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
        $query = $categoryFilter->apply(Category::query());
        if ($excludedId = $request->get('except_category_id')) {
            $query->where('id', '!=', $excludedId);
        }
        if ($request->boolean('is_parent')) {
            $query->whereNull('parent_id');
        }
        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->get('parent_id'));
        }
        if ($request->boolean('all')) {
            $categories = $query->get();
        } else {
            $perPage = $request->get('perPage', 10);
            $currentPage = $request->get('current_page', 1);
            $categories = $query->paginate($perPage, ['*'], 'page', $currentPage);
        }

        // Prepare the response
        return response()->json([
            'data' => CategoryResource::collection($categories),
            'meta' => !$request->boolean('all') ? [
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'last_page' => $categories->lastPage(),
            ] : null,
        ]);
    }


    public function show(Request $request, Category $category): JsonResponse
    {
        return response()->json(new CategoryResource($category));
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());
        $category->addMedia($request->file('image'))->toMediaCollection('featured');
        return response()->json(new CategoryResource($category));
    }

    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());
        if ($request->hasFile('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('featured');
        }
        $category->save();
        return response()->json(new CategoryResource($category));
    }

    public function destroy(Request $request, Category $category): JsonResponse
    {
        $category->delete();
        return response()->json();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->get('ids', []);
        $result = Category::whereIn('id', $ids)->delete();
        return response()->json($result);
    }
}
