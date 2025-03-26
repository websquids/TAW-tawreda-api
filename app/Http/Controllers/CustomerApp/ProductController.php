<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\GetProducts;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller {
  protected ProductService $productService;

  public function __construct(ProductService $productService) {
    $this->productService = $productService;
  }

  public function index(GetProducts $request): JsonResponse {
    $categories = $this->productService->getFilteredProducts($request);
    return response()->apiResponse($categories);
  }

  public function show(Request $request, int $id): JsonResponse {
    $product = Product::findOrFail($id)->load('brand', 'category', 'unit');
    return response()->apiResponse(new ProductResource($product));
  }

  public function getMinimumandMaximumPrice(): JsonResponse {
    $minMaxPrice = $this->productService->getMinMaxPrice();
    return response()->apiResponse($minMaxPrice);
  }
}
