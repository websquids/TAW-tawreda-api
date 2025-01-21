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
          $mediaItems = $item->getMedia('products');
          $mediaWithConversions = $mediaItems->map(function ($media) {
            return [
              'original' => $media->getUrl(),
              'thumb' => $media->getUrl('thumb'),
              'medium' => $media->getUrl('medium'),
              'large' => $media->getUrl('large'),
            ];
          });
          return [
            'id' => $item->id,
            'cart_id' => $item->cart_id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'discount' => $item->product?->discount,
            'product_name' => $item->product?->title,
            'media' => $mediaWithConversions ,
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
