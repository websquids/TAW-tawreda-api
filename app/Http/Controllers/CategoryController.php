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

class CategoryController extends Controller  implements HasMiddleware {
  public static function middleware(): array {
    return [
      new Middleware('check.role.permissions:view address', only: ['index', 'show']),
      new Middleware('check.role.permissions:edit address', only: ['update']),
      new Middleware('check.role.permissions:delete address', only: ['bulkDelete']),
      new Middleware('check.role.permissions:create address', only: ['store']),
      new Middleware('check.role.permissions:edit address', only: ['update']),
    ];
  }
  protected CategoryService $categoryService;

  public function __construct(CategoryService $categoryService) {
    $this->categoryService = $categoryService;
  }

  public function index(Request $request): JsonResponse {
    $categories = $this->categoryService->getFilteredCategories($request);
    return response()->apiResponse($categories);
  }


  public function show(Request $request, Category $category): JsonResponse {
    return response()->apiResponse(new CategoryResource($category));
  }

  public function store(CategoryStoreRequest $request): JsonResponse {
    $category = Category::create($request->validated());
    $category->addMedia($request->file('image'))->toMediaCollection();
    return response()->apiResponse('', '', 201);
  }

  public function update(CategoryUpdateRequest $request, Category $category): JsonResponse {
    $category->update($request->validated());
    if ($request->hasFile('image')) {
      $category->addMedia($request->file('image'))->toMediaCollection('featured');
    }
    $category->save();
    return response()->json(new CategoryResource($category));
  }

  public function destroy(Request $request, Category $category): JsonResponse {
    $category->delete();
    return response()->json();
  }

  public function bulkDelete(Request $request) {
    $ids = $request->get('ids', []);
    $result = Category::whereIn('id', $ids)->delete();
    return response()->json($result);
  }
}
