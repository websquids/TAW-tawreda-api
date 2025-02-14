<?php

namespace App\Filters;

use App\Models\Slider;

class SliderFilter extends BaseFilter {
  public function __construct() {
    parent::__construct(Slider::class);
  }
}
