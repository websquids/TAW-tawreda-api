<?php

namespace App\Filters;

use App\Models\Brand;

class BrandFilter extends BaseFilter {
  function __construct() {
    parent::__construct(Brand::class);
  }
}
