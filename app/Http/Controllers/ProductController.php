<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller implements HasMiddleware {
  public static function middleware(): array {
    return [
      new Middleware('check.role.permissions:view product', only: ['index', 'show']),
      new Middleware('check.role.permissions:edit product', only: ['update']),
      new Middleware('check.role.permissions:delete product', only: ['bulkDelete']),
      new Middleware('check.role.permissions:create product', only: ['store']),
      new Middleware('check.role.permissions:edit product', only: ['update']),
    ];
  }
  protected ProductService $productService;
  public function __construct(ProductService $productService) {
    $this->productService = $productService;
  }
  public function index(Request $request): JsonResponse {
    $products = $this->productService->getFilteredProducts($request);
    return response()->apiResponse($products);
  }
  public function show(Request $request, Product $product): JsonResponse {
    $product->load('brand', 'category', 'unit');
    return response()->apiResponse(new ProductResource($product));
  }

  public function store(ProductStoreRequest $request): JsonResponse {
    $product = Product::create($request->validated());
    foreach ($request->all()['images'] as $image) {
      $product->addMedia($image)->toMediaCollection('gallery');
    }
    return response()->json(new ProductResource($product));
  }

  public function update(ProductUpdateRequest $request, Product $product): JsonResponse {
    $product->update($request->validated());
    if ($request->has('images')) {
      // Retrieve all media items from the 'gallery' collection
      $mediaItems = $product->getMedia('gallery');

      // Delete each media item individually
      foreach ($mediaItems as $media) {
        $media->delete();
      }

      // Add the new images to the 'gallery' collection
      foreach ($request->images as $image) {
        $product->addMedia($image)->toMediaCollection('gallery');
      }
    }

    return response()->json(new ProductResource($product));
  }

  public function destroy(Request $request, Product $product): JsonResponse {
    $product->delete();
    return response()->json();
  }

  public function bulkDelete(Request $request): JsonResponse {
    $ids = $request->get('ids', []);
    $result = Product::whereIn('id', $ids)->delete();
    return response()->json($result);
  }
}
