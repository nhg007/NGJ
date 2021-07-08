<?php


namespace App\Modules\Services;


use Brick\Math\BigInteger;
use Illuminate\Http\Request;

interface ProductService
{

//    public function

    /**商品の情報を取得する
     * @param Request $request
     * @return mixed
     */
    public function selectOne($bigInteger);

    /**商品リストを取得
     * @param Request $request
     * @return mixed
     */
    public function getPagerList(Request $request);

    /**获取Lists
     * @return mixed
     */
    public function getLists();

    /**商品の新規
     * @param Request $request
     * @return mixed
     */
    public  function saveOrUpdate(Request $request);

    /**商品の削除
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request);

    //查询卖了多少件商品
    public function saledProductNumber($productId);
}
