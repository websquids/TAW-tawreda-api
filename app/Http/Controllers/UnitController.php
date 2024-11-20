<?php

namespace App\Http\Controllers;

use App\Helpers\UnitFilter;
use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request, UnitFilter $unitFilter): JsonResponse
    {
        // Initialize the query builder for the Unit model
        $query = Unit::query();

        // Apply filters to the query
        $query = $unitFilter->apply($query);
        $perPage = $request->get('perPage', 10);
        $currentPage = $request->get('current_page', 1);

        if ($request->get('all', false)) {
            $units = $query->get();
        } else {
            $units = $query->paginate($perPage, ['*'], 'page', $currentPage);
            $units->data = UnitResource::collection($units);
        }
        return response()->json([
            'data' => UnitResource::collection($units),
            'meta' => !$request->boolean('all') ?  [
                'current_page' => $units->currentPage(),
                'per_page' => $units->perPage(),
                'total' => $units->total(),
                'last_page' => $units->lastPage(),
            ] : null,
        ]);
    }

    public function show(Request $request, Unit $unit): JsonResponse
    {
        return response()->json(new UnitResource($unit));
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
