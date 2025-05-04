<?php

namespace App\Helpers;

use App\Filters\BaseFilter;
use App\Models\Brand;

class BrandFilter extends BaseFilter {
  public function __construct() {
    parent::__construct(Brand::class);
  }
}
