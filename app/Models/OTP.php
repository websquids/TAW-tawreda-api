<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model {
  protected $fillable = ['phone', 'otp', 'expires_at'];
  public const EXPIRY_TIME = 5; // in minutes
  protected $table = 'otps';

  public const STATUS = [
    'VALID' => 1,
    'INVALID' => 2,
    'EXPIRED_AND_RESENT' => 3,
  ];
}
