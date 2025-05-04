<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Services\UnitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UnitController extends Controller implements HasMiddleware {
  public static function middleware(): array {
    return [
      new Middleware('check.role.permissions:view unit', only: ['index', 'show']),
      new Middleware('check.role.permissions:edit unit', only: ['update']),
      new Middleware('check.role.permissions:delete unit', only: ['bulkDelete']),
      new Middleware('check.role.permissions:create unit', only: ['store']),
      new Middleware('check.role.permissions:edit unit', only: ['update']),
    ];
  }
  protected UnitService $unitService;

  public function __construct(UnitService $uniteService) {
    $this->unitService = $uniteService;
  }
  public function index(Request $request): JsonResponse {
    $units = $this->unitService->getFilteredUnits($request);
    return response()->apiResponse($units);
  }

  public function show($unit): JsonResponse {
    $unit = Unit::find($unit);
    if (!$unit) {
      return response()->json(['message' => 'Unit not found'], 404);
    }
    return response()->apiResponse(new UnitResource($unit));
  }

  public function store(UnitStoreRequest $request): JsonResponse {
    $unit = Unit::create($request->validated());
    return response()->json(new UnitResource($unit));
  }

  public function update(UnitUpdateRequest $request, Unit $unit): JsonResponse {
    $unit->update($request->validated());
    return response()->json(new UnitResource($unit));
  }

  public function destroy(Request $request, Unit $unit): JsonResponse {
    $unit->delete();
    return response()->json();
  }

  public function bulkDelete(Request $request): JsonResponse {
    $ids = $request->get('ids', []);
    $result = Unit::whereIn('id', $ids)->delete();
    return response()->json($result);
  }
}
