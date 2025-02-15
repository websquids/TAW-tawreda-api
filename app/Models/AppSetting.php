<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class AppSetting extends Model implements TranslatableContract {
  use Translatable;

  public $translatedAttributes = ['value'];
  protected $fillable = ['key'];


  protected static array $fields = [
    'key' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'created_at' => [
      'searchable' => false,
      'sortable' => true,
    ],
    'updated_at' => [
      'searchable' => false,
      'sortable' => true,
    ],
  ];

  public static function getFields(): array {
    $instance = new static(); // Create an instance of the model
    $fields = self::$fields;
    $translatedAttributes = $instance->translatedAttributes;

    // Dynamically mark fields as translated based on $translatedAttributes
    foreach ($translatedAttributes as $translatedAttribute) {
      if (isset($fields[$translatedAttribute])) {
        $fields[$translatedAttribute]['translated'] = true;
      } else {
        // Add translated fields that aren't explicitly defined in $fields
        $fields[$translatedAttribute] = [
          'translated' => true,
          'searchable' => true, // Default behavior for translated fields
          'sortable' => false, // Optional, adjust as needed
        ];
      }
    }

    return $fields;
  }
  public function value() {
    return $this->hasOne(AppSettingValue::class);
  }
}
