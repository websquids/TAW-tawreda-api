<?php

namespace App\Services;

use App\Filters\ProductFilter;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductService {
  protected ProductFilter $productFilter;

  public function __construct() {
    $this->productFilter = new ProductFilter();
  }

  public function getFilteredProducts(Request $request) {
    // Apply filters to the Product query
    $query = $this->productFilter->apply(Product::query(), $request);

    $query->with('category', 'brand');

    // Paginate the results with a default `perPage` value
    $paginatedProducts = $query->paginate($request->get('perPage', 10));

    // Transform the results into a resource collection
    $paginatedProducts->data = ProductResource::collection($paginatedProducts);

    return $paginatedProducts;
  }
}
