<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array {
    $mediaItems = $this->getMedia('sliders');
    $mediaUrls = $mediaItems->map(function ($media) {
      return [
        'id' => $media->id,
        'image' => $media->getUrl(),
      ];
    });
    return [
      'id' => $this->id,
      'media' => $mediaUrls,
    ];
  }
}
