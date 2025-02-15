<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\AppSettingRetrieveRequest;
use App\Http\Resources\CustomerApp\AppSettingsResource;
use App\Services\AppSettingsService;

class AppSettingsController extends Controller {
  protected AppSettingsService $appSettingsService;
  public function __construct(AppSettingsService $appSettingsService) {
    $this->appSettingsService = $appSettingsService;
  }

  public function index(AppSettingRetrieveRequest $request) {
    $key = $request->input('key');
    $value = $this->appSettingsService->getSettingByKey($key);
    return response()->apiResponse(AppSettingsResource::make($value));
  }
}
