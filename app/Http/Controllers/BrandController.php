<?php

namespace App\Http\Controllers;

use App\Helpers\BrandFilter;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller {
    public function index(Request $request, BrandFilter $brandFilter): JsonResponse {
        $query = Brand::query();
        $filteredQuery = $brandFilter->apply($query, $request);
        $perPage = $request->get('perPage', 10);
        $currentPage = $request->get('current_page', 1);
        if ($request->get('all', false)) {
            $brands = $filteredQuery->get();
        } else {
            $brands = $filteredQuery->paginate($perPage, ['*'], 'page', $currentPage);
        }
        return response()->json([
            'data' => BrandResource::collection($brands),
            'meta' => !$request->boolean('all') ? [
                'current_page' => $brands->currentPage(),
                'per_page' => $brands->perPage(),
                'total' => $brands->total(),
                'last_page' => $brands->lastPage(),
            ] : null,
        ]);
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
        // dd($request);
        $ids = $request->get('ids', []);
        $result = Brand::whereIn('id', $ids)->delete();
        return response()->json($result);
    }
}
