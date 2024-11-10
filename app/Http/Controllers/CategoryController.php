<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function index(Request $request): JsonResponse {
        $categories = Category::paginate(10);
        $categories->data =  CategoryResource::collection($categories);
        return response()->json(new CategoryResource($categories));
    }

    public function show(Request $request, Category $category): JsonResponse {
        return response()->json(new CategoryResource($category));
    }

    public function store(CategoryStoreRequest $request): JsonResponse {
        $category = Category::create($request->validated());
        $category->addMedia($request->file('image'))->toMediaCollection('featured');
        return response()->json(new CategoryResource($category));
    }

    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse {
        $category->update($request->validated());
        if ($request->hasFile('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('featured');
        }
        return response()->json(new CategoryResource($category));
    }

    public function destroy(Request $request, Category $category): JsonResponse {
        $category->delete();
        return response()->json();
    }
}
