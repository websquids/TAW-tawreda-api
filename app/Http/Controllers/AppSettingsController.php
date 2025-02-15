<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppSettingStoreRequest;
use App\Services\AppSettingsService;

class AppSettingsController extends Controller {
  protected AppSettingsService $appSettingsService;
  public function __construct(AppSettingsService $appSettingsService) {
    $this->appSettingsService = $appSettingsService;
  }
  public function index() {
    // return $this->appSettingsService->getAppSettings();
  }

  public function store(AppSettingStoreRequest $request) {
    return $this->appSettingsService->create($request);
  }

  // update app settings
  public function update(AppSettingStoreRequest $request) {
    return $this->appSettingsService->update($request);
  }
}
