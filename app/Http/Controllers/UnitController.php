<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller {
    public function index(Request $request): JsonResponse {
        $units = Unit::paginate();
        $units->data = UnitResource::collection($units);
        return response()->json($units);
    }

    public function show(Request $request, Unit $unit): JsonResponse {
        return response()->json(new UnitResource($unit));
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
}
