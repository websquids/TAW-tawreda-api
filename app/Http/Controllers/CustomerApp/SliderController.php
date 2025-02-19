<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Services\SliderService;
use Illuminate\Http\Request;

class SliderController extends Controller {
  protected SliderService $sliderService;
  public function __construct(SliderService $sliderService) {
    $this->sliderService = $sliderService;
  }

  public function index(Request $request) {
    $slider = $this->sliderService->getActiveSlider();
    return response()->apiResponse(SliderResource::make($slider), 200);
  }
}
