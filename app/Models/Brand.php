<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Brand extends Model implements TranslatableContract, HasMedia {
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

    public function registerMediaCollections(): void {
        $this->addMediaCollection('featured')->singleFile();
    }
}
