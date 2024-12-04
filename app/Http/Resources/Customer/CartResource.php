<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array {
    return [
      'id' => $this->id,
      'products' => $this->products,
      'quantity' => $this->quantity,
      'total' => $this->total,
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt,
    ];
  }

  private function calculateTotal($products) {
    $total = 0;
    foreach ($products as $product) {
      $total += calcPriceWithDiscount($product->price, $product->discount);
    }
    return $total;
  }
}
