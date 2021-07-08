<?php

namespace App\Http\Controllers;
use App\Models\Vo\JsonResult;
use App\Modules\Services\RestrantServcie;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Restrant;

class AdminController extends Controller
{

    private $restrantService;

    public function __construct( RestrantServcie $restrantService)
    {
        $this->restrantService = $restrantService;
    }

    public function loginAdmin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {

            return response()->json(JsonResult::success('ログイン成功しました'));
        }
        return response()->json(JsonResult::fail('ログイン失敗しました'));
    }


    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $restrantlist= $this->restrantService->getLists($request);
        //$restrantlist = Restrant::orderBy('created_at','asc')->paginate(20);
        return view('admin.index',["restrantlist" => $restrantlist]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
    }

    public function restrantLogin(Request $request)
    {
        $id= $request->get("id");

        if (Auth::guard('restrant')->loginUsingId($id)) {
            return redirect("/restrant/index");
        }
    }
}
