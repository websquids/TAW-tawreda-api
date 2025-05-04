<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Slider extends Model implements HasMedia {
  use InteractsWithMedia;

  protected $fillable = ['is_active'];

  protected static array $fields = [
    'created_at' => [
      'searchable' => false,
      'sortable' => true,
    ],
    'updated_at' => [
      'searchable' => false,
      'sortable' => true,
    ],
  ];

  /**
   * Get the fields configuration.
   *
   * @return array
   */
  public static function getFields(): array {
    $fields = self::$fields;
    return $fields;
  }
}
