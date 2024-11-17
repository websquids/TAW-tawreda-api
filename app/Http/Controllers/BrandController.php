<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Initialize the query builder for the Brand model
        $query = Brand::query();
        $perPage = $request->get('perPage', 10);
        $currentPage = $request->get('current_page', 1);

        if ($request->get('all', false)) {
            $brands = $query->get();
        } else {
            $brands = $query->paginate($perPage, ['*'], 'page', $currentPage);
        }
        // Return the filtered and paginated results as a JSON response
        return response()->json([
            'data' => BrandResource::collection($brands),
            'meta' => [
                'current_page' => $brands->currentPage(),
                'per_page' => $brands->perPage(),
                'total' => $brands->total(),
                'last_page' => $brands->lastPage(),
            ]
        ]);
    }

    public function show(Request $request, Brand $brand)
    {
        return response()->json(new BrandResource($brand));
    }

    public function store(BrandStoreRequest $request)
    {
        $brand = Brand::create($request->validated());
        $brand->addMedia($request->file('image'))->toMediaCollection('featured');
        return response()->json(new BrandResource($brand));
    }

    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        $brand->update($request->validated());
        if ($request->hasFile('image')) {
            $brand->addMedia($request->file('image'))->toMediaCollection('featured');
        }
        return response()->json(new BrandResource($brand));
    }

    public function destroy(Request $request, Brand $brand)
    {
        $brand->delete();
        return response()->json();
    }
}
