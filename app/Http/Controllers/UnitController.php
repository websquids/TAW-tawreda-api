<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Initialize the query builder for the Unit model
        $query = Unit::query();

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
        $units = $query->paginate(10);

        // Format the paginated results using the UnitResource
        $units->data = UnitResource::collection($units);

        // Return the filtered and paginated results as a JSON response
        return response()->json($units);
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
}
