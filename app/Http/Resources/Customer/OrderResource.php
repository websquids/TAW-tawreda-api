<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array {
    return [
      'id' => $this->id,
      'customer' => $this->customer->name,
      'products' => $this->orderProducts,
      'quantity' => $this->quantity,
      'price' => $this->calculateTotalPrice($this->orderProducts),
      'status' => $this->ORDER_STATUSES[($this->status)],
      'created_at' => $this->created_at->format('d-m-Y H:i:s'),
      'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
    ];
  }

  private function calculateTotalPrice($orderProducts) {
    $totalPrice = 0;
    foreach ($orderProducts as $orderProduct) {
      // Calculate the total price for each order product with discount
      $totalPrice += $orderProduct->product->price * $orderProduct->quantity * (1 - $orderProduct->discount / 100);
    }
    return $totalPrice;
  }
}
