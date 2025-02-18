<?php

namespace App\Services;

use App\Filters\AppSettingsFilter;
use App\Http\Resources\CustomerApp\AppSettingsResource;
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
    return $appSetting;
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
    $query = $this->filter->apply($this->appSetting->query(), $request);

    $perPage = (int) $request->get('perPage', 10);

    if ($perPage === -1) {
      $appSettings = $query->get();
      return $appSettings;
    }

    // Otherwise, paginate the results
    $paginatedAppSettings = $query->paginate($perPage);

    $paginatedAppSettings->data = AppSettingsResource::collection($paginatedAppSettings);

    return $paginatedAppSettings;
  }

  public function getById($id) {
    $appSetting = $this->appSetting->findOrFail($id);
    return $appSetting;
  }
}
