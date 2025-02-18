<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProductInvestorPrice extends Model {
  protected $fillable = ['order_product_id', 'investor_price'];

  public function orderProduct() {
    return $this->belongsTo(OrderProduct::class);
  }
}
