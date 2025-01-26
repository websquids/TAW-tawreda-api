<?php

namespace App\Services;

use App\Filters\OrderFilter;
use App\Http\Resources\Customer\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderService {
  protected OrderFilter $orderFilter;
  public function __construct() {
    $this->orderFilter = new OrderFilter();
  }
  public function getFilteredOrders(Request $request) {
    $orders = $this->orderFilter->apply(Order::query(), $request);

    $paginationOrders = $orders->paginate($request->get('perPage', 10));

    $paginationOrders->data = OrderResource::collection($paginationOrders);

    return $paginationOrders;
  }

  public function create($orderData) {
    $order = Order::create($orderData);
    return $order;
  }

  public function getOrderById($id) {
    $order = Order::find($id)->get();
    return OrderResource::collection($order);
  }

  public function updateOrder($request, $order) {
    $order->update($request->validated());
    $order->save();
    return $order;
  }

  public function deleteOrder($order) {
    $order->delete();
  }
}
