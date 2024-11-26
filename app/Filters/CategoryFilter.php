<?php

namespace App\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryFilter extends BaseFilter
{
    function __construct()
    {
        parent::__construct(Category::class);
    }

    public function apply(Builder $query, Request $request): Builder
    {
        parent::apply($query, $request);
        $query->when($request->get('is_parent'), function (Builder $query) {
            $query->whereNull('parent_id');
        });

        $query->when($request->get('parent_id'), function (Builder $query) use ($request) {
            $query->where('parent_id', $request->get('parent_id'));
        });
        return $query;
    }
}
