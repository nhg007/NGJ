<?php


namespace App\Modules\Services;


use Illuminate\Http\Request;

interface OrderService
{

    //すべての注文を取得する
    public function pager(Request $request);

    //生成订单
    public function createOrder(Request $request);

    //キャンセル订单
    public function cancelOrder(Request $request);

    //注文の支払い方法と状態を更新する
    public function updateOrderPaymentAndStatus($token, $status, $payStatus);

    //注文リストを取得する
    public function getUserOrders();

    public function getOrderInfo($id);

    //注文支払い情報を更新する
    public function updateOrderPayment(Request $request);
}
