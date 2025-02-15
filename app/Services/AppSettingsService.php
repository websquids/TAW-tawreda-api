<?php

namespace App\Services;

use App\Filters\AppSettingsFilter;
use App\Models\AppSetting;
use App\Models\AppSettingValue;

class AppSettingsService {
  protected AppSetting $appSetting;
  protected AppSettingValue $appSettingValue;
  protected AppSettingsFilter $filter;


  public function __construct(AppSetting $appSetting, AppSettingsFilter $filter, AppSettingValue $appSettingValue) {
    $this->appSetting = $appSetting;
    $this->filter = $filter;
    $this->appSettingValue = $appSettingValue;
  }

  public function getSettingByKey($key) {
    $appSetting = $this->appSetting->where('key', $key)->first();
    if ($appSetting->has_translation) {
      return $appSetting->load('translations')->first();
    } else {
      return $appSetting->value();
    }
  }


  public function create($request) {
    if (!$request->has_translation) {
      $appSetting = $this->appSetting->create([
        'key' => $request->key,
        'type' => $request->type,
      ]);
      $this->appSettingValue->create([
        'app_setting_id' => $appSetting->id,
        'value' => $request->value,
      ]);
      return $appSetting;
    } else {
      $appSetting = $this->appSetting->create($request->all());
      return $appSetting->load('translations');
    }
  }

  public function update($request, $appSetting) {
    if (!$request->has_translation) {
      $appSetting->update([
        'key' => $request->key,
        'type' => $request->type,
      ]);
      $appSetting->value()->update([
        'value' => $request->value,
      ]);
      return $appSetting;
    } else {
      $appSetting->update($request->all());
      return $appSetting->load('translations');
    }
  }

  public function getAppSettings($request) {
    return $this->filter->apply($this->appSetting->query(), $request);
  }
}
