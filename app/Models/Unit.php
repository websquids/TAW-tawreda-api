<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Unit extends Model implements TranslatableContract {
  use HasFactory, Translatable;

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
}
