<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\OrderStoreRequest;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller implements HasMiddleware {
  private orderService $orderService;
  private addressService $addressService;

  public function __construct(OrderService $orderService, AddressService $addressService) {
    $this->orderService = $orderService;
    $this->addressService = $addressService;
  }
  public static function middleware(): array {
    return [
      new Middleware('check.role.permissions:view order', only: ['index', 'show']),
      new Middleware('check.role.permissions:edit order', only: ['update']),
      new Middleware('check.role.permissions:delete order', only: ['bulkDelete']),
      new Middleware('check.role.permissions:create order', only: ['store']),
      new Middleware('check.role.permissions:edit order', only: ['update']),
    ];
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request) {
    $orders = $this->orderService->getFilteredOrders($request);
    return response()->apiResponse($orders);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create() {
        //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(OrderStoreRequest $request) {
    // 'order_type' => 'required|in:customer,investor',
        //     'order_items' => 'required|array|min:1',
        //     'order_items.*.product_id' => 'required|exists:products,id',
        //     'order_items.*.quantity' => 'required|integer|min:1',
        //     'order_address_id' => 'required_if:order_address,null|exists:addresses,id',
        //     'order_address' => 'required_if:order_address_id,null',
        //     'order_address.street' => 'required|string|max:255',
        //     'order_address.city' => 'required|string|max:255',
        //     'order_address.state' => 'required|string|max:255',
        //     'order_address.country' => 'required|string|max:255',
        //     'order_address.postal_code' => 'required|string|max:255',
        //     'order_address.building_number' => 'required|string|max:255',
        //     'order_address.mobile_number' => 'required|string|max:255',
        //     'order_address.address_type' => 'required|enum:user,order',


    // begin transaction db
    DB::beginTransaction();
    try {
      if ($request->has('order_address')) {
        $addressData = $request->input('order_address');
        $addressData['address_type'] = 'order';
        $address = $this->addressService->create($addressData);
        $request->merge(['address_id' => $address->id]);
      }
      $orderData = [
        'order_type' => 'customer',
        'address_id' => $request->input('address_id'),
        'user_id' => Auth::user()->id,
        'status' => Order::ORDER_STATUSES['PENDING'],
      ];
      $order = $this->orderService->create($orderData);
      foreach ($request->input('order_items') as $item) {
        $this->orderService->createOrderItem($order->id, $item['product_id'], $item['quantity']);
      }
      DB::commit();
      return response()->apiResponse($order, 'Order created successfully', 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->apiResponse([], $e->getMessage(), 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id) {
        //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id) {
        //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id) {
        //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {
        //
  }
}
