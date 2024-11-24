<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller {
  protected BrandService $brandService;

  public function __construct(BrandService $brandService) {
    $this->brandService = $brandService;
  }

  public function index(Request $request): JsonResponse {
    $brands = $this->brandService->getFilteredBrands($request);
    return response()->apiResponse($brands);
  }

  public function show(Request $request, Brand $brand) {
    return response()->json(new BrandResource($brand));
  }

  public function store(BrandStoreRequest $request) {
    $brand = Brand::create($request->safe()->except(['image']));
    $brand->addMedia($request->file('image'))->toMediaCollection('featured');
    return response()->json(new BrandResource($brand));
  }

  public function update(BrandUpdateRequest $request, Brand $brand) {
    $brand->update($request->safe()->except(['image']));
    if ($request->hasFile('image')) {
      $brand->addMedia($request->file('image'))->toMediaCollection('featured');
    }
    return response()->json(new BrandResource($brand));
  }

  public function destroy(Request $request, Brand $brand) {
    $brand->delete();
    return response()->json();
  }

  public function bulkDelete(Request $request) {
    $ids = $request->get('ids', []);
    $result = Brand::whereIn('id', $ids)->delete();
    return response()->json($result);
  }
}
