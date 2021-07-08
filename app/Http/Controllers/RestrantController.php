<?php

namespace App\Http\Controllers;

use App\Models\Vo\JsonResult;
use App\Modules\Services\RestrantServcie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restrant;

class RestrantController extends Controller
{

    private $restrantServcie;
    public function __construct(RestrantServcie $restrantServcie){
        $this->restrantServcie = $restrantServcie;
    }

    public function loginRestrant(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (Auth::guard('restrant')->attempt(['email' => $email, 'password' => $password])) {

            return response()->json(JsonResult::success('ログイン成功しました'));
        }
        return response()->json(JsonResult::fail('ログイン失敗しました'));
    }

    public function getRestrant(Request $request)
    {
        $user = Auth::guard('restrant')->user();
        $images = $user->RestrantImage;
        return response()->json(['user' => $user,'images'=> $images]);;
    }

    public function logout(Request $request)
    {
        Auth::guard('restrant')->logout();
    }

    public function index(Request $request){
        $user=  Auth::guard('restrant')->user()->toArray();
        return view('restrant.index',['user' => $user]);
    }


    //上传图片
    public function uploadPic(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([], 500, 'アップロードされたファイルを取得できません');
        }
        $file = $request->file('file');

        if ($file->isValid()) {
            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg

            // 上传文件
            $filename = date('Ymd/His');
            // 使用我们新建的uploads本地存储空间（目录）
            $path = $file->store($filename, 'uploads');
            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'photo' => $path,
                'name' => $originalName,
            ]);

        } else {
            return response()->json([], 500, 'アップロードできませんでした');
        }
    }

    public function update(Request $request){
        return $this->restrantServcie->update($request);
    }

    public function  getRestrantTakeDate(Request $request){
        $restrantId =  Auth::guard('restrant')->id();
        $date = $request->get('takeDate');
        return $this->restrantServcie->getRestrantTakeDate($restrantId,$date);
    }

    public  function getRestrantTakeOutLists(Request $request){
        $restrantId =  Auth::guard('restrant')->id();
        $date = $request->get('takeDate');
        return $this->restrantServcie->getRestrantTakeOutDatas($restrantId,$date);
    }

    public function saveTakeout (Request $request){

        return $this->restrantServcie->updateTakeOutRule($request);
    }
}
