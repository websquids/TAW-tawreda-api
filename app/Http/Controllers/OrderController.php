<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
  protected OrderService $orderService;
  public function __construct(
    OrderService $orderService
  ) {
    $this->orderService = $orderService;
  }
  public function index(Request $request): JsonResponse {
    $orders = $this->orderService->getFilteredOrders($request);
    return response()->json($orders);
  }

  public function edit(Request $request, $id): JsonResponse {
    DB::beginTransaction();
    try {
      $order = $this->orderService->getOrderById($id);
      if (!$order) {
        return response()->json(['error' => 'Order not found.'], 404);
      }
      $order = $this->orderService->updateOrder($request, $order);
      DB::commit();
      return response()->json($order);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
