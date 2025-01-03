<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
          new Middleware('check.role.permissions:view address', only: ['index', 'show']),
          new Middleware('check.role.permissions:edit address', only: ['update']),
          new Middleware('check.role.permissions:delete address', only: ['bulkDelete']),
          new Middleware('check.role.permissions:create address', only: ['store']),
          new Middleware('check.role.permissions:edit address', only: ['update']),
        ];
    }
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $categories = $this->categoryService->getFilteredCategories($request);
        return response()->apiResponse($categories);
    }


    public function show(Request $request, Category $category): JsonResponse
    {
        return response()->apiResponse(new CategoryResource($category));
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $category = Category::create($request->validated());
            $category->addMedia($request->file('image'))->toMediaCollection();
            DB::commit();
            return response()->apiResponse('Category created successfully.', '', 201);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Category creation failed: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to create category.'], 500);
        }
    }

    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        DB::beginTransaction();
        try {
            $category->update($request->validated());
            if ($request->hasFile('image')) {
                $category->clearMediaCollection();
                $category->addMedia($request->file('image'))->toMediaCollection();
            }
            DB::commit();
            return response()->apiResponse('Category updated successfully.', '', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category update failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update category.'], 500);
        }
    }

    public function destroy(Request $request, Category $category): JsonResponse
    {
        if ($category->products()->exists()) {
            return response()->json(['error' => 'Cannot delete category with associated products.'], 400);
        }
        $category->clearMediaCollection();
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
