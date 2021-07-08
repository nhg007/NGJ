<?php

namespace App\Http\Controllers;

use App\Modules\Services\OrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function dashboard()
    {

        //ユーザーの注文リスト
        $list = $this->orderService->getUserOrders();

        //支払い
        $payOrders = $list->where('pay_status', 0)->where('status', 0);

        return view('dashboard', ["orders" => $list, 'email'=>Auth::user()->email, 'payOrders' => $payOrders]);
    }
}
