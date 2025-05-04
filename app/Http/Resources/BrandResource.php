<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource {
  /**
   * Transform the resource into an array.
   */
  public function toArray(Request $request): array {
    $mediaItem = $this->getFirstMedia('brands');
    $mediaWithConversions = [
      'original' => $mediaItem->getUrl(),
      'thumb' => $mediaItem->getUrl('thumb'),
      'medium' => $mediaItem->getUrl('medium'),
      'large' => $mediaItem->getUrl('large'),
    ];
    $locale = app()->getLocale();
    $translated = $this->translate($locale);
    $data = [
      'id' => $this->id,
      'name' => $translated->name,
      'media' => $mediaWithConversions,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
    if ($request->get('all_translation_data') == 'true') {
      $data['translations'] = $this->getTranslationsArray();
    }
    return $data;
  }
}
