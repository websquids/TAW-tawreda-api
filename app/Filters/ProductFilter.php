<?php

namespace App\Filters;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter extends BaseFilter {
  function __construct() {
    parent::__construct(Product::class);
  }
  public function apply(Builder $query, Request $request): Builder {
    parent::apply($query, $request);
    $searchParam = parent::getSearchParameters($request);
    if (isset($searchParam['brand_id'])) {
      $query->where('brand_id', $searchParam['brand_id']);
    }
    // filter between 2 prices
    if (isset($searchParam['min_price']) && isset($searchParam['max_price'])) {
      $query->whereBetween('price', [$searchParam['min_price'], $searchParam['max_price']]);
    }
    return $query;
  }
}
