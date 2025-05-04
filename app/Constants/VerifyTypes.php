<?php

namespace App\Constants;

class VerifyTypes {
  public const REGISTER = 'register';
  public const FORGET_PASSWORD = 'forget_password';

  public static function getAllTypes() {
    return [
      self::REGISTER,
      self::FORGET_PASSWORD,
    ];
  }
}
