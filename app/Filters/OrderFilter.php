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
    parent::apply($query, $request);
    return $query;
  }

  public function filterByCurrentUser(Builder $query, Request $request): Builder {
    $query->where('user_id', auth()->id());
    parent::apply($query, $request);
    return $query;
  }
}
