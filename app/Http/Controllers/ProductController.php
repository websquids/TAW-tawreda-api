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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
    // dd('asddasdasddd');
    $products = $this->productService->getFilteredProducts($request);
    return response()->apiResponse($products);
  }
  public function show(Request $request, Product $product): JsonResponse {
    $product->load('brand', 'category', 'unit');
    return response()->apiResponse(new ProductResource($product));
  }

  public function store(ProductStoreRequest $request): JsonResponse {
    DB::beginTransaction();
    try {
      $product = Product::create($request->validated());
      foreach ($request->all()['images'] as $image) {
        $product->addMedia($image)->toMediaCollection('products');
      }
      DB::commit();
      return response()->json(new ProductResource($product));
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }
  }

  public function update(ProductUpdateRequest $request, Product $product): JsonResponse {
    DB::beginTransaction();
    try {
      $product->update($request->validated());
      if ($request->has('images')) {
        $product->clearMediaCollection('products');
        foreach ($request->images as $image) {
          $product->addMedia($image)->toMediaCollection('products');
        }
      }
      DB::commit();
      return response()->json(new ProductResource($product));
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }
  }

  public function updateQuantity(Request $request, Product $product): JsonResponse {
    DB::beginTransaction();
    try {
      $validatedData = Validator::make($request->all(), [
        'current_stock_quantity' => 'required|numeric',
      ]);
      if ($validatedData->fails()) {
        return response()->json(['errors' => $validatedData->errors()], 422);
      }
      $product->update(['current_stock_quantity' => $request->current_stock_quantity]);
      DB::commit();
      return response()->json(new ProductResource($product));
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }
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
