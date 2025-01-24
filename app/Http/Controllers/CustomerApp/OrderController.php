<?php

namespace App\Http\Controllers\CustomerApp;

use App\Enums\CartType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\OrderStoreRequest;
use App\Http\Resources\Customer\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
  public function index(Request $request) {
    $orders = $request->user()->orders()->get();
    return response()->apiResponse(OrderResource::collection($orders));
  }

  public function store(OrderStoreRequest $request) {
    DB::beginTransaction();
    try {
      $cartItems = $this->getCartItems($request);
      $total = $this->getTotalPrice($cartItems);
      $order = $request->user()->orders()->create([
        'address_id' => $request->address_id,
        'order_type' => $request->order_type,
        'total' => $total,
        'order_status' => Order::ORDER_STATUSES['PENDING'],
      ]);
      foreach ($cartItems as $item) {
        $order->orderProducts()->create([
          'product_id' => $item['product_id'],
          'quantity' => $item['quantity'],
          'price' => $item['price'],
        ]);
      }
      $this->deleteCartItems($cartItems);
      DB::commit();
      return response()->apiResponse($order);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->apiResponse(null, $e->getMessage(), false, 500);
    }
  }


  protected function getCartItems($request) {
    return $request->user()
        ->cart()
        ->where('type', CartType::SHOPPING)
        ->with('cartItems.product')
        ->first()->cartItems;
  }

  protected function getTotalPrice($cartItems) {
    $total = 0;
    foreach ($cartItems as $item) {
      $total += $this->getPriceWithDiscount($item->price, $item->quantity, $item->product->discount);
    }
    return $total;
  }

  protected function getPriceWithDiscount($price, $quantity, $discount) {
    if ($discount > 0) {
      $discountedPrice = $price - ($price * $discount / 100);
      return $discountedPrice * $quantity;
    } else {
      return $price * $quantity;
    }
  }

  protected function deleteCartItems($cartItems) {
    foreach ($cartItems as $item) {
      $item->delete();
    }
  }
}
