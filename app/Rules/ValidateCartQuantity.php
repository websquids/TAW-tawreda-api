<?php

namespace App\Rules;

use App\Models\Cart;
use App\Models\Product;
use App\Enums\CartType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateCartQuantity implements ValidationRule {
  protected $type;
  protected $userId;

  public function __construct(string $type, int $userId) {
    $this->type = $type;
    $this->userId = $userId;
  }

  /**
   * Run the validation rule.
   *
   * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void {
    $cartType = CartType::tryFrom($this->type);
    $product = Product::find(request('product_id'));
    $cart = Cart::where('user_id', $this->userId)
        ->where('type', $this->type)
        ->first();

    if (!$product) {
      $fail('Invalid product.');
    }

    $existingQuantity = $cart?->cartItems()->where('product_id', request('product_id'))->sum('quantity') ?? 0;
    $totalQuantity = $value;

    // Check stock availability
    if ($totalQuantity > $product->current_stock_quantity) {
      $fail("The total quantity exceeds available stock.");
    }

    // Check min and max based on cart type
    if ($cartType === CartType::SHOPPING) {
      if ($totalQuantity < $product->min_order_quantity) {
        $fail("The total quantity is below the minimum order quantity.");
      }
      if ($totalQuantity > $product->max_order_quantity) {
        $fail("The total quantity exceeds the maximum order quantity.");
      }
    } elseif ($cartType === CartType::INVESTMENT) {
      if ($totalQuantity < $product->min_storage_quantity) {
        $fail("The total quantity is below the minimum storage quantity.");
      }
      if ($totalQuantity > $product->max_storage_quantity) {
        $fail("The total quantity exceeds the maximum storage quantity.");
      }
    }
  }
}
