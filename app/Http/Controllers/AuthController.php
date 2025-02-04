<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\OTP;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
  protected OtpService $otpService;
  public function __construct(OtpService $otpService) {
    $this->otpService = $otpService;
  }
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
      'phone' => 'required|exists:users,phone',
      'password' => 'required|string',
      'fcm_token' => 'required|string',
      'device_name' => 'required|string',
    ]);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }
    $user = User::where('phone', $request->phone)->first();
    if ($user->phone_verified_at === null) {
      return response()->json(['message' => 'Phone number not verified.'], 401);
    }
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
    DB::beginTransaction();
    try {
      $user = User::create($request->validated());
      $user->assignRole('customer');
      $this->otpService->sendOTP($request->phone, null);
      DB::commit();
      return response()->apiResponse(['message' => 'User registered successfully. OTP sent to your phone number.
            '], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->apiResponse(['message' => $e->getMessage()], 500);
    }
  }

  public function verifyOTP(Request $request, $chanel = 'sms') {
    switch ($chanel) {
      case 'sms':
        return $this->verifySMS($request);
      default:
        return response()->json(['message' => 'Invalid channel.'], 400);
    }
  }

  protected function verifySMS($request) {
    $validator = Validator::make($request->all(), [
      'phone' => 'required|exists:users,phone|exists:otps,phone',
      'otp' => 'required|numeric|digits:6',
      'fcm_token' => 'required',
      'device_name' => 'required|string',
    ]);
    try {
      if ($validator->fails()) {
        return response()->apiResponse(['errors' => $validator->errors()], 422);
      }
      $user = User::where('phone', $request->phone)->first();
      $verifyStatus = $this->otpService->verifyOTP($request->phone, $request->otp);
      switch ($verifyStatus['status']) {
        case OTP::STATUS['VALID']:
          break;
        case OTP::STATUS['EXPIRED_AND_RESENT']:
          return response()->apiResponse(['message' => $verifyStatus['message']], 400);
        default:
          return response()->apiResponse(['message' => 'Invalid OTP.'], 400);
      }
      $user->update(['phone_verified_at' => now()]);
      $this->otpService->deleteOtpByPhone($request->phone);
      $token = $user->createToken('UserApp')->accessToken;
      $roles = $user->getRoleNames();
      $permissions = $user->getAllPermissions()->pluck('name');
      $user->fcmTokens()->create([
        'fcm_token' => $request->fcm_token,
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
    } catch (\Exception $e) {
      return response()->apiResponse(['message' => $e->getMessage()], 500);
    }
  }

  public function customerLogout(Request $request) {
    // Validate fcm_token
    $validator = Validator::make($request->all(), [
      'fcm_token' => 'required|string|exists:fcm_tokens,fcm_token',
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
