<?php

namespace App\Services;

use App\Filters\UnitFilter;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitService {
  protected UnitFilter $unitFilter;

  public function __construct() {
    $this->unitFilter = new UnitFilter();
  }

  public function getFilteredUnits(Request $request) {
    // Apply filters to the Unit query
    $query = $this->unitFilter->apply(Unit::query(), $request);

    $perPage = (int) $request->get('perPage', 10);


    if ($perPage === -1) {
      $units = $query->get();
      $data = UnitResource::collection($units);
      return [
        'data' => $data,
      ];
    }

    // Paginate the results with a default `perPage` value
    $paginatedUnits = $query->paginate($request->get('perPage', 10));

    // Transform the results into a resource collection
    $paginatedUnits->data = UnitResource::collection($paginatedUnits);

    return $paginatedUnits;
  }
}
