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
    'PENDING' => 0,
    'PROCESSING' => 1,
    'COMPLETED' => 2,
    'CANCELED' => 3,
    'FAILED' => 5,
    'REFUNDED' => 4,
    'REFUND_PENDING' => 6,
    'REFUND_FAILED' => 7,
    'REFUND_COMPLETED' => 8,
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
      'searchable' => false,
      'sortable' => true,
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
    return $this->hasMany(OrderProduct::class);
  }


  public static function getFields(): array {
    $fields = self::$fields;
    return $fields;
  }
}
