<?php


namespace App\Modules\Services\impl;


use App\Models\Product;
use App\Models\Vo\JsonResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Modules\Services\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\exactly;



class ProductServiceImpl implements ProductService
{

    /**
     * @inheritDoc
     */
    public function getPagerList(Request $request)
    {
        $keyword = $request->json('keyword');
        $limit = $request->json('limit');
        $restrantId=  Auth::guard('restrant')->id();

        $product = null;
        if (isset($keyword)) {
            $product = Product::where([
                    ['name', 'like', $keyword . '%'],
                    ['restrant_id', '=', $restrantId],
                ])
                ->paginate(isset($limit) ? $limit : 10);
        } else {

            $product = Product::where([
                ['restrant_id', '=', $restrantId],
            ])->paginate(isset($limit) ? $limit : 10);
        }

        $total = $product->total();
        $items = $product->items();
        return response()->json(JsonResult::success('リストを取得しました', 200, ["total" => $total, "data" => $items]));
    }

    /**
     * @param Request $request
     * @return mixed|void
     */
    public function getLists()
    {
        return Product::where('stock_status','<>','売り切れ')->orderByDesc('updated_at')->get();
    }


    public function getListsByRestrantId($restrantId)
    {
        $list = Product::where([
            ['stock_status','<>','売り切れ'],
            ['restrant_id','=',$restrantId],
            ])->orderByDesc('updated_at')->get();

        //查询每个商品已经卖了多少件数
        foreach ($list as $key=>$value){

            $value->saledNumber =  $this->saledProductNumber($value->id);
        }

        return $list;
    }

    public function saveOrUpdate(Request $request)
    {
        $restrantId=  Auth::guard('restrant')->id();

        $id = $request->get('id');
        //商品名
        $name = $request->get('name');
        //内容量
        $column = $request->get('column');
        //贩卖数量
        $number = $request->get('number');
        //価格
        $price = $request->get('price');
        //在庫状況
        $stockStatus = $request->get('stock_status');
        //詳細情報
        $description = $request->get('description');

        $files = $request->get('files');


        $message = "新規成功しました";
        $product = null;
        if (isset($id) && $id > 0) {
            $product = Product::firstWhere('id', $id);
            if (!isset($product)) {
                return response()->json(JsonResult::fail("商品の情報を見つかりませんでした", 404));
            }
            $message = "更新成功しました";
        } else {
            $product = new Product();
        }
        $product->name = $name;
        $product->column = $column;
        $product->price = $price;
        $product->number = $number;
        $product->stock_status = $stockStatus;
        $product->description = $description;
        $product->restrant_id= $restrantId;

        if (isset($files) && sizeof($files) > 0) {
            $file = $files[0];
            if ($product->picture_path != $file['url']) {
                //删除文件
                if (Storage::disk('uploads')->exists($product->picture_path)) {
                    Storage::disk('uploads')->delete($product->picture_path);
                }
            }
            $product->original_name = $file['name'];
            $product->picture_path = $file['url'];
        }
        $product->save();
        return response()->json(JsonResult::success($message, 200, $product));
    }

    public function delete(Request $request)
    {
        // TODO: Implement delete() method.
        $id = $request->get('id');

        $product = $this->selectOne($id);

        //商品不存在的情况下
        if (!isset($product)) {
            return response()->json(JsonResult::fail("商品の情報を見つかりませんでした", 404));
        }

        //file文件的删除
        if(isset($product->picture_path)){

            //删除文件
            if (Storage::disk('uploads')->exists($product->picture_path)) {
                Storage::disk('uploads')->delete($product->picture_path);
            }
        }
        $product->delete();
        return response()->json(JsonResult::success('商品を削除しました', 200, $product));
    }

    public function selectOne($id)
    {
        return  Product::firstWhere('id', $id);
        // TODO: Implement selectOne() method.
    }

    public function saledProductNumber($productId)
    {
        // TODO: Implement saledProductNumber() method.

        $now = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        return DB::table('restrants_orders_product')
            ->where('product_id','=',$productId)
            ->where('created_at','>',$now)
            ->where('created_at','<',$tomorrow)
            ->sum('product_number');
    }


}
