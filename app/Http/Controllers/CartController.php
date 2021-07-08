<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Vo\JsonResult;
use App\Modules\Services\CartService;
use App\Modules\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    private $cartService;
    private $productService;

    public function __construct(
        ProductService $productService,
        CartService $cartService)
    {
        $this->productService = $productService;
        $this->cartService = $cartService;
    }

    public function getProductNum()
    {
        if (!Auth::check()) {
            return response()->json(JsonResult::success('', 201));
        }

        return response()->json(JsonResult::success('', 200, $this->cartService->getUserCart()->sum('number')));
    }

    public function addCart($restrantId,$productId, Request $request)
    {

        //未ログインの場合
        if (!Auth::check()) {
            return response()->json(JsonResult::success('', 201));
        }
        //1.商品をデーターベースのカートテーブルに保存する
        $product = $this->productService->selectOne($productId);
        if (!isset($product)) {
            return response()->json(JsonResult::success('商品情報を存在していません', 404, null));
        }
        //2.カートのすべての情報を取得する
        return $this->cartService->addCart($restrantId,$product);

    }

    /**カートに商品の数量を更新する
     * @param $cartId
     * @param $number
     */
    public  function updateCartProductNum(Request $request){
        $cartId = $request->cartId;
        $number = $request->number;
        return $this->cartService->updateCartProductNumber($cartId,$number);
    }

    //
    public function cart($productId = null)
    {
        $restrantCarts = $this->cartService->getUserRestrantCart();
        //dump($restrantCarts);
        return view('cart.index', ["restrantCarts" => $restrantCarts]);
    }

    //注文内容の確認
    public function cartConfirm(Request $request)
    {
        $restrantId= $request->get('restrantId');
        $restrant = $this->cartService->getRestrantByID($restrantId);
        $cartIds = $request->get('cartIds');
        if (!isset($cartIds)) {
            throw  new NotFoundHttpException();
        }
        $list = $this->cartService->getItems($cartIds);
        $goodNumber = $list->sum('number');
        return view('cart.confirm', ["restrant"=>$restrant,"carts" => $list, "goodNumber" => $goodNumber,"cartIds"=>$cartIds]);
    }

    public function checkCartItem(Request $request){
        $cartIds = $request->get('cartIds');
        $list = $this->cartService->getItems($cartIds);

        return response()->json(JsonResult::success('更新しました', 200,sizeof($list)));;
    }


    /**カートに一つ商品を削除する
     * @param Request $request
     */
    public function delete($id)
    {
        return $this->cartService->deleteItem($id);
    }

    public function mulDelete(Request $request)
    {
        return $this->cartService->mulDelete($request);
    }
}
