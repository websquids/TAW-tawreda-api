<?php

namespace App\Services;

use App\Filters\CategoryFilter;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    protected CategoryFilter $categoryFilter;

    public function __construct()
    {
        $this->categoryFilter = new CategoryFilter();
    }

    public function getFilteredCategories(Request $request)
    {
        // Apply filters to the Category query
        $query = $this->categoryFilter->apply(Category::query(), $request);

        $perPage = (int) $request->get('perPage', 10);

        if ($perPage === -1) {
            $categories = $query->get();
            $data = CategoryResource::collection($categories);
            return [
                'data' => $data,
            ];
        }

        // Otherwise, paginate the results
        $paginatedCategories = $query->paginate($perPage);

        // Transform the results into a resource collection
        $paginatedCategories->data = CategoryResource::collection($paginatedCategories);

        return $paginatedCategories;
    }
}
