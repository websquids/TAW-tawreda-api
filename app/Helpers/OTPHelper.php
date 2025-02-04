<?php

namespace App\Helpers;

use App\Models\OTP;

class OTPHelper {
  public static function sendOTP($phone, $message) {
    $otp = random_int(100000, 999999);

    OTP::updateOrCreate(
      ['phone' => $phone],
      [
        'otp' => $otp,
        'expires_at' => now()->addMinutes(5),
      ],
    );
    sendSms($phone, $message ?? "Your OTP is: $otp");
    return $otp;
  }
}
