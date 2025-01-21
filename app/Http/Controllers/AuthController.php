<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
  public function login(Request $request) {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string',
    ]);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }
    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
      return response()->json(['message' => 'Invalid credentials.'], 401);
    }
    $roles = $user->getRoleNames();
    $permissions = $user->getAllPermissions()->pluck('name');
    $token = $user->createToken('UserApp')->accessToken;
    return response()->json(['token' => $token, 'user' => $user, 'roles' => $roles, 'permissions' => $permissions], 200);
  }

  public function customerLogin(LoginRequest $request) {
    $validator = Validator::make($request->all(), [
      'phone' => 'required',
      'password' => 'required|string',
      'fcm_token' => 'required|string',
      'device_name' => 'required|string',
    ]);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }
    $user = User::where('phone', $request->phone)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
      return response()->json(['message' => 'Invalid credentials.'], 401);
    }
    $roles = $user->getRoleNames();
    $permissions = $user->getAllPermissions()->pluck('name');
    $token = $user->createToken('UserApp')->accessToken;
    $user->fcmTokens()->updateOrCreate(
      ['fcm_token' => $request->fcm_token],
      [
        'fcm_token' => $request->fcm_token,
        'device_name' => $request->device_name,
      ],
    );
    return response()->json(['token' => $token, 'user' => $user, 'roles' => $roles, 'permissions' => $permissions], 200);
  }

  public function register(RegisterRequest $request) {
    $user = User::create($request->validated());
    $user->assignRole('customer');
    $token = $user->createToken('UserApp')->accessToken;
    $roles = $user->getRoleNames();
    $permissions = $user->getAllPermissions()->pluck('name');
    $user->fcm_tokens()->create([
      'token' => $request->fcm_token,
      'device_name' => $request->device_name,
    ]);
    return response()->apiResponse(
      [
        'token' => $token,
        'user' => $user,
        'roles' => $roles,
        'permissions' => $permissions,
      ],
    );
  }

  public function customerLogout(Request $request) {
    // Validate fcm_token
    $validator = Validator::make($request->all(), [
      'fcm_token' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->apiResponse(['errors' => $validator->errors()], 422);
    }

    // Get and delete the FCM token
    $user = $request->user();
    $fcmToken = $user->fcmTokens()->where('fcm_token', $request->fcm_token)->first();

    if ($fcmToken) {
      $fcmToken->delete();
    }

    // Revoke the user's access token
    $user->token()->revoke();

    return response()->apiResponse(['message' => 'Successfully logged out.'], 200);
  }
}
