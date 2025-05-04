<?php

namespace App\Constants;

class ResetPasswordType {
  public const FORGET_PASSWORD = 'forget_password';
  public const CHANGE_PASSWORD = 'change_password';

  public static function getAllTypes() {
    return [
      self::FORGET_PASSWORD,
      self::CHANGE_PASSWORD,
    ];
  }
}
