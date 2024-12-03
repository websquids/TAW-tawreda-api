<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AuthenticatedSessionController extends Controller {
  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): JsonResponse {
    // Validate and get the request credentials
    $credentials = $request->validated();

    // Find the user by email
    $user = User::where('email', $credentials['email'])->first();

    // Check if user exists and password is correct
    if (!$user || !Hash::check($credentials['password'], $user->password)) {
      return $this->sendError('The provided credentials are incorrect.', 403);
    }

    // Revoke all the user's current tokens
    $user->tokens()->delete();

    // Create a new token for the user
    $token = $user->createToken('web_login')->plainTextToken;

    // Return a success response with the user and token
    return $this->sendResponse([
      'user' => new UserResource($user),
      'token' => [
        'access_token' => $token,
        'token_type' => 'bearer',
      ],
    ], __('authenticated'));
  }

  /**
   * Return the authenticated user.
   */
  public function show(): JsonResponse {
    // Retrieve the authenticated user
    $user = new UserResource(request()->user());

    // Return the user data in the response
    return $this->sendResponse($user, __('retrieved-successfully'));
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request): JsonResponse {
    try {
      // Get the currently authenticated user
      $user = $request->user();

      // Revoke all of the user's tokens
      $user->tokens()->delete();

      // Return a success response
      return $this->sendResponse('', __('logged-out-successfully'));
    } catch (\Throwable $th) {
      // Handle errors gracefully and return a server error response
      return $this->sendError(__('server-error'), 500);
    }
  }

  /**
   * Helper method to return success responses.
   */
  protected function sendResponse($data, $message): JsonResponse {
    return response()->json([
      'status' => 'success',
      'message' => $message,
      'data' => $data,
    ], 200);
  }

  /**
   * Helper method to return error responses.
   */
  protected function sendError($message, $code = 400): JsonResponse {
    return response()->json([
      'status' => 'error',
      'message' => $message,
    ], $code);
  }
}
