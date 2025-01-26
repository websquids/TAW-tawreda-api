<?php

namespace App\Http\Controllers\CustomerApp;

use App\Constants\OrderTypes;
use App\Enums\CartType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\OrderStoreRequest;
use App\Http\Resources\Customer\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
  protected OrderService $orderService;
  public function __construct(
    OrderService $orderService
  ) {
    $this->orderService = $orderService;
  }
  public function index(Request $request) {
    // dd($request->input('order_type'));
    // if ($request->input('order_type') === OrderTypes::INVESTOR) {
        //     $orders = $request->user()->orders()->where('order_type', OrderTypes::INVESTOR);
    // } else {
        //     $orders = $request->user()->orders()->where('order_type', OrderTypes::CUSTOMER);
    // }
    $orders = $this->orderService->getFilteredOrders($request);
    return response()->apiResponse($orders);
    // return response()->apiResponse(OrderResource::collection($orders));
  }

  public function store(OrderStoreRequest $request) {
    DB::beginTransaction();
    try {
      $cartItems = $this->getCartItems($request);
      // if ($cartItems->isEmpty()) {
            //     return response()->apiResponse(null, 'Cart is empty', false, 400);
      // }
      $total = $this->getTotalPrice($cartItems);
      $order = $request->user()->orders()->create([
        'order_type' => $request->order_type,
        'total' => $total,
        'order_status' => Order::ORDER_STATUSES['PENDING'],
      ]);
      if ($request->order_type === OrderTypes::CUSTOMER) {
        $order->orderAddress()->create([
          'address_id' => $request->address_id,
          'order_id' => $order->id,
        ]);
      }
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
