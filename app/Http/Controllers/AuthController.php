<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
        $token = $user->createToken('UserApp')->accessToken;
        return response()->json(['token' => $token, 'user' => $user, 'roles' => $roles, 'permissions' => $permissions], 200);
    }

    public function register(RegisterRequest $request,)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('customer');
        return response()->json(['message' => 'User registered successfully.'], 201);
    }
}
