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

        $totalPrice = 0;
        $goods_number = 0;
        $cartIds = $request->get('cartIds');
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

            $cartArray = $this->cartService->getItems($cartIds);

            foreach ($cartArray as $cart) {
                $goods_number = $goods_number + $cart->number;
                $totalPrice = $totalPrice + $cart->price * $cart->number;
            }

            //商品数量
            $order->product_number = $goods_number;

            //商品价格总价
            $order->product_amount = $totalPrice;

            //注文価格
            $order->order_amount = 0;

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

    public function updateOrderPayment(Request $request){

        $payment = $request->payment;
        if($payment == '1'){
            return $this->updateOrderDeliveryPayment($request);
        }else{
            return $this->updateOrderTakeoutPayment($request);
        }
    }

    //現地取りの場合
    public function updateOrderTakeoutPayment(Request $request)
    {
        // TODO: Implement updateOrderPayment() method.

        $id = $request->id;
        $consignee = $request->consignee;
        $tel = $request->tel;
        $post = $request->post;
        $pref = $request->pref;
        $address = $request->address;
        $remark = $request->remark;

        $delivery_date = $request->delivery_date;
        $takeoutId = $request->takeoutId;
        $receiveType = $request->receiveType;

        //お届け先
        if ($receiveType == '登録住所') {
            $user = Auth::user();
            $consignee = $user->name;
            $post = $user->post;
            $pref = $user->pref;
            $tel = $user->telphone;
            $address = $user->address;
        }

        $order = Order::find($id);
        if (!isset($order)) {
            return response()->json(JsonResult::fail('注文情報を見つかりません', 404, $order));
        }

        //最後の支払い金額を計算する
        $products = $order->products;
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->pivot->product_price * $product->pivot->product_number;
        }
        //手续费
        $order->tip_fee = 0;
        //配送料
        $order->delivery_fee = 0;

        $total = $totalPrice;
        $total = $total + $total * 0.1;

        //支付金额
        $order->order_amount = round($total);


        $filename = public_path('uploads') . DIRECTORY_SEPARATOR. "lock.txt";
        $file = fopen($filename, "r+"); //打开文件
        $lock = flock($file, LOCK_EX);
        $cantakeOut = true;
        $message = "";
        if ($lock) {
            try {
                //获取takeout 时间
                $restrantTakeout = RestrantTakeOut::find($takeoutId);
                if(!isset($restrantTakeout)){
                    $cantakeOut = false;
                    $message = 'テイクアウト来店時間帯情報を見つかりません';
                }else{
                    if($restrantTakeout ->number == 0){
                        $cantakeOut = false;
                        $message = 'ご予約がいっぱいですので、他の時間帯をお選びください';
                    }
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
                fclose($file); //关闭文件句柄
            }
        }


        if(!$cantakeOut){
            return response()->json(JsonResult::fail($message, 500, $order));
        }

        //注文状態
        $order->status = 0;
        //支払い方式
        $order->payment = 2;
        $order->receive_type = $receiveType;
        $order->consignee = $consignee;
        $order->pref = $pref;
        $order->tel = $tel;
        $order->post = $post;
        $order->address = $address;
        $order->remark = $remark;
        $order->delivery_date = $delivery_date;
        $order->restrant_take_out_id = $takeoutId;
        $order->token = $this->GetRandStr(15) . '_' . $order->id;
        $order->save();

        return response()->json(JsonResult::success('注文を完成しました', 200, $order));
    }

    //郵便の場合
    public function updateOrderDeliveryPayment(Request $request)
    {
        $id = $request->id;
        $consignee = $request->consignee;
        $tel = $request->tel;
        $post = $request->post;
        $pref = $request->pref;
        $address = $request->address;
        $remark = $request->remark;
        $payment = $request->payment;
        $receiveType = $request->receiveType;
        $receiveDate = $request->receiveDate;
        $receiveTime = $request->receiveTime;

        //お届け先
        if ($receiveType == '登録住所') {
            $user = Auth::user();
            $consignee = $user->name;
            $post = $user->post;
            $pref = $user->pref;
            $tel = $user->telphone;
            $address = $user->address;
        }

        $order = Order::find($id);
        if (!isset($order)) {
            return response()->json(JsonResult::fail('注文情報を見つかりません', 404, $order));
        }

        //注文状態
        $order->status = 0;
        //支払い方式
        $order->payment = 1;
        $order->consignee = $consignee;
        $order->tel = $tel;
        $order->post = $post;
        $order->address = $address;
        $order->remark = $remark;
        $order->receive_type = $receiveType;
        $order->pref = $pref;
        $order->receiveDate = $receiveDate;
        $order->receiveTime = $receiveTime;


        //最後の支払い金額を計算する
        $products = $order->products;
        $totalPrice = 0;
        //商品の合計金額
        $normal = false;
        $cold = false;
        foreach ($products as $product) {
            if ($product->keeping == '常温便') {
                $normal = true;
            }

            if ($product->keeping == '冷凍便') {
                $cold = true;
            }
            $totalPrice += $product->pivot->product_price * $product->pivot->product_number;
        }

        //计算运费
        $fee1 = 972;
        if ($pref == '北海道' || $pref == '沖縄県') {
            $fee1 = 1404;
        }

        $tipfee = 0;
        if ($payment == '1') {
            $tipfee = 324;
        }

        //检查商品是否是常温和冷冻混合
        //冷凍と常温が混在するので、送料が二倍となる
        if ($normal && $cold) {
            $fee1 = $fee1 * 2;
        }

        //手续费
        $order->tip_fee = $tipfee;
        //配送料
        $order->delivery_fee = $fee1;

        $total = $totalPrice + $fee1 + $tipfee;
        $total = $total + $total * 0.1;

        //支付金额
        $order->order_amount = round($total);

        $order->token = $this->GetRandStr(15) . '_' . $order->id;

        $order->save();

        return response()->json(JsonResult::success('注文を完成しました', 200, $order));
    }

    function GetRandStr($length)
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }


    public function updateOrderPaymentAndStatus($token, $status, $payStatus)
    {
        $order = Order::where(['token' => $token])->first();
        if (!isset($order)) {
            abort(404, '注文情報を見つかりませんでした');
            return;
        }

        $restrantId = $order->restrant_id;

        //支付状态为支付成功
        $order->pay_status = $payStatus;
        //订单正常完成
        $order->status = $status;


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
