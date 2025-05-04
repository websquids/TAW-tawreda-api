<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model {
  protected $fillable = ['order_id', 'address_id'];

  public function order() {
    return $this->belongsTo(Order::class);
  }

  public function address() {
    return $this->belongsTo(Address::class, 'address_id', 'id');
  }
}
