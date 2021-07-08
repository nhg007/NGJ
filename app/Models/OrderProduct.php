<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;


    protected $table = 'restrants_orders_product';
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
