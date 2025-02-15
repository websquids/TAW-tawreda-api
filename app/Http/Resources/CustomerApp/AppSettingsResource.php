<?php

namespace App\Http\Resources\CustomerApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppSettingsResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array {
    $locale = app()->getLocale();
    $translated = $this->translate($locale);
    return [
      'id' => $this->id,
      'key' => $this->key,
      'type' => $this->type,
      'value' => $this->has_translation ? $translated : $this->value,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
