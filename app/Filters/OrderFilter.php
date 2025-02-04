<?php

namespace App\Filters;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrderFilter extends BaseFilter {
  public function __construct() {
    parent::__construct(Order::class);
  }

  public function apply(Builder $query, Request $request): Builder {
    if ($request->has('order_type')) {
      $query->where('order_type', $request->get('order_type'));
    } else {
      $query->where('order_type', Order::ORDER_TYPES['CUSTOMER']);
    }
    parent::apply($query, $request);
    return $query;
  }
}
