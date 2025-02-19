<?php

namespace App\Http\Controllers;

use App\Constants\ResetPasswordIdentifierTypes as ConstantsResetPasswordIdentifierTypes;
use App\Constants\VerifyTypes;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\CustomerApp\ChangePasswordRequest;
use App\Http\Requests\CustomerApp\DeleteAccountRequest;
use App\Http\Requests\CustomerApp\VerifySMSRequest;
use App\Models\OTP;
use App\Models\User;
use App\Services\OtpService;
use App\Services\ResetPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
  protected OtpService $otpService;
  protected ResetPasswordService $resetPasswordService;
  public function __construct(OtpService $otpService, ResetPasswordService $resetPasswordService) {
    $this->otpService = $otpService;
    $this->resetPasswordService = $resetPasswordService;
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

  public function verifyOTP(VerifySMSRequest $request, $chanel = 'sms') {
    switch ($chanel) {
      case 'sms':
        return $this->verifySMS($request);
      default:
        return response()->json(['message' => 'Invalid channel.'], 400);
    }
  }

  protected function verifySMS($request) {
    try {
      // dd($request->phone, $request->otp);
      $verifyStatus = $this->otpService->verifyOTP($request->phone, $request->otp);

      if ($verifyStatus['status'] === OTP::STATUS['EXPIRED_AND_RESENT']) {
        return response()->apiResponse(['message' => $verifyStatus['message']], 400);
      }

      if ($verifyStatus['status'] !== OTP::STATUS['VALID']) {
        return response()->apiResponse(['message' => 'Invalid OTP.'], 400);
      }

      return match ($request->verify_type) {
        VerifyTypes::REGISTER => $this->handleRegistrationVerification($request),
        VerifyTypes::FORGET_PASSWORD => $this->handleForgetPasswordVerification($request),
        default => response()->apiResponse(['message' => 'Invalid verify type.'], 400),
      };
    } catch (\Exception $e) {
      return response()->apiResponse(['message' => $e->getMessage()], 500);
    }
  }

  private function handleRegistrationVerification($request) {
    $user = User::where('phone', $request->phone)->first();

    if ($user->phone_verified_at !== null) {
      return response()->apiResponse(['message' => 'Phone number already verified.'], 400);
    }

    $user->update(['phone_verified_at' => now()]);

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
  }

  private function handleForgetPasswordVerification($request) {
    $user = User::where('phone', $request->phone)->first();

    if ($user->phone_verified_at === null) {
      return response()->apiResponse(['message' => 'Phone number not verified.'], 400);
    }
    $resetPasswordToken = $this->resetPasswordService->createResetToken($request->phone, ConstantsResetPasswordIdentifierTypes::PHONE);
    return response()->apiResponse(['reset_password_token' => $resetPasswordToken->token], 200);
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

  public function resetPassword(Request $request) {
    $validator = Validator::make($request->all(), [
      'reset_password_token' => 'required',
      'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
      $user = $this->resetPasswordService->getUserByResetToken($request->reset_password_token);
      if (!$user) {
        return response()->apiResponse(['message' => 'Invalid reset password token.'], 400);
      }
      $user->password = Hash::make($request->password);
      $user->save();
      return response()->apiResponse(['message' => 'Password reset successfully.'], 200);
    } catch (\Exception $e) {
      return response()->apiResponse(['message' => $e->getMessage()], 500);
    }
  }

  public function changePassword(ChangePasswordRequest $request) {
    $user = auth()->user();
    $user->password = Hash::make($request->new_password);
    $user->save();
    return response()->apiResponse(['message' => 'Password changed successfully.'], 200);
  }

  public function forgetPassword(Request $request) {
    $validator = Validator::make($request->all(), [
      'phone' => 'required|exists:users,phone',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
      $user = User::where('phone', $request->phone)->first();
      $this->otpService->sendOTP($request->phone, null);
      return response()->apiResponse([
        'message' => 'OTP sent to your phone number. Please use it to reset your password.',
      ], 200);
    } catch (\Exception $e) {
      return response()->apiResponse(['message' => $e->getMessage()], 500);
    }
  }

  public function deleteUser(DeleteAccountRequest $request) {
    // delete user
    $user = auth('api')->user();
    $user->delete();
    return response()->apiResponse(['message' => 'Account deleted successfully.'], 200);
  }
}
