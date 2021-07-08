<?php


namespace App\Modules\Services\impl;

use App\Mail\MerchantMail;
use App\Mail\UserMail;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Restrant;
use App\Models\RestrantTakeOut;
use App\Models\User;
use App\Models\Vo\JsonResult;
use App\Modules\Services\CartService;
use \App\Modules\Services\OrderService;
use App\Modules\Services\ProductService;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class OrderServiceImpl implements OrderService
{
    private $cartService;
    private $productService;

    public function __construct(CartService $cartService, ProductService $productService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
    }

    public function pager(Request $request)
    {
        $keyword = $request->get('keyword');
        $limit = $request->get('limit');
        $restrantId=  Auth::guard('restrant')->id();
        //dump($restrantId);
        $order = null;
        if (isset($keyword)) {
            $order = Order::where([
                ['order_sn', 'like', '%' . $keyword . '%'],
                ['restrant_id', '=', $restrantId],
            ])->orderBy('updated_at', 'desc')->paginate(isset($limit) ? $limit : 10);
        } else {
            $order = Order::where('restrant_id', '=', $restrantId)->orderBy('updated_at', 'desc')->paginate(isset($limit) ? $limit : 10);
        }

        //获取订单商品，订单用户
        $order->each(function ($item) {
            $item['products'] = $item->products;
            $item['user']= $item->user;
        });
        $total = $order->total();
        $items = $order->items();
        return response()->json(JsonResult::success('リストを取得しました', 200, ["total" => $total, "data" => $items, "limit" => $limit]));
    }

    public function createOrder(Request $request)
    {

        $cartIds = $request->get('cartIds');
        $totalPrice = $request->get('totalPrice');
        $goods_number = $request->get('goodNumber');
        $restrantId  = $request->get('restrantId');
        DB::beginTransaction();
        try {

            //
            $order = new Order();
            //注文番号
            $order->order_sn = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            //ユーザー
            $order->user_id = Auth::id();

            $order->restrant_id = $restrantId;
            //商品数量
            $order->product_number = $goods_number;
            //注文価格
            $order->order_amount = $totalPrice;

            //注文状態
            $order->status = 0;

            //未支払い
            $order->pay_status = 0;

            //支払い方法
            $order->payment = 0;

            $order->save();

            $id = DB::getPdo()->lastInsertId();

            $cartArray = $this->cartService->getItems($cartIds);
            foreach ($cartArray as $cart) {
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $id;
                $orderProduct->product_id = $cart->product_id;
                $orderProduct->product_price = $cart->price;
                $orderProduct->product_number = $cart->number;


                //检查一下商品的数量够不够
                $saledNumber = $this->productService->saledProductNumber($cart->product_id);
                $product = $cart->product;
                if(isset($product->number)){
                    if($cart->number + $saledNumber > $product->number){
                        throw new \InvalidArgumentException("商品在庫数が不足して注文が失敗しました");
                    }

                    if($product->number == $cart->number + $saledNumber){
                        $product->stock_status = '売り切れ';
                    }else{
                        if($product->number - $cart->number + $saledNumber <= 2){
                            $product->stock_status = '残りわずか';
                        }
                    }
                    $product->save();
                }

                $orderProduct->save();

                //カートのデータを削除する
                $cart->delete();
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(JsonResult::fail($ex->getMessage(), 500, null ));
        }
        return response()->json(JsonResult::success('', 200, $order));
    }

    public function getOrderInfo($id)
    {
        return Order::find($id);
    }


    public function getUserOrders()
    {
        $userId = Auth::id();
        return Order::where('user_id', $userId)->orderBy('updated_at', 'DESC')->get()->each(function ($item) {
            $item['products'] = $item->products;
            $item['restrant'] =  Restrant::where('id',$item->restrant_id)->first();

            //$item['products'] = OrderProduct::where('order_id','=',$item['id'])->orderByDesc('updated_at')->get();

           // $item['products']['number'] = $item->pivot->product_number;
        });


    }

    public function cancelOrder(Request $request)
    {
        $orderId = $request->get('id');
        $order = Order::find($orderId);

        if (!isset($order)) {
            return response()->json(JsonResult::fail('注文情報を見つかりません', 404, $order));
        }
        //注文がキャンセルされた
        $order->status = 2;
        $order->save();

        return response()->json(JsonResult::success('注文がキャンセルされました', 200, $order));

    }

    public function updateOrderPayment(Request $request)
    {
        // TODO: Implement updateOrderPayment() method.

        $id = $request->id;
        //$payment = $request->payment;
        $consignee = $request->consignee;
        $tel = $request->tel;
        $post = $request->post;
        $address = $request->address;
        $remark = $request->remark;

        $delivery_date = $request->delivery_date;
        $takeoutId = $request->takeoutId;

        $order = Order::find($id);
        if (!isset($order)) {
            return response()->json(JsonResult::fail('注文情報を見つかりません', 404, $order));
        }

        $filename = storage_path('app') . DIRECTORY_SEPARATOR. "lock";
        $file = fopen($filename, 'w'); //打开文件
        $lock = flock($file, LOCK_EX);
        $cantakeOut = true;
        $message = "";
        if ($lock) {
            try {
                //获取takeout 时间
                $restrantTakeout = RestrantTakeOut::find($takeoutId);
                if(!isset($restrantTakeout)){
                    $cantakeOut = false;
                    fclose($file); //关闭文件句柄
                    abort(404,"テイクアウト来店時間帯情報を見つかりません");
                    return false;
                }
                if($restrantTakeout ->number == 0){
                    $cantakeOut = false;
                    $message = 'ご予約がいっぱいですので、他の時間帯をお選びください';
                }

                //成功了
                if($cantakeOut){
                    //更新预约数
                    $restrantTakeout-> number = $restrantTakeout-> number - 1;
                    $restrantTakeout->save();
                }
            }catch (\Exception $e){
                $cantakeOut = false;
                $message = $e->getMessage();
            } finally {
                flock($file, LOCK_UN); //无论如何都要释放锁
            }
        }
        fclose($file); //关闭文件句柄

        if(!$cantakeOut){
            return response()->json(JsonResult::fail($message, 500, $order));
        }

        //注文状態
        $order->status = 0;
        //支払い方式
        //$order->payment = $payment;
        $order->consignee = $consignee;
        $order->tel = $tel;
        $order->post = $post;
        $order->address = $address;
        $order->remark = $remark;
        $order->delivery_date = $delivery_date;
        $order->restrant_take_out_id = $takeoutId;
        $order->save();


        return response()->json(JsonResult::success('注文を完成しました', 200, $order));
    }

    public function updateOrderPaymentAndStatus($id, $payment, $status, $payStatus)
    {
        $order = Order::find($id);
        if (!isset($order)) {
            return null;
        }

        $restrantId = $order->restrant_id;

        //支付状态为支付成功
        $order->pay_status = $payStatus;
        //订单正常完成
        $order->status = $status;

        //支付方式
        $order->payment = $payment;

        $order->save();


        $restrant = DB::table('restrants')->where('id',$restrantId)->first();

        //客様へ送信する
        $user = User::find(Auth::id());
        $email = $user->email;
        Mail::to($email)->send(new UserMail($order,$restrant));

        //サイトへ送信する

        $restrantMail = $restrant->email;
        Mail::to($restrantMail)->send(new MerchantMail($order));

        return $order;
    }
}
