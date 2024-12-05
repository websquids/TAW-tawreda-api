<?php

namespace App\Http\Controllers;

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

  public function register(RegisterRequest $request, ) {
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);
    $user->assignRole('customer');
    $token = $user->createToken('UserApp')->accessToken;
    $roles = $user->getRoleNames();
    $permissions = $user->getAllPermissions()->pluck('name');
    return response()->json(['token' => $token, 'user' => $user, 'roles' => $roles, 'permissions' => $permissions], 200);
  }
}
