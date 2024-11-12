<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Initialize the query builder
        $query = Category::query();

        // Get filter parameters from the request
        $name_en = $request->name_en;
        $name_ar = $request->name_ar;
        $description_en = $request->description_en;
        $description_ar = $request->description_ar;

        // Apply filters if provided in the request
        if ($name_en) {
            $query->where('name_en', 'like', '%' . $name_en . '%');
        }

        if ($name_ar) {
            $query->where('name_ar', 'like', '%' . $name_ar . '%');
        }

        if ($description_en) {
            $query->where('description_en', 'like', '%' . $description_en . '%');
        }

        if ($description_ar) {
            $query->where('description_ar', 'like', '%' . $description_ar . '%');
        }

        $categories = $query->paginate(10);

        // Return the filtered and paginated results as a JSON response
        return response()->json(CategoryResource::collection($categories));
        // return response()->json(new CategoryResource($categories));
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
        return response()->json(new CategoryResource($category));
    }

    public function destroy(Request $request, Category $category): JsonResponse
    {
        $category->delete();
        return response()->json();
    }
}
