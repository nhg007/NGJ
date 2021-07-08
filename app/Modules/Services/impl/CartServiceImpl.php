<?php


namespace App\Modules\Services\impl;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Vo\JsonResult;
use App\Modules\Services\CartService;
use App\Modules\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartServiceImpl implements CartService
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function addCart($restrantId,$product)
    {

        //ユーザーID
        $userID = Auth::id();
        $product_id = $product->id;
        $price = $product->price;

        $cart = Cart::where('user_id',$userID)
            ->where('product_id',$product_id)
            ->where('price',$price)->first();
        if(isset($cart)){
            //データを存在すると、数量を増加する
            $cart->number = $cart->number + 1;
        }else{
            //新しくデータを追加する
            $cart = new Cart([
               'user_id'=>$userID,
                'restrant_id'=>$restrantId,
                'product_id'=>$product_id,
                'price'=>$price,
                'number'=>1
            ]);
        }

        //检查一下购物车里的数量
        if(!$this->checkLeftProductNumber($product_id,$cart->number)){
            //カートの中に商品数量を取得する
            return response()->json(JsonResult::fail('商品在庫数が不足しており、ショッピングカートへのご加入はできません', 500, ""));
        }

        $cart->save();

        return response()->json(JsonResult::success('', 200, $this->getUserCart()->sum('number')));
    }

    public function checkLeftProductNumber($productId,$number){
       $product = Product::find($productId);
       //如果没有设置商品数量就认为是放行的
       if(!isset($product->number)){
           return true;
       }

       //在订单明细表里查询商品已经卖了多少件了
       $saled = $this->productService->saledProductNumber($productId);
       if($number + $saled > $product->number){
           return false;
       }

       return true;
    }

    /**
     * カートの情報を取得する
     * @return mixed
     */
    public function getUserRestrantCart()
    {
        $user_id = Auth::id();
        $RestrantArray = DB::select('SELECT carts.restrant_id, restrants.name as restrant_name FROM carts INNER JOIN restrants ON carts.restrant_id=restrants.id WHERE carts.user_id = :uid GROUP BY carts.restrant_id ORDER BY carts.restrant_id', ['uid' => $user_id]);
        $RestrantCartsArray =[];

        for ($i=0; $i<sizeof($RestrantArray); $i++)
        {
            $RestrantCartsArray[$i]["restrant_id"]=$RestrantArray[$i]->restrant_id;
            $RestrantCartsArray[$i]["restrant_name"]=$RestrantArray[$i]->restrant_name;
            $RestrantCartsArray[$i]["restrant_carts"]=Cart::where([['user_id',$user_id],['restrant_id',$RestrantArray[$i]->restrant_id]])->orderBy('created_at','DESC')->get()->each(function ($item){
                $item['product'] = $item->product;
            });
        }
        return $RestrantCartsArray;
    }

    public function getUserCart()
    {
        $user_id = Auth::id();
        $result = Cart::where('user_id',$user_id)->orderBy('created_at','DESC')->get()->each(function ($item){
            $item['product'] = $item->product;
        });
        return $result;
    }

    public function deleteItem($id)
    {
        Cart::destroy($id);
        return response()->json(JsonResult::success('', 200, $id));;
    }

    public function mulDelete(Request $request)
    {
        // TODO: Implement multyDelete() method.

        $ids = $request->get('ids');
        Cart::destroy($ids);
        return response()->json(JsonResult::success('', 200, $ids));;
    }

    public function getItems($cartIds)
    {
        if(gettype($cartIds) == 'string'){
            $arr = explode(",",$cartIds);
        }else{
            $arr = $cartIds;
        }
        return Cart::find($arr)->each(function ($item){
            $item['product'] = $item->product;
        });
    }

    public function updateCartProductNumber($cartId, $number)
    {
        $cart = Cart::find($cartId);
        if(!isset($cart)){
            return response()->json(JsonResult::fail('カートの情報を見つかりません', 404));
        }


        //检查库存数够不够
        if(!$this->checkLeftProductNumber($cart->product_id,$number)){
            //カートの中に商品数量を取得する
            return response()->json(JsonResult::fail('商品在庫数が不足しており、ショッピングカートへのご加入はできません', 500, ""));
        }


        $cart->number = $number;
        $cart->save();

        response()->json(JsonResult::success('更新しました', 200));;

        // TODO: Implement updateCartProductNumber() method.
    }

    public function getRestrantByID($restrantId)
    {
        return DB::table('restrants')->where('id',$restrantId)->first();
    }

}
