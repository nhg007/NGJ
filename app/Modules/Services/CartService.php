<?php


namespace App\Modules\Services;


use App\Models\Product;
use Illuminate\Http\Request;

interface CartService
{
    public function addCart($restrantId,$product);

    public function getUserCart();

    public function getItems($cartIds);

    public function deleteItem($id);

    public function updateCartProductNumber($cartId,$number);

    public function mulDelete(Request $request);
}
