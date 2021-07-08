<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestrantController;

// Index Controller
Route::get('/', [IndexController::class, 'index'])->name('top');
Route::get('/detail', [IndexController::class, 'detail'])->name('detail');
Route::get('/getCityListApi', [IndexController::class, 'getCityListApi'])->name('getCityListApi');
Route::get('/getLocationRangeApi', [IndexController::class, 'getLocationRangeApi'])->name('getLocationRangeApi');
Route::post('/getRestrantPageListApi', [IndexController::class, 'getRestrantPageListApi'])->name('getRestrantPageListApi');
Route::post('/getRestrantRangeListApi', [IndexController::class, 'getRestrantRangeListApi'])->name('getRestrantRangeListApi');

// Admin Controller
Route::view('/admin', 'admin.login');
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminController::class, 'loginAdmin']);
    Route::any('/logout', [AdminController::class, 'logout']);
    Route::middleware(['auth:admin'])->group(function () {
        Route::any('/index', [AdminController::class, 'index']);
        Route::any('/logout', function () {
            \Illuminate\Support\Facades\Auth::logout();
            return redirect("/admin");
        });
        Route::any('/restrantLogin', [AdminController::class, 'restrantLogin']);
    });
});

// Restrant Controller
Route::view('/restrant', 'restrant.login');
Route::prefix('restrant')->group(function () {
    Route::post('/login', [RestrantController::class, 'loginRestrant']);
    Route::any('/logout', [RestrantController::class, 'logout']);
    Route::middleware(['auth.restrant'])->group(function () {
        Route::any('/index', [RestrantController::class, 'index']);
        Route::any('/logout', function () {
            \Illuminate\Support\Facades\Auth::logout();
            return redirect("/restrant");
        });

        Route::get('/info', [RestrantController::class, 'getRestrant']);

        //商品リスト
        Route::post('/product/pager', [ProductController::class, 'pager']);
        //商品の追加
        Route::post('/product/save', [ProductController::class, 'saveOrUpdate']);
        //商品の削除
        Route::post('/product/delete', [ProductController::class, 'delete']);
        //注文リスト
        Route::post('/order/pager', [OrderController::class, 'pager']);

        Route::post('/update', [RestrantController::class, 'update']);

        Route::post('/takeOut/update', [RestrantController::class, 'saveTakeout']);

        Route::post('/takeOut/getLists',[RestrantController::class,'getRestrantTakeOutLists']);

        Route::post('/takeout/getTakeDates',[RestrantController::class,'getRestrantTakeDate']);
    });
});


//商品リストを取得する
//Route::get('/product/lists',[ProductController::class, 'lists']);

Route::post('/cart/getProductNum', [CartController::class, 'getProductNum'])->name('getProductNum');
Route::post('/cart/addCart/{restrantId?}/{productId?}', [CartController::class, 'addCart'])->name('addCart');
Route::get('/paySuccess', [OrderController::class, 'paySuccess'])->name('paySuccess');
Route::get('/payFailed', [OrderController::class, 'payFailed'])->name('payFailed');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    //ダシュボード
    Route::get('/dashboard', 'App\Http\Controllers\Controller@dashboard')->name('dashboard');

    //カートのルート
    Route::get('/cart/{productId?}', [CartController::class, 'cart'])->name('cart');

    //カート項目の削除
    Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('deleteItem');

    //商品の削除
    Route::delete('/cart/mulDelete', [CartController::class, 'mulDelete'])->name('mulDelete');

    //カートの商品数量の変更
    Route::post('/cartProductNumber', [CartController::class, 'updateCartProductNum'])->name('cartProductNumber');

    //注文の確認
    Route::post('/cartConfirm', [CartController::class, 'cartConfirm'])->name('cartConfirm');

    Route::post('/checkCartItem', [CartController::class, 'checkCartItem'])->name('checkCartItem');

    //注文を作成する
    Route::post('/createOrder', [OrderController::class, 'createOrder'])->name('createOrder');

    //注文キャンセル
    Route::post('/cancelOrder', [OrderController::class, 'cancelOrder'])->name('cancelOrder');

    //注文基本情報を更新する
    Route::post('/updateOrderInfo', [OrderController::class, 'updateOrderInfo'])->name('updateOrderInfo');

    //お支払い
    Route::post('/payment', [OrderController::class, 'payment'])->name('payment');

    //お支払い確定
    Route::post('/paymentPost', [OrderController::class, 'paymentPost'])->name('paymentPost');
});


Route::view('/about', 'footer.about');
Route::view('/company', 'footer.company');
Route::view('/contact', 'footer.contact');
