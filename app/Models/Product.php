<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Product extends Model implements TranslatableContract, HasMedia {
  use HasFactory, InteractsWithMedia, Translatable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $translatedAttributes = ['title', 'description'];
  protected $fillable = [
    'price',
    'discount',
    'current_stock_quantity',
    'category_id',
    'brand_id',
    'unit_id',
    'min_order_quantity',
    'max_order_quantity',
    'min_storage_quantity',
    'max_storage_quantity',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'price' => 'decimal:2',
    'discount' => 'decimal:2',
    'category_id' => 'integer',
    'brand_id' => 'integer',
    'unit_id' => 'integer',
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
    'description' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'price' => [
      'searchable' => false,
      'sortable' => true,
    ],
    'discount' => [
      'searchable' => false,
      'sortable' => true,
    ],
    'current_stock_quantity' => [
      'searchable' => false,
      'sortable' => true,
    ],
    'category_id' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'parent_category' => [
      'searchable' => true,
      'relation' => [
        'category',
        'parent_id',
      ],
      'sortable' => false,
    ],
    'brand_id' => [
      'searchable' => false,
      'sortable' => true,
    ],
    'unit_id' => [
      'searchable' => false,
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

  public function registerMediaCollections(): void {
    $this->addMediaCollection('featured')->singleFile();
    $this->addMediaCollection('gallery')->onlyKeepLatest(10);
  }

  public function category(): BelongsTo {
    return $this->belongsTo(Category::class);
  }

  public function brand(): BelongsTo {
    return $this->belongsTo(Brand::class);
  }

  public function unit(): BelongsTo {
    return $this->belongsTo(Unit::class);
  }
}
