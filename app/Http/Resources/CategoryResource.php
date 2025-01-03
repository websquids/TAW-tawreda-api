<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CategoryResource extends JsonResource {
  /**
   * Transform the resource into an array.
   */
  public function toArray(Request $request): array {
    // Retrieve all media items and map them with conversions
    $mediaItem = $this->getFirstMedia();
    $mediaWithConversions = [
      'original' => $mediaItem->getUrl(),
      'thumb' => $mediaItem->getUrl('thumb'),
      'medium' => $mediaItem->getUrl('medium'),
      'large' => $mediaItem->getUrl('large'),
    ];
    $locale = App::getLocale();
    $translation = $this->translate($locale);
    $data = [
      'id' => $this->id,
      'title' => $translation?->title ?? '',
      'description' => $translation?->description ?? '',
      'parent_id' => $this->parent_id,
    ];
    $data['media'] = $mediaWithConversions;
    if ($request->get('all_translation_data') == 'true') {
      $data['translations'] = $this->getTranslationsArray();
    }
    return $data;
  }
}
