<?php

namespace App\Http\Controllers;

use App\Helpers\ProductFilter;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, ProductFilter $productFilter): JsonResponse
    {
        $query = $productFilter->apply(Product::query());
        $query->with('brand', 'category', 'unit');
        $perPage = $request->get('perPage', 10);
        $currentPage = $request->get('current_page', 1);
        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);
        // Return the custom response as JSON
        return response()->json([
            'data' => ProductResource::collection($products),
            'meta' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }
    public function show(Request $request, Product $product): JsonResponse
    {
        $product->load('brand', 'category', 'unit');
        return response()->json(new ProductResource($product));
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());
        foreach ($request->all()['images'] as $image) {
            $product->addMedia($image)->toMediaCollection('gallery');
        }
        return response()->json(new ProductResource($product));
    }

    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
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

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $product->delete();
        return response()->json();
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->get('ids', []);
        $result = Product::whereIn('id', $ids)->delete();
        return response()->json($result);
    }
}
