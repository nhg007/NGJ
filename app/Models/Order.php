<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'restrants_orders';

    public function products(){
        return $this->belongsToMany('App\Models\Product',"restrants_orders_product")->withPivot('product_number','product_price');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function takeouts(){
        return $this->belongsTo('App\Models\RestrantTakeOut','restrant_take_out_id');
    }
}
