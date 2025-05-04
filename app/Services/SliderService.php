<?php

namespace App\Services;

use App\Filters\SliderFilter;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderService {
  protected Slider $sliderModel;
  protected SliderFilter $sliderFilter;
  public function __construct(Slider $sliderModel, SliderFilter $sliderFilter) {
    $this->sliderModel = $sliderModel;
    $this->sliderFilter = $sliderFilter;
  }
  public function getActiveSlider(): Slider {
    $slider = $this->sliderModel->where('is_active', true)->first();
    if (!$slider) {
      throw new \Exception('No active slider found');
    }
    // $slider['media'] = $slider->getMedia('sliders')->map(function ($media) {
        //     return [
        //       'original' => $media->getUrl(),
        //     ];
    // })->toArray();
    return $slider;
  }

  public function getFilteredSlider($request) {
    $query = $this->sliderFilter->apply(Slider::query(), $request);
    $perPage = (int) $request->get('perPage', 10);
    $paginatedSliders = $query->paginate($perPage);
    $paginatedSliders->data = SliderResource::collection($paginatedSliders);
    return $paginatedSliders;
  }

  public function createSlider($request): Slider {
    $slider = Slider::create($request->all());
    if ($request->is_active) {
      Slider::where('id', '!=', $slider->id)->update(['is_active' => false]);
    }
    if ($request->hasFile('images')) {
      foreach ($request->images as $file) {
        $slider->addMedia($file)->toMediaCollection('sliders');
      }
    }
    return $slider;
  }
  public function updateSlider(Slider $slider, Request $request): Slider {
    if ($request['is_active']) {
      Slider::where('is_active', true)->where('id', '!=', $slider->id)->update(['is_active' => false]);
    }
    if ($request->hasFile('sliders')) {
      $slider->clearMediaCollection('sliders');
      $slider->addMedia($request->file('sliders'))->toMediaCollection('sliders');
    }
    $slider->update($request->all());
    $slider->save();
  }

  public function deleteSliderMediaByIds(Slider $slider, array $ids): void {
    $sliderMedia = $slider->getMedia('sliders');
    foreach ($sliderMedia as $media) {
      if (in_array($media->id, $ids)) {
        $media->delete();
      }
    }
    $slider->save();
    return;
  }
}
