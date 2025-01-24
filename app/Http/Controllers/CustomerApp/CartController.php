<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\CartIndexRequest;
use App\Http\Requests\CustomerApp\CartItemRemoveRequest;
use App\Http\Requests\CustomerApp\CartStoreRequest;
use App\Http\Resources\Customer\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Enums\CartType;

class CartController extends Controller {
  public function index(CartIndexRequest $request): JsonResponse {
    $userId = $request->user()->id;
    $type = $request->get('type');
    $cart = Cart::where([
      'user_id' => $userId,
      'type' => $type,
    ])->first();

    if (empty($cart)) {
      $cart = Cart::create(['type' => $type, 'user_id' => $userId,]);
    }

    return response()->apiResponse(new CartResource($cart->load(['cartItems'])));
  }

  public function store(CartStoreRequest $request): JsonResponse {
    $inputs = $request->validated();
    $userId = $request->user()->id;
    DB::beginTransaction();
    try {
      $cart = Cart::updateOrCreate([
        'user_id' => $userId,
        'type' => $inputs['type'],
      ], []);

      $product = Product::findOrFail($inputs['product_id']);
      $cartItem = $cart->cartItems()->firstOrNew(['product_id' => $product->id]);

      // Update quantity or create the item
      $cartItem->quantity = $inputs['quantity'];

      $cartItem->price = $product->price;
      $cartItem->save();
      if ($inputs['type'] == CartType::SHOPPING->value) {
        $wishlist = Cart::where([
          'user_id' => $userId,
          'type' => CartType::WISHLIST->value,
        ])->first();
        if (!empty($wishlist)) {
          $wishlist->cartItems()->where(['product_id' => $product->id])?->delete();
        }
      }
      DB::commit();

      // Return the cart with its items
      return response()->apiResponse(new CartResource($cart->load(['cartItems'])));
    } catch (\Exception $e) {
      DB::rollback();
      return response()->apiResponse(new CartResource($cart->load(['cartItems'])));
    }
  }

  public function removeItem(CartItemRemoveRequest $request): JsonResponse {
    $userId = $request->user()->id;
    $type = $request->get('type');
    $product_id = $request->get('product_id');
    $cart = Cart::where([
      'user_id' => $userId,
      'type' => $type,
    ])->first();

    if (!empty($cart)) {
      $cart->cartItems()->where(['product_id' => $product_id])?->delete();
    }
    return response()->apiResponse(new CartResource($cart->load(['cartItems'])));
  }

  public function destroy(CartItemRemoveRequest $request): JsonResponse {
    $userId = $request->user()->id;
    $type = $request->get('type');
    $cart = Cart::where([
      'user_id' => $userId,
      'type' => $type,
    ])->first();

    if (!empty($cart)) {
      $cart->cartItems()->delete();
    }
    return response()->apiResponse(new CartResource($cart->load(['cartItems'])));
  }
}
