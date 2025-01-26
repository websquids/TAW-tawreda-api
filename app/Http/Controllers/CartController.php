<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller {
  protected CartService $cartService;
  public function __construct(
    CartService $cartService
  ) {
    $this->cartService = $cartService;
  }
  public function index(Request $request) {
    $cart = $this->cartService->getCartItems($request);
    return response()->apiResponse($cart);
  }
  public function store(Request $request) {
    // $cart = $this->cartService->addToCart($request);
    // return response()->apiResponse($cart);
  }
}
