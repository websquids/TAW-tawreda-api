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
    if ($request->routeIs('orders.index', 'customer_app.orders.index')) {
      return $this->transformForIndex();
    }

    return $this->transformForShow();
  }

  /**
   * Transform the resource into an array for the index API.
   *
   * @return array<string, mixed>
   */
  public function transformForIndex(): array {
    return [
      'id' => $this->id,
      'address' => [
        'city' => $this->orderAddress->address->city,
        'street' => $this->orderAddress->address->street,
        'building_number' => $this->orderAddress->address->building_number,
      ],
      'user' => [
        'name' => $this->user->name,
      ],
      'status' => $this->getStatusAttribute($this->order_status),
      'created_at' => $this->created_at,
      'total' => $this->total,
    ];
  }

  /**
   * Transform the resource into an array for the show API.
   *
   * @return array<string, mixed>
   */
  public function transformForShow(): array {
    $orderItems = $this->orderProducts->map(function ($orderProduct) {
      $product = $orderProduct->product;
      $discountedPrice = $orderProduct->price * (1 - ($product->discount / 100));
      $totalPrice = $discountedPrice * $orderProduct->quantity;
      return [
        'id' => $orderProduct->id,
        'product_name' => $product->title,
        'product_price' => $orderProduct->price,
        'quantity' => $orderProduct->quantity,
        'discount' => $product->discount,
        'total_price' => $totalPrice,
        'media' => $product->getMedia('products')->map(function ($media) {
          return [
            'original' => $media->getUrl(),
            'thumb' => $media->getUrl('thumb'),
            'medium' => $media->getUrl('medium'),
            'large' => $media->getUrl('large'),
          ];
        }),
      ];
    });
    $data = [
      'id' => $this->id,
      'order_type' => $this->order_type,
      'total' => $this->total,
      'status' => $this->getStatusAttribute($this->order_status),
      'order_products' => $orderItems,
      'user' => [
        'id' => $this->user->id,
        'name' => $this->user->name,
        'email' => $this->user->email,
        'phone' => $this->user->phone,
      ],
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];

    if ($this->order_type !== 'investor') {
      $data['address'] = [
        'id' => $this->orderAddress->address->id,
        'street' => $this->orderAddress->address->street,
        'city' => $this->orderAddress->address->city,
        'state' => $this->orderAddress->address->state,
        'country' => $this->orderAddress->address->country,
        'postal_code' => $this->orderAddress->address->postal_code,
        'building_number' => $this->orderAddress->address->building_number,
        'mobile_number' => $this->orderAddress->address->mobile_number,
        'latitude' => $this->orderAddress->address->latitude,
        'longitude' => $this->orderAddress->address->longitude,
      ];
    }

    return $data;
  }
}
