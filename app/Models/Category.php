<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements TranslatableContract, HasMedia {
  use Translatable;
  use HasFactory;
  use InteractsWithMedia;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $translatedAttributes = ['title', 'description'];




  protected $fillable = [
    'parent_id',
  ];

  /**
   * The fields allowed for searching and sorting.
   *
   * @var array
   */
  protected static array $fields = [
    'title' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'parent_id' => [
      'searchable' => true,
      'relation' => Category::class,
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

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
  ];

  /**
   * Get the fields configuration.
   *
   * @return array
   */
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
  public array $mediaSizes = [
    'thumb' => ['width' => 150, 'height' => 150, 'quality' => 75],
    'medium' => ['width' => 300, 'height' => 300, 'quality' => 80],
    'large' => ['width' => 1000, 'height' => 1000, 'quality' => 100],
  ];

  public function registerMediaConversions(?Media $media = null): void {
    foreach ($this->mediaSizes as $key => $size) {
      $this->addMediaConversion($key)
          ->width($size['width'])
          ->height($size['height'])
          ->quality($size['quality']);
    }
  }

  public function products() {
    return $this->hasMany(Product::class);
  }
}
