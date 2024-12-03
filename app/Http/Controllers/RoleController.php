<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
// Use the aliased Role model from Spatie
use Spatie\Permission\Models\Role as PermissionRole;

class RoleController extends Controller {
  /**
   * Display a listing of the roles.
   */
  public function index(): JsonResponse {
    $roles = PermissionRole::all();  // Use PermissionRole here

    return response()->json([
      'status' => 'success',
      'data' => $roles,
    ]);
  }

  /**
   * Store a newly created role.
   */
  public function store(Request $request): JsonResponse {
    $request->validate([
      'name' => 'required|string|unique:roles,name',
    ]);

    $role = PermissionRole::create([  // Use PermissionRole here
      'name' => $request->name,
    ]);

    return response()->json([
      'status' => 'success',
      'data' => $role,
    ]);
  }

  /**
   * Update the specified role.
   */
  public function update(Request $request, $id): JsonResponse {
    $role = PermissionRole::findOrFail($id);  // Use PermissionRole here

    $request->validate([
      'name' => 'required|string|unique:roles,name,' . $id,
    ]);

    $role->update([
      'name' => $request->name,
    ]);

    return response()->json([
      'status' => 'success',
      'data' => $role,
    ]);
  }

  /**
   * Remove the specified role.
   */
  public function destroy($id): JsonResponse {
    $role = PermissionRole::findOrFail($id);  // Use PermissionRole here

    $role->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'Role deleted successfully',
    ]);
  }
}
