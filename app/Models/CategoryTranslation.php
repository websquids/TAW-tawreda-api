<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model {
  protected $fillable = ['title', 'description'];
  public $timestamps = false;
}
