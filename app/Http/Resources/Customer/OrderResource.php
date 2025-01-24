<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array<string, mixed>
   */
  public function toArray($request): array {
    $orderItems = $this->orderProducts->map(function ($orderProduct) {
      $product = $orderProduct->product;
      $discountedPrice = $orderProduct->price * (1 - ($product->discount / 100));
      $totalPrice = $discountedPrice * $orderProduct->quantity;
      return [
        'id' => $orderProduct->id,
        'product_name' => $product->name,
        'product_price' => $orderProduct->price,
        'quantity' => $orderProduct->quantity,
        'discount' => $product->discount,
        'total_price' => $totalPrice,
      ];
    });
    return [
      'id' => $this->id,
      'address' => [
        'id' => $this->address_id,
        'street' => $this->address->street,
        'city' => $this->address->city,
        'state' => $this->address->state,
        'country' => $this->address->country,
        'postal_code' => $this->address->postal_code,
        'building_number' => $this->address->building_number,
        'mobile_number' => $this->address->mobile_number,
        'latitude' => $this->address->latitude,
        'longitude' => $this->address->longitude,
      ],
      'order_type' => $this->order_type,
      'total' => $this->total,
      'status' => $this->ORDER_STATUSES[$this->status] ?? 'Unknown',
      'order_products' => $orderItems,
      'created_at' => $this->created_at->format('d-m-Y H:i:s'),
      'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
    ];
  }
}
