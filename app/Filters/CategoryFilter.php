<?php

namespace App\Filters;

use App\Models\Category;

class CategoryFilter extends BaseFilter {
  function __construct() {
    parent::__construct(Category::class);
  }
}
