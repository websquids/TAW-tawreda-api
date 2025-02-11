<?php

use App\Models\OTP;

if (!function_exists('calcPriceWithDiscount')) {
  function calcPriceWithDiscount(float $price, float $discount): float {
    return $price - ($price * ($discount / 100));
  }
}
if (!function_exists('handleVerificationStatus')) {
  function handleVerificationStatus($verificationResponse): string {
    switch ($verificationResponse['status']) {
      case OTP::STATUS['VALID']:
        break;
      case OTP::STATUS['EXPIRED_AND_RESENT']:
        return response()->apiResponse(['message' => $verificationResponse['message']], 400);
      default:
        return response()->apiResponse(['message' => 'Invalid OTP.'], 400);
    }
  }
}
