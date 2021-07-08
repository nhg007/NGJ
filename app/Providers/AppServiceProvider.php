<?php

namespace App\Providers;

use App\Modules\Services\CartService;
use App\Modules\Services\impl\CartServiceImpl;
use App\Modules\Services\impl\OrderServiceImpl;
use App\Modules\Services\impl\ProductServiceImpl;
use App\Modules\Services\impl\RestrantServiceImpl;
use App\Modules\Services\OrderService;
use App\Modules\Services\ProductService;
use App\Modules\Services\RestrantServcie;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        //实例化お問い合わせ
        $this->app->singleton(ProductService::class,ProductServiceImpl::class);
        //购物车
        $this->app->singleton(CartService::class,CartServiceImpl::class);
        //注文
        $this->app->singleton(OrderService::class,OrderServiceImpl::class);

        $this->app->singleton(RestrantServcie::class,RestrantServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
