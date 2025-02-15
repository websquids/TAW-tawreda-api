<?php

namespace App\Constants;

class AppSettingTypes {
  public const STRING = 'string';
  public const INTEGER = 'integer';
  public const BOOLEAN = 'boolean';
  public const ARRAY = 'array';
  public const OBJECT = 'object';

  public static function getAllTypes() {
    return [
      self::STRING,
      self::INTEGER,
      self::BOOLEAN,
      self::ARRAY,
      self::OBJECT,
    ];
  }
}
