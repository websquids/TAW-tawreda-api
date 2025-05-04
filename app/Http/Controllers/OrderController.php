<?php

namespace App\Http\Controllers;

use App\Http\Resources\Customer\OrderResource;
use App\Models\Order;
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

  public function show($id) {
    $order = $this->orderService->getOrderById($id);
    if (!$order) {
      return response()->apiResponse(null, 'Order not found', false, 404);
    }
    return response()->apiResponse($order);
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

  public function getAllOrderStatus(): JsonResponse {
    $orderStatuses = $this->orderService->getAllOrderStatus();
    return response()->apiResponse($orderStatuses);
  }

  public function bulkUpdateOrderStatus(Request $request): JsonResponse {
    $request->validate([
      'order_ids' => [
        'required',
        'array',
        'min:1',
      ],
      'order_ids.*' => [
        'exists:orders,id',
      ],
      'order_status_id' => [
        'required',
        'in:' . implode(',', array_values($this->orderService->getAllOrderStatus())),
      ],
    ]);

    $orderIds = $request->input('order_ids');
    $status = $request->input('order_status_id');
    DB::beginTransaction();

    try {
      foreach ($orderIds as $orderId) {
        $order = Order::find($orderId);
        $this->orderService->updateOrderStatus($order, $status);
      }
      DB::commit();
      $updatedOrders = Order::whereIn('id', $orderIds)->get();
      $ordersResource = OrderResource::collection($updatedOrders);

      return response()->apiResponse($ordersResource);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'message' => 'Failed to update order statuses.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
