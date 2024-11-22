<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UnitFilter {
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
            ->when($search && isset($search['name']), function ($query) use ($search) {
                $query->whereTranslationLike('name', '%' . $search['name'] . '%');
            });
        $sortBy = $this->request->get('sortBy', 'created_at');
        $sort = $this->request->get('sort', 'asc');

        if ($sortBy === 'name') {
            $query->orderByTranslation('name', $sort);
        } else {
            $query->orderBy($sortBy, $sort);
        }

        return $query;
    }
}
