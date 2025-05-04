<?php

namespace App\Enums;

enum AppSettingsEnums: string {
  case STRING = 'string';
  case INTEGER = 'integer';
  case BOOLEAN = 'boolean';
  case ARRAY = 'array';
  case OBJECT = 'object';
}
