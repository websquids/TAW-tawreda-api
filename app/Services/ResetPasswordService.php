<?php

namespace App\Services;

use App\Models\resetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordService {
  public function createResetToken(string $identifier, string $identifierType): ResetPassword {
    // Generate a unique token
    $token = Str::random(60);

    // Create or update the reset password record
    return resetPassword::updateOrCreate(
      [
        'identifier' => $identifier,
        'identifier_type' => $identifierType,
      ],
      [
        'token' => Hash::make($token),
      ],
    );
  }

  public function getUserByResetToken(string $token): User | bool {
    $resetPassword = ResetPassword::where('token', $token)
        ->first();
    if (!$resetPassword) {
      return false;
    }
    // delete the reset password record
    $resetPassword->delete();

    return User::where($resetPassword->identifier_type, $resetPassword->identifier)
        ->first();
  }
}
