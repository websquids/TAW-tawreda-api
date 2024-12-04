<?php

namespace App\Services;

use App\Filters\Customer\CartFilter;
use App\Http\Resources\Customer\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartService {
  protected cartFilter $cartFilter;
  public function __construct(CartFilter $cartFilter) {
    $this->cartFilter = $cartFilter;
  }
  public function getCartItems(Request $request) {
    $query = $this->cartFilter->apply(Cart::query(), $request);

    $paginationCart = $query->with('cartItems')->paginate($request->input('perPage', 10));

    $paginationCart->data = CartResource::collection($paginationCart);
    return $paginationCart;
  }

  public function create($user_id) {
    $cart = Cart::create(['user_id' => $user_id]);
    return $cart;
  }
}
