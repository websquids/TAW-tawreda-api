<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::paginate(10);
        $products->data = ProductResource::collection($products);
        return response()->json($products);
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        return response()->json(new ProductResource($product));
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());
        $product->addMedia($request->file('featured_image'))->toMediaCollection('featured');
        foreach ($request->all()['images'] as $image) {
            $product->addMedia($image)->toMediaCollection('gallery');
        }
        return response()->json(new ProductResource($product));
    }

    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());
        if ($request->hasFile('featured_image')) {
            $product->addMedia($request->file('featured_image'))->toMediaCollection('featured');
        }
        foreach ($request->all()['images'] as $image) {
            $product->addMedia($image)->toMediaCollection('gallery');
        }
        return response()->json(new ProductResource($product));
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $product->delete();
        return response()->json();
    }
}
