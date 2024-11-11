<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller {
    public function index(Request $request): JsonResponse {
        $brands = Brand::paginate($request->integer('per_page', 10));
        $brands->data = BrandResource::collection($brands);
        return response()->json($brands);
    }

    public function show(Request $request, Brand $brand) {
        return response()->json(new BrandResource($brand));
    }

    public function store(BrandStoreRequest $request) {
        $brand = Brand::create($request->validated());
        $brand->addMedia($request->file('image'))->toMediaCollection('featured');
        return response()->json(new BrandResource($brand));
    }

    public function update(BrandUpdateRequest $request, Brand $brand) {
        $brand->update($request->validated());
        if ($request->hasFile('image')) {
            $brand->addMedia($request->file('image'))->toMediaCollection('featured');
        }
        return response()->json(new BrandResource($brand));
    }

    public function destroy(Request $request, Brand $brand) {
        $brand->delete();
        return response()->json();
    }
}
