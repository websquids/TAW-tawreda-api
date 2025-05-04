<?php

namespace App\Http\Controllers\CustomerApp;

use App\Constants\OrderTypes;
use App\Enums\CartType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\GetOrders;
use App\Http\Requests\CustomerApp\OrderStoreRequest;
use App\Models\Order;
use App\Services\AppSettingsService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
  protected OrderService $orderService;
  protected AppSettingsService $appSettingsService;

  public function __construct(
    OrderService $orderService,
    AppSettingsService $appSettingsService
  ) {
    $this->orderService = $orderService;
    $this->appSettingsService = $appSettingsService;
  }

  public function index(GetOrders $request) {
    try {
      $orders = $this->orderService->getFilteredOrdersByCurrentUser($request);
      return response()->apiResponse($orders);
    } catch (\Exception $e) {
      return response()->apiResponse(null, $e->getMessage(), false, 500);
    }
  }

  public function show($id) {
    $order = $this->orderService->getOrderById($id);
    if (!$order) {
      return response()->apiResponse(null, 'Order not found', false, 404);
    }
    return response()->apiResponse($order);
  }

  public function store(OrderStoreRequest $request) {
    DB::beginTransaction();

    try {
      $cartItems = $this->getCartItems($request);

      if ($cartItems->isEmpty()) {
        return response()->apiResponse(null, 'Cart is empty', false, 400);
      }

      $order = $this->createOrder($request, $cartItems);

      if ($request->order_type === OrderTypes::CUSTOMER) {
        $this->attachOrderAddress($order, $request->address_id);
      } elseif ($request->order_type === OrderTypes::INVESTOR) {
        $order->order_status = Order::ORDER_STATUSES['PAID'];
      }

      $this->attachOrderProducts($order, $cartItems, $request->order_type);

      $this->deleteCartItems($cartItems);

      DB::commit();

      return response()->apiResponse($order);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->apiResponse(null, $e->getMessage(), false, 500);
    }
  }

  public function requestResale(Request $request, int $id): JsonResponse {
    $userId = request()->user()->id;

    // Fetch the order
    $order = Order::where('id', $id)
      ->where('user_id', $userId)
      ->where('order_type', Order::ORDER_TYPES['INVESTOR'])
      ->first();

    // Validate order existence
    if (!$order) {
      return response()->apiResponse([], 'Order not found.', false, 404);
    }

    // Update order status to RESALE_PENDING
    $order->order_status = Order::ORDER_STATUSES['RESALE_PENDING'];
    $order->save();

    $order = $this->orderService->getOrderById($order->id);

    return response()->apiResponse($order, 'Request submitted successfully');
  }

  public function getActiveInvestorOrder(Request $request): JsonResponse {
    $order = $this->orderService->getActiveInvestorOrder($request);

    if (empty($order)) {
      return response()->apiResponse(null, 'No active order', false, 404);
    }

    return response()->apiResponse($order);
  }

  protected function createOrder($request, $cartItems) {
    $total = $this->getTotalPrice($cartItems, $request->order_type);
    $this->checkOrderAmount($request->order_type, $total);
    return $request->user()->orders()->create([
      'order_type' => $request->order_type,
      'total' => $total,
      'order_status' => Order::ORDER_STATUSES['PENDING'],
    ]);
  }

  protected function checkOrderAmount(string $orderType, $total) {
    if ($orderType === OrderTypes::INVESTOR) {
      $minAmount = $this->appSettingsService->getSettingByKey('minimum investor order amount');
      $maxAmount = $this->appSettingsService->getSettingByKey('maximum investor order amount');
      if ($total < $minAmount->value) {
        throw new \Exception('Minimum order amount is ' . $minAmount->value);
      }
      if ($total > $maxAmount->value) {
        throw new \Exception('Maximum order amount is ' . $maxAmount->value);
      }
    } else {
      $minAmount = $this->appSettingsService->getSettingByKey('minimum order amount');
      $maxAmount = $this->appSettingsService->getSettingByKey('maximum order amount');
      if ($total < $minAmount->value) {
        throw new \Exception('Minimum order amount is ' . $minAmount->value);
      }
      if ($total > $maxAmount->value) {
        throw new \Exception('Maximum order amount is ' . $maxAmount->value);
      }
    }
  }

  protected function attachOrderAddress($order, $addressId) {
    $order->orderAddress()->create([
      'address_id' => $addressId,
      'order_id' => $order->id,
    ]);
  }

  protected function attachOrderProducts($order, $cartItems, $orderType) {
    foreach ($cartItems as $item) {
      $orderProduct = $order->orderProducts()->create([
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
        'price' => $item['price'],
      ]);
      if ($orderType === OrderTypes::INVESTOR) {
        $orderProductInvestorPrice = calcPriceWithDiscount($item->price, $item->product->storage_discount);
        $orderProduct->orderProductInvestorPrice()->create([
          'investor_price' => $orderProductInvestorPrice,
        ]);
      }
    }
  }

  protected function getCartItems($request) {
    return $request->user()
      ->cart()
      ->where('type', $this->getCartType($request->order_type))
      ->with('cartItems.product')
      ->first()->cartItems;
  }

  protected function getCartType($orderType) {
    return $orderType === OrderTypes::CUSTOMER ? CartType::SHOPPING : CartType::INVESTMENT;
  }

  protected function getTotalPrice($cartItems, $orderType) {
    $total = 0;
    foreach ($cartItems as $item) {
      $discount = $orderType === OrderTypes::CUSTOMER ? $item->product->discount : $item->product->storage_discount;
      $total += $this->getPriceWithDiscount($item->price, $item->quantity, $discount);
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
