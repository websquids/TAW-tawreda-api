<?php

namespace App\Filters;

use App\Models\Unit;

class UnitFilter extends BaseFilter
{
    function __construct()
    {
        parent::__construct(Unit::class);
    }
}
