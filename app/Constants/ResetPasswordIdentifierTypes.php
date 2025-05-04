<?php

namespace App\Constants;

class ResetPasswordIdentifierTypes {
  public const EMAIL = 'email';
  public const PHONE = 'phone';

  public static function getAllTypes() {
    return [
      self::EMAIL,
      self::PHONE,
    ];
  }
}
