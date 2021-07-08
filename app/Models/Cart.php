<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'restrant_id',
        'product_id',
        'price',
        'number'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
