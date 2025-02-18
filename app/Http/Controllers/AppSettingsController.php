<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppSettingStoreRequest;
use App\Services\AppSettingsService;
use Illuminate\Http\Request;

class AppSettingsController extends Controller {
  protected AppSettingsService $appSettingsService;
  public function __construct(AppSettingsService $appSettingsService) {
    $this->appSettingsService = $appSettingsService;
  }
  public function index(Request $request) {
    $appSettings = $this->appSettingsService->getAppSettings($request);
    return response()->apiResponse($appSettings);
  }

  public function show($id) {
    $appSetting = $this->appSettingsService->getById($id);
    if (!$appSetting) {
      return response()->apiResponse(null, 'App Setting not found', 404);
    }
    if ($appSetting->has_translation) {
      $appSetting->load('translations');
    } else {
      $appSetting->load('value');
    }
    return response()->apiResponse($appSetting);
  }

  public function store(AppSettingStoreRequest $request) {
    return $this->appSettingsService->create($request);
  }

  // update app settings
  public function update(AppSettingStoreRequest $request) {
    return $this->appSettingsService->update($request);
  }
}
