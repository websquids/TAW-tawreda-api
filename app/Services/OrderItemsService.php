<?php

namespace App\Services;

use App\Models\OrderProduct;
use App\Models\Product;

class OrderItemsService {
  public function create($order_id, $product_id, $quantity) {
    // get product Data
    $product = Product::find($product_id);
    $orderItem = new OrderProduct();
    $orderItem->order_id = $order_id;
    $orderItem->product_id = $product_id;
    $orderItem->quantity = $quantity;
    $orderItem->price = $product->price;
    $orderItem->save();
    return $orderItem;
  }
}
