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
      'user_id' => $this->user_id,
      'total' => $this->total,
      'type' => $this->type,
      'cart_items' => $this->whenLoaded('cartItems', function () {
        return $this->cartItems()->orderBy('created_at')->get()->map(function ($item) {
          $featureImage = $item->product?->getFirstMediaUrl('featured');
          if (empty($featureImage) && !empty($item->product)) {
            $featureImage = $item->product->getFirstMediaUrl('gallery');
          }
          return [
            'id' => $item->id,
            'cart_id' => $item->cart_id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'discount' => $item->product?->discount,
            'product_name' => $item->product?->title,
            'product_image' => $featureImage,
          ];
        });
      }),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
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
