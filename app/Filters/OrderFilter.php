<?php

namespace App\Filters;

use App\Models\Order;

class OrderFilter extends BaseFilter {
  function __construct() {
    parent::__construct(Order::class);
  }
}
