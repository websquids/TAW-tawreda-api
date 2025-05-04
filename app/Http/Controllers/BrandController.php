<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller implements HasMiddleware {
  protected BrandService $brandService;
  public static function middleware(): array {
    return [
      new Middleware('check.role.permissions:view brand', only: ['index', 'show']),
      new Middleware('check.role.permissions:edit brand', only: ['update']),
      new Middleware('check.role.permissions:delete brand', only: ['bulkDelete']),
      new Middleware('check.role.permissions:create brand', only: ['store']),
      new Middleware('check.role.permissions:edit brand', only: ['update']),
    ];
  }

  public function __construct(BrandService $brandService) {
    $this->brandService = $brandService;
  }

  public function index(Request $request): JsonResponse {
    $brands = $this->brandService->getFilteredBrands($request);
    return response()->apiResponse($brands);
  }

  public function show($brand) {
    $brand = Brand::find($brand);
    if (!$brand) {
      return response()->json(['message' => 'Brand not found'], 404);
    }

    return response()->apiResponse(new BrandResource($brand));
  }
  public function store(BrandStoreRequest $request) {
    try {
      DB::beginTransaction();
      $brand = Brand::create($request->safe()->except(['image']));
      $brand->addMedia($request->file('image'))->toMediaCollection('brands');
      DB::commit();
      return response()->apiResponse(new BrandResource($brand));
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(['message' => 'Failed to create brand'], 500);
    }
  }

  public function update(BrandUpdateRequest $request, Brand $brand) {
    try {
      DB::beginTransaction();
      $brand->update($request->safe()->except(['image']));
      if ($request->hasFile('image')) {
        $brand->clearMediaCollection('brands');
        $brand->addMedia($request->file('image'))->toMediaCollection('brands');
      }

      DB::commit();
      return response()->apiResponse(new BrandResource($brand));
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(['message' => 'Failed to update brand'], 500);
    }
  }

  public function destroy(Brand $brand) {
    if (!$brand) {
      return response()->json(['message' => 'Brand not found'], 404);
    }
    if ($brand->products()->exists()) {
      return response()->json(['error' => 'Cannot delete brand with associated products.'], 400);
    }
    $brand->clearMediaCollection();
    $brand->delete();
    return response()->json();
  }

  public function bulkDelete(Request $request) {
    $ids = $request->get('ids', []);
    $result = Brand::whereIn('id', $ids)->delete();
    return response()->json($result);
  }
}
