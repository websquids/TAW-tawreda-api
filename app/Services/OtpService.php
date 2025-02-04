<?php

namespace App\Services;

use App\Models\OTP;

class OtpService {
  protected TwilioService $twilioService;
  public function __construct(TwilioService $twilioService) {
    $this->twilioService = $twilioService;
  }
  // Add your methods here
  public function sendOTP($phone, $message) {
    // Implement OTP sending logic here
    $otp = random_int(100000, 999999);
    try {
      OTP::updateOrCreate(
        ['phone' => $phone],
        [
          'otp' => $otp,
          'expires_at' => now()->addMinutes(5),
        ],
      );
      $this->twilioService->sendSMS($phone, $message ?? "Your OTP is: $otp");
      $otp = OTP::where('phone', $phone)->first();
      return $otp;
    } catch (\Exception $e) {
      dd($e);
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }

  public function verifyOTP($phone, $otp): array {
    $storedOTP = OTP::where('phone', $phone)->first();
    if ($storedOTP->otp == $otp) {
      if ($storedOTP->expires_at < now()) {
        $this->sendOTP($phone, null);
        return ['status' => OTP::STATUS['EXPIRED_AND_RESENT'],'message' => 'OTP expired. New OTP sent.'];
      } else {
        $storedOTP->delete();
        return ['status' => OTP::STATUS['VALID'],'message' => 'OTP verified successfully.'];
      }
    }
    // Default response if OTP is invalid or not found
    return [
      'status' => OTP::STATUS['INVALID'],
      'message' => 'Invalid OTP.',
    ];
  }

  public function getOtp($phone) {
    $otp = OTP::where('phone', $phone)->first();
    return $otp;
  }

  public function deleteOtpByPhone($phone) {
    $otp = OTP::where('phone', $phone)->first();
    if ($otp) {
      $otp->delete();
    }
  }
}
