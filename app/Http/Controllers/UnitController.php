<?php

namespace App\Http\Controllers;

use App\Helpers\UnitFilter;
use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Services\UnitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected UnitService $unitService;

    public function __construct(UnitService $uniteService)
    {
        $this->unitService = $uniteService;
    }
    public function index(Request $request): JsonResponse
    {
        $units = $this->unitService->getFilteredUnits($request);
        return response()->apiResponse($units);
    }

    public function show($unit): JsonResponse
    {
        $unit = Unit::find($unit);
        if (!$unit) {
            return response()->json(['message' => 'Unit not found'], 404);
        }
        return response()->apiResponse(new UnitResource($unit));
    }

    public function store(UnitStoreRequest $request): JsonResponse
    {
        $unit = Unit::create($request->validated());
        return response()->json(new UnitResource($unit));
    }

    public function update(UnitUpdateRequest $request, Unit $unit): JsonResponse
    {
        $unit->update($request->validated());
        return response()->json(new UnitResource($unit));
    }

    public function destroy(Request $request, Unit $unit): JsonResponse
    {
        $unit->delete();
        return response()->json();
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->get('ids', []);
        $result = Unit::whereIn('id', $ids)->delete();
        return response()->json($result);
    }
}
