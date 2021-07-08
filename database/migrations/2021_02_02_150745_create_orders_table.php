<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //注文番号
            $table->string('order_sn',20);
            //ユーザーID
            $table->bigInteger('user_id');
            //注文状態
            $table->tinyInteger('status');
            //支払い状態
            $table->tinyInteger('pay_status');
            //商品の数量
            $table->integer('goods_number');
            //注文金額
            $table->integer('order_amount');
            //支付接口返回的取引ID
            $table->string("access_id")->nullable();
            //支付接口返回的取引パスワード
            $table->string("access_pass")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
