<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BrandController extends Controller implements HasMiddleware {
  use AuthorizesRequests;
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
    $brand = Brand::create($request->safe()->except(['image']));
    $brand->addMedia($request->file('image'))->toMediaCollection('featured');
    return response()->apiResponse(new BrandResource($brand));
  }

  public function update(BrandUpdateRequest $request, Brand $brand) {
    $brand->update($request->safe()->except(['image']));
    if ($request->hasFile('image')) {
      $brand->addMedia($request->file('image'))->toMediaCollection('featured');
    }
    return response()->apiResponse(new BrandResource($brand));
  }

  public function destroy(Brand $brand) {
    $brand->delete();
    return response()->json();
  }

  public function bulkDelete(Request $request) {
    $ids = $request->get('ids', []);
    $result = Brand::whereIn('id', $ids)->delete();
    return response()->json($result);
  }
}
