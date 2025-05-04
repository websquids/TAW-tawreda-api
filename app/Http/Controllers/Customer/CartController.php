<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\CartItemsService;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CartController extends Controller implements HasMiddleware {
  protected cartItemsService $cartItemsService;
  protected cartService $cartService;

  public function __construct(CartItemsService $cartItemService, CartService $cartService) {
    $this->cartItemsService = $cartItemService;
    $this->cartService = $cartService;
  }
  public static function middleware(): array {
    return [
      new Middleware('check.role.permissions:view cart', only: ['index', 'show']),
      new Middleware('check.role.permissions:edit cart', only: ['update']),
      new Middleware('check.role.permissions:delete cart', only: ['bulkDelete']),
      new Middleware('check.role.permissions:create cart', only: ['store']),
      new Middleware('check.role.permissions:edit cart', only: ['update']),
    ];
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request) {
    $cartItems = $this->cartService->getCartItems($request);
    return response()->apiResponse($cartItems);
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
  public function store(Request $request) {
        //
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
