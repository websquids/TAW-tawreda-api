<?php

namespace App\Http\Resources\Customer;

use App\Constants\OrderTypes;
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
    if ($request->routeIs('orders.index', 'customer_app.orders.index', 'orders.updateOrderStatus')) {
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
    $data = [
      'id' => $this->id,
      'user' => [
        'name' => $this->user->name,
      ],
      'status' => $this->getStatusAttribute($this->order_status),
      'created_at' => $this->created_at,
      'total' => $this->total,
    ];
    if ($this->order_type !== OrderTypes::INVESTOR) {
      $data['address'] = [
        'city' => $this->orderAddress->address->city,
        'street' => $this->orderAddress->address->street,
        'building_number' => $this->orderAddress->address->building_number,
      ];
    } else {
      $data['new_total_price'] = $this->calculateNewTotalPrice($this->orderProducts);
    }
    return $data;
  }

  /**
   * Transform the resource into an array for the show API.
   *
   * @return array<string, mixed>
   */
  public function transformForShow(): array {
    // $orderItems = $this->orderProducts->map(function ($orderProduct) {
        //     $product = $orderProduct->product;
        //     $discountedPrice = $orderProduct->price * (1 - ($product->discount / 100));
        //     $totalPrice = $discountedPrice * $orderProduct->quantity;
        //     $data = [
        //         'id' => $orderProduct->id,
        //         'product_name' => $product->title,
        //         'product_price' => $orderProduct->price,
        //         'quantity' => $orderProduct->quantity,
        //         'discount' => $product->discount,
        //         'media' => $product->getMedia('products')->map(function ($media) {
        //             return [
        //               'original' => $media->getUrl(),
        //               'thumb' => $media->getUrl('thumb'),
        //               'medium' => $media->getUrl('medium'),
        //               'large' => $media->getUrl('large'),
        //             ];
        //         }),
        //       ];
        //     if ($this->order_type === OrderTypes::INVESTOR) {
        //         $data['old_investor_price_price'] = $orderProduct->orderProductInvestorPrice->investor_price;
        //         $data['old_total_investor_price'] = $orderProduct->orderProductInvestorPrice->investor_price * $orderProduct->quantity;
        //         $data['new_investor_price'] = calcPriceWithDiscount($orderProduct->price, $product->storage_discount);
        //         $data['new_total_investor_price'] = $data['new_investor_price'] * $orderProduct->quantity;
        //     } else {
        //         $data['total_price'] = $totalPrice;
        //     }
        //     return $data;
    // });
    $data = [
      'id' => $this->id,
      'order_type' => $this->order_type,
      'total' => $this->total,
      'status' => $this->getStatusAttribute($this->order_status),
      'order_products' => $this->transformOrderProducts(),
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
    } else {
      $data['new_total_price'] = $this->calculateNewTotalPrice($this->orderProducts);
    }

    return $data;
  }

  protected function transformOrderProducts(): array {
    return $this->orderProducts->map(function ($orderProduct) {
      $product = $orderProduct->product;
      $discountedPrice = $orderProduct->price * (1 - ($product->discount / 100));
      $totalPrice = $discountedPrice * $orderProduct->quantity;

      $data = [
        'id' => $orderProduct->id,
        'product_name' => $product->title,
        'product_price' => $orderProduct->price,
        'quantity' => $orderProduct->quantity,

        'media' => $this->transformMedia($product->getMedia('products')),
      ];

      if ($this->order_type === OrderTypes::INVESTOR) {
        $data = array_merge($data, $this->transformInvestorPrices($orderProduct, $product));
      } else {
        $data['total_price'] = $totalPrice;
        $data['discount'] = $product->discount;
      }

      return $data;
    })->toArray();
  }

  protected function transformInvestorPrices($orderProduct, $product): array {
    return [
      'old_investor_price_price' => $orderProduct->orderProductInvestorPrice->investor_price,
      'old_total_investor_price' => $orderProduct->orderProductInvestorPrice->investor_price * $orderProduct->quantity,
      'new_investor_price' => calcPriceWithDiscount($orderProduct->price, $product->storage_discount),
      'new_total_investor_price' => calcPriceWithDiscount($orderProduct->price, $product->storage_discount) * $orderProduct->quantity,
      'discount' => $product->storage_discount,
    ];
  }

  protected function transformMedia($media): array {
    return $media->map(function ($mediaItem) {
      return [
        'original' => $mediaItem->getUrl(),
        'thumb' => $mediaItem->getUrl('thumb'),
        'medium' => $mediaItem->getUrl('medium'),
        'large' => $mediaItem->getUrl('large'),
      ];
    })->toArray();
  }



  protected function calculateNewTotalPrice($orderItems) {
    $newTotalPrice = 0;
    foreach ($orderItems as $item) {
      $product = $item->product;
      $priceWithDiscount = calcPriceWithDiscount($product->price, $product->storage_discount);
      $newTotalPrice += $item->quantity * $priceWithDiscount;
    }
    return $newTotalPrice;
  }
}
