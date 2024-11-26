<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Brand extends Model implements TranslatableContract, HasMedia
{
    use HasFactory, InteractsWithMedia, Translatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = ['name'];
    protected $fillable = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * The fields allowed for searching and sorting.
     *
     * @var array
     */
    protected static array $fields = [
        'name' => [
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

    /**
     * Get the fields configuration.
     *
     * @return array
     */
    public static function getFields(): array
    {
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
    }
}
