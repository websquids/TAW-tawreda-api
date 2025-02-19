<?php

namespace App\Services;

use App\Constants\OrderTypes;
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
    $orders = $this->orderFilter->filterByCurrentUser(Order::query(), $request);

    $paginationOrders = $orders->paginate($request->get('perPage', 10));

    $paginationOrders->data = OrderResource::collection($paginationOrders);

    return $paginationOrders;
  }

  public function getFilteredOrdersByCurrentUser(Request $request) {
    // dd();
    if ($request->search['order_type'] === OrderTypes::INVESTOR) {
      $order = $this->orderFilter->filterByCurrentUser(Order::query(), $request);

      // Get the first order
      $firstOrder = $order->first();

      // If there is no order, return an appropriate response
      if (!$firstOrder) {
        throw new \Exception('please create an order first', 404);
      }

      // Use transformForShow for the first order
      return (new OrderResource($firstOrder))->transformForShow();
    } else {
      $order = $this->orderFilter->filterByCurrentUser(Order::query(), $request);
      $paginationOrders = $order->paginate($request->get('perPage', 10));
      $paginationOrders->data = OrderResource::collection($paginationOrders);
      return $paginationOrders;
    }
  }

  public function create($orderData) {
    $order = Order::create($orderData);
    return $order;
  }

  public function getOrderById($id) {
    $order = Order::find($id);
    if (!$order) {
      return null;
    }
    return OrderResource::make($order);
  }

  public function updateOrder($request, $order) {
    $order->update($request->validated());
    $order->save();
    return $order;
  }

  public function deleteOrder($order) {
    $order->delete();
  }

  public function getAllOrderStatus() {
    return Order::ORDER_STATUSES;
  }

  public function updateOrderStatus($order, $status) {
    $order->update(['order_status' => $status]);
    $order->save();
    return $order;
  }
}
