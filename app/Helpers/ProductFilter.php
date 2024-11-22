<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter {
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Apply filters to the category query based on the request.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function apply(Builder $query): Builder {
        $search = json_decode($this->request->search, true); // Decode once at the beginning
        $query = $query
            ->when($search && isset($search['title']), function ($query) use ($search) {
                $query->whereTranslationLike('title', '%' . $search['title'] . '%');
            })
            ->when($search && isset($search['description']), function ($query) use ($search) {
                $query->whereTranslationLike('description', '%' . $search['description'] . '%');
            })->when($search && isset($search['price']), function ($query) use ($search) {
                $query->where('price', $search['price']);
            });
        $sortBy = $this->request->get('sortBy', 'created_at');
        $sort = $this->request->get('sort', 'asc');
        if ($sortBy === 'title') {
            $query->orderByTranslation('title', $sort);
        } else if ($sortBy === 'description') {
            $query->orderByTranslation('description', $sort);
        } else {
            $query->orderBy($sortBy, $sort);
        }
        return $query;
    }
}
