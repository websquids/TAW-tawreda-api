<?php

namespace App\Filters;

use App\Models\Product;

class ProductFilter extends BaseFilter {
  function __construct() {
    parent::__construct(Product::class);
  }
}
