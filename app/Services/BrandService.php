<?php

namespace App\Services;

use App\Filters\BrandFilter;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandService {
  protected BrandFilter $brandFilter;

  public function __construct() {
    $this->brandFilter = new BrandFilter();
  }

  public function getFilteredBrands(Request $request) {
    // Apply filters to the Brand query
    $query = $this->brandFilter->apply(Brand::query(), $request);

    $perPage = (int) $request->get('perPage', 10);

    if ($perPage == -1) {
      $brands = $query->get();
      $data = BrandResource::collection($brands);
      return [
        'data' => $data,
      ];
    }

    // Paginate the results with a default `perPage` value
    $paginatedBrands = $query->paginate($request->get('perPage', 10));

    // Transform the results into a resource collection
    $paginatedBrands->data = BrandResource::collection($paginatedBrands);

    return $paginatedBrands;
  }
}
