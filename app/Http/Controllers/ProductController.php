<?php

namespace App\Http\Controllers;

use App\Modules\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**新商品の新規
     * @param Request $request
     * @return mixed
     */
    public function saveOrUpdate(Request $request)
    {
        return $this->productService->saveOrUpdate($request);
    }

    /**商品の削除
     * @param Request $request
     */
    public function delete(Request $request){
        return $this->productService->delete($request);
    }

    /**商品分页列表
     * @param Request $request
     * @return mixed
     */
    public function pager(Request $request)
    {
        return $this->productService->getPagerList($request);
    }

    /**商品リストを取得する
     * @param Request $request

    public function lists (Request $request){
        return $this->productService->getLists($request);
    }
    */
    
    /** 画像のアップロード
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
}
