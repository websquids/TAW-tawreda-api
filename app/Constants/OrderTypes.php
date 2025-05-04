<?php

namespace App\Constants;

class OrderTypes {
  public const CUSTOMER = 'customer';
  public const INVESTOR = 'investor';

  public static function getAllTypes() {
    return [
      self::CUSTOMER,
      self::INVESTOR,
    ];
  }
}
