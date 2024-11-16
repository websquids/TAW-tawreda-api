<?php

namespace App\Http\Controllers;

use App\Helpers\CategoryFilter;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request, CategoryFilter $categoryFilter): JsonResponse
    {
        // Initialize the query builder
        $query = Category::query();
        // Get filter parameters from the request
        $filteredQuery = $categoryFilter->apply($query);
        $perPage = $request->get('perPage', 10);
        if ($request->get('all', false)) {
            $categories = $filteredQuery->get();
        } else {
            $perPage = $request->get('perPage', 10);
            $categories = $filteredQuery->paginate($perPage);
        }
        return response()->json(CategoryResource::collection($categories));
    }

    public function show(Request $request, Category $category): JsonResponse
    {
        return response()->json(new CategoryResource($category));
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());
        $category->addMedia($request->file('image'))->toMediaCollection();
        return response()->json(new CategoryResource($category));
    }

    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());
        if ($request->hasFile('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('featured');
        }
        return response()->json(new CategoryResource($category));
    }

    public function destroy(Request $request, Category $category): JsonResponse
    {
        $category->delete();
        return response()->json();
    }
}
