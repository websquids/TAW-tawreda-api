<?php

namespace App\Helpers;

use App\Filters\BaseFilter;
use App\Models\Category;

class CategoryFilter extends BaseFilter {
  public function __construct() {
    parent::__construct(Category::class);
  }
}
