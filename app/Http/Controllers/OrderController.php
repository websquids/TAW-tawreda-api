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
    return response()->apiResponse($orders);
  }

  public function edit(Request $request, $id): JsonResponse {
    DB::beginTransaction();
    try {
      $order = $this->orderService->getOrderById($id);
      if (!$order) {
        return response()->apiResponse(['error' => 'Order not found.'], 404);
      }
      $order = $this->orderService->updateOrder($request, $order);
      DB::commit();
      return response()->apiResponse($order);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->apiResponse(['error' => $e->getMessage()], 500);
    }
  }

  public function destroy($id): JsonResponse {
    $order = $this->orderService->getOrderById($id);
    if (!$order) {
      return response()->apiResponse(['error' => 'Order not found.'], 404);
    }
    $this->orderService->deleteOrder($order);
    return response()->apiResponse(['message' => 'Order deleted successfully.']);
  }
}
