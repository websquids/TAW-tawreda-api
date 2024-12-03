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


  public function user() {
    return $this->belongsTo(User::class);
  }

  public function addresses() {
    return $this->morphMany(Address::class, 'addressable');
  }
}
