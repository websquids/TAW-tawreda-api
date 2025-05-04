<?php

namespace App\Services;

use App\Models\CartItem;

class CartItemsService {
  public static function create($cartItemData) {
    $cart = CartItem::create($cartItemData);
    return $cart;
  }
}
