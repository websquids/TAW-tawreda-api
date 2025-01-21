<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request, $userId): JsonResponse
    {
        $request->validate([
          'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);

        // Assign the role to the user
        $user->assignRole($request->role);

        return response()->json([
          'status' => 'success',
          'message' => 'Role assigned successfully',
          'user' => $user,
        ]);
    }


    public function getUsers(Request $request): JsonResponse
    {
        $users = $this->userService->getFilteredUsers($request);
        return response()->apiResponse($users, 'Users retrieved successfully');
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(Request $request, $userId): JsonResponse
    {
        $request->validate([
          'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);

        // Remove the role from the user
        $user->removeRole($request->role);

        return response()->json([
          'status' => 'success',
          'message' => 'Role removed successfully',
          'user' => $user,
        ]);
    }
}
