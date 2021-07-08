<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;

// Index Controller
Route::get('/', [IndexController::class, 'index'])->name('top');
Route::get('/detail', [IndexController::class, 'detail'])->name('detail');
Route::get('/getCityListApi', [IndexController::class, 'getCityListApi'])->name('getCityListApi');
Route::get('/getLocationRangeApi', [IndexController::class, 'getLocationRangeApi'])->name('getLocationRangeApi');
Route::post('/getRestrantPageListApi', [IndexController::class, 'getRestrantPageListApi'])->name('getRestrantPageListApi');
Route::post('/getRestrantRangeListApi', [IndexController::class, 'getRestrantRangeListApi'])->name('getRestrantRangeListApi');

//商品リストを取得する
Route::get('/product/lists',[ProductController::class, 'lists']);

Route::post('/cart/getProductNum', 'App\Http\Controllers\CartController@getProductNum')->name('getProductNum');
Route::post('/cart/addCart/{restrantId?}/{productId?}', 'App\Http\Controllers\CartController@addCart')->name('addCart');

Route::get('/paySuccess','App\Http\Controllers\OrderController@paySuccess')->name('paySuccess');
Route::get('/payFailed','App\Http\Controllers\OrderController@payFailed')->name('payFailed');

Route::middleware(['auth:sanctum', 'verified'])->group(function (){

    //ダシュボード
    Route::get('/dashboard', 'App\Http\Controllers\Controller@dashboard')->name('dashboard');

    //カートのルート
    Route::get('/cart/{productId?}', 'App\Http\Controllers\CartController@cart')->name('cart');

    //カート項目の削除
    Route::delete('/cart/delete/{id}','App\Http\Controllers\CartController@delete')->name('deleteItem');

    //商品の削除
    Route::delete('/cart/mulDelete','App\Http\Controllers\CartController@mulDelete')->name('mulDelete');

    //カートの商品数量の変更
    Route::post('/cartProductNumber', 'App\Http\Controllers\CartController@updateCartProductNum')->name('cartProductNumber');

    //注文の確認
    Route::post('/cartConfirm', 'App\Http\Controllers\CartController@cartConfirm')->name('cartConfirm');

    Route::post('/checkCartItem','App\Http\Controllers\CartController@checkCartItem')->name('checkCartItem');

    //注文を作成する
    Route::post('/createOrder','App\Http\Controllers\OrderController@createOrder')->name('createOrder');

    //注文キャンセル
    Route::post('/cancelOrder','App\Http\Controllers\OrderController@cancelOrder')->name('cancelOrder');

    //注文基本情報を更新する
    Route::post('/updateOrderInfo','App\Http\Controllers\OrderController@updateOrderInfo')->name('updateOrderInfo');

    //お支払い
    Route::post('/payment','App\Http\Controllers\OrderController@payment') -> name('payment');

    //お支払い確定
    Route::post('/paymentPost','App\Http\Controllers\OrderController@paymentPost') -> name('paymentPost');
});

// Admin Controller
Route::view('/admin', 'admin.login');
Route::post('/admin/login', 'App\Http\Controllers\AdminController@loginAdmin');
Route::any('/admin/logout', 'App\Http\Controllers\AdminController@logout');
Route::middleware(['auth:admin'])->group( function () {

    Route::any('/admin/index', 'App\Http\Controllers\AdminController@index');
    Route::any('/admin/logout', function (){
        \Illuminate\Support\Facades\Auth::logout();
        return redirect("/admin");
    });

    Route::any('/admin/getAdmin', 'App\Http\Controllers\AdminController@getAdmin');

    //商品リスト
    Route::post('/admin/product/pager','App\Http\Controllers\ProductController@pager');

    //商品の追加
    Route::post('/admin/product/save','App\Http\Controllers\ProductController@saveOrUpdate');

    //商品の削除
    Route::post('/admin/product/delete','App\Http\Controllers\ProductController@delete');

    //注文リスト
    Route::post('/admin/order/pager','App\Http\Controllers\OrderController@pager');

    //商品画像のアップロード
    //Route::any('/admin/product/uploadPic','App\Http\Controllers\ProductController@uploadPic');
    Route::any('/admin/restrantLogin', 'App\Http\Controllers\AdminController@restrantLogin');

});

//restrant
Route::view('/restrant', 'restrant.login');
Route::post('/restrant/login', 'App\Http\Controllers\RestrantController@loginRestrant');
Route::any('/restrant/logout', 'App\Http\Controllers\RestrantController@logout');
Route::middleware(['auth:restrant'])->group( function () {
    Route::any('/restrant/index', 'App\Http\Controllers\RestrantController@index');

    Route::any('/restrant/logout', function (){
        \Illuminate\Support\Facades\Auth::logout();
        return redirect("/restrant");
    });

    Route::any('/restrant/getAdmin', 'App\Http\Controllers\RestrantController@getAdmin');

    //商品リスト
    Route::post('/restrant/product/pager','App\Http\Controllers\ProductController@pager');

    //商品の追加
    Route::post('/restrant/product/save','App\Http\Controllers\ProductController@saveOrUpdate');

    //商品の削除
    Route::post('/restrant/product/delete','App\Http\Controllers\ProductController@delete');

    //注文リスト
    Route::post('/restrant/order/pager','App\Http\Controllers\OrderController@pager');

    //商品画像のアップロード
    //Route::any('/restrant/product/uploadPic','App\Http\Controllers\ProductController@uploadPic');

});

Route::view('/about', 'footer.about');
Route::view('/company', 'footer.company');
Route::view('/contact', 'footer.contact');
