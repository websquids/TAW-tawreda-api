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

        // Apply filters based on request parameters if they exist
        if ($request->has('name_en')) {
            $query->where('name_en', 'like', '%' . $request->name_en . '%');
        }

        if ($request->has('name_ar')) {
            $query->where('name_ar', 'like', '%' . $request->name_ar . '%');
        }

        if ($request->has('description_en')) {
            $query->where('description_en', 'like', '%' . $request->description_en . '%');
        }

        if ($request->has('description_ar')) {
            $query->where('description_ar', 'like', '%' . $request->description_ar . '%');
        }

        // Paginate the results after applying the filters
        $brands = $query->paginate(10);

        // Format the paginated results using the BrandResource
        $brands->data = BrandResource::collection($brands);

        // Return the filtered and paginated results as a JSON response
        return response()->json($brands);
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
