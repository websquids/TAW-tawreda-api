<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource {
  /**
   * Transform the resource into an array.
   */
  public function toArray(Request $request): array {
    $locale = app()->getLocale();
    $translated = $this->translate($locale);
    $data = [
        'id' => $this->id,
        'name' => $translated->name,
        'image' => $this->getFirstMediaUrl('featured'),
    ];
    if ($request->get('all_translation_data') == 'true') {
      $data['translations'] = $this->getTranslationsArray();
    }
    return $data;
  }
}
