<?php

namespace App\Http\Controllers;

use App\Http\Requests\SliderStoreRequest;
use App\Models\Slider;
use App\Services\SliderService;
use Illuminate\Http\Request;

class SliderController extends Controller {
  protected SliderService $sliderService;
  public function __construct(SliderService $sliderService) {
    $this->sliderService = $sliderService;
  }
  public function index(Request $request) {
    $sliders = $this->sliderService->getFilteredSlider($request);
    return response()->json($sliders);
  }
  public function store(SliderStoreRequest $request) {
    $slider = $this->sliderService->createSlider($request);
    return response()->json($slider);
  }

  public function edit(SliderStoreRequest $request, Slider $slider) {
    $slider = $this->sliderService->updateSlider($slider, $request);
    return response()->json($slider);
  }
}
