<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_id',
    'order_type',
    'order_status',
    'total',
    'status',
  ];


  public const ORDER_TYPES = [
    'CUSTOMER' => 'customer',
    'INVESTOR' => 'investor',
  ];

  public const ORDER_STATUSES = [
    'PENDING' => 0,             // Order created but not yet processed
    'PROCESSING' => 1,          // Order is being prepared for fulfillment
    'PAID' => 2,                // Payment successfully received
    'SHIPPED' => 3,             // Order has been dispatched for delivery
    'DELIVERED' => 4,           // Order successfully delivered to customer

    'RETURN_PENDING' => 5,      // Return request received from customer
    'RETURN_PROCESSING' => 6,   // Return is being processed
    'RETURN_SHIPPED' => 7,      // Return package shipped back to warehouse
    'RETURN_DELIVERED' => 8,    // Return package received at warehouse

    'RESALE_PENDING' => 9,      // Returned item evaluated for resale
    'RESALE_PROCESSING' => 10,  // Item being prepared for resale
    'RESALE_PAID' => 11,        // Item successfully resold

    'REFUNDED' => 12,           // Full refund issued to customer
    'CANCELLED' => 13,          // Order cancelled before processing
    'FAILED' => 14,             // Order failed due to payment/system issues
  ];

  public function getStatusAttribute($value) {
    // dd($value);
    return array_flip(self::ORDER_STATUSES)[$value];
  }

  protected static array $fields = [
    'order_type' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'order_status' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'total' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'status' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'created_at' => [
      'searchable' => true,
      'sortable' => true,
      'type' => 'date_range',
    ],
    'updated_at' => [
      'searchable' => false,
      'sortable' => true,
    ],
  ];


  public function user() {
    return $this->belongsTo(User::class);
  }

  public function orderAddress() {
    return $this->hasOne(OrderAddress::class, 'order_id', 'id');
  }

  public function orderProducts() {
    return $this->hasMany(OrderProduct::class, 'order_id', 'id');
  }


  public static function getFields(): array {
    $fields = self::$fields;
    return $fields;
  }
}
