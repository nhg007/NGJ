<?php

namespace App\Http\Controllers;

use App\Models\Vo\JsonResult;
use App\Modules\Services\OrderService;
use App\Modules\Services\RestrantServcie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    private $orderService;
    private $restrantServcie;

    //
    public function __construct(OrderService $orderService, RestrantServcie $restrantServcie)
    {
        $this->orderService = $orderService;
        $this->restrantServcie = $restrantServcie;
    }

    public function pager(Request $request)
    {
        return $this->orderService->pager($request);
    }

    public function createOrder(Request $request)
    {
        return $this->orderService->createOrder($request);
    }

    public function cancelOrder(Request $request)
    {
        return $this->orderService->cancelOrder($request);
    }

    public function updateOrderInfo(Request $request)
    {
        return $this->orderService->updateOrderInfo($request);
    }


    public function payment(Request $request)
    {
        $order_id = $request->get('order_id');
        //检查order
        $order = $this->orderService->getOrderInfo($order_id);
        if (!isset($order)) {
            abort(404, "注文情報を見つかりませんでした");
        }
        $restrant = $this->restrantServcie->getRestrantInfo($order->restrant_id);
        $takeout = $this->restrantServcie->getRestrantCanTakeOutTimes($order->restrant_id);
        return view('payment.index', [
            'user' => Auth::user(),
            "order" => $order,
            "restrant" => $restrant,
            'takeoutTimes' => $takeout]);
    }

    /**
     * 注文情報を更新する
     * @param Request $request
     * @return mixed
     */
    public function paymentPost(Request $request)
    {
        return $this->orderService->updateOrderPayment($request);
    }

    public function paySuccess(Request $request)
    {
        //1:現地決済、2:クレジットカード
        //1:代引き、2:クレジットカード
        $payStatus = 1; //订单完成
        $orderStatus = 1; //支付成功;
        $order = $this->orderService->updateOrderPaymentAndStatus($request->token, $orderStatus, $payStatus);
        return view('payment.success', ["order" => $order, "email" => Auth::user()->email]);
    }

    public function payFailed(Request $request)
    {
        //支付方式
        //1:現地決済、2:クレジットカード
        $orderStatus = 1; //订单完成
        $payStatus = 2; //支付失败
        $order = $this->orderService->updateOrderStatus($request->orderId, $orderStatus, $payStatus);
        return view('payment.failed',["order" => $order, "email" => Auth::user()->email]);
    }
}
