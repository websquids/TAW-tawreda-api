<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller {
  protected CategoryService $categoryService;

  public function __construct(CategoryService $categoryService) {
    $this->categoryService = $categoryService;
  }

  public function index(Request $request): JsonResponse {
    $categories = $this->categoryService->getFilteredCategories($request);
    return response()->apiResponse($categories);
  }
}
