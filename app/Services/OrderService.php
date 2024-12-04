<?php

namespace App\Service;

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
}
