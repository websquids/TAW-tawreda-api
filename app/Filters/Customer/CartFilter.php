<?php

namespace App\Filters\Customer;

use App\Filters\BaseFilter;
use App\Models\Cart;

class CartFilter extends BaseFilter {
  public function __construct() {
    parent::__construct(Cart::query());
  }
}
