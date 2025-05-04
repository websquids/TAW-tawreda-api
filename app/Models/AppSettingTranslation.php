<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettingTranslation extends Model {
  protected $fillable = ['value'];
  public $table = 'app_setting_translations';
}
