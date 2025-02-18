<?php

namespace App\Constants;

class AppSettingTypes {
  public const STRING = 'string';
  public const INTEGER = 'integer';
  public const BOOLEAN = 'boolean';
  public const ARRAY = 'array';
  public const OBJECT = 'object';
  public const HTML = 'html';
  public const IS_DELETABLE = 'is_deletable';
  public const IS_VALUE_EDITABLE = 'is_value_editable';

  public static function getAllTypes() {
    return [
      self::STRING,
      self::INTEGER,
      self::BOOLEAN,
      self::ARRAY,
      self::OBJECT,
      self::HTML,
      self::IS_DELETABLE,
      self::IS_VALUE_EDITABLE,
    ];
  }
}
