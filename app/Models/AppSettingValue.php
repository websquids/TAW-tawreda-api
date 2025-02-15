<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettingValue extends Model {
  protected $fillable = ['app_setting_id','value'];

  public function appSetting() {
    return $this->belongsTo(AppSetting::class);
  }
}
