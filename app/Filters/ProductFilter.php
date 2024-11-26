<?php

namespace App\Filters;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter extends BaseFilter
{
    function __construct()
    {
        parent::__construct(Product::class);
    }
}
