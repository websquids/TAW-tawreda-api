<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        $translated = $this->translate($locale);
        return [
            'id' => $this->id,
            'name' => $translated->name,
            'image' => $this->getFirstMediaUrl('featured')->getUtl(),
        ];

        if ($request->all_translation_data) {
            $data['translations'] = $this->getTranslationsArray();
        }
        return $data;
    }
}
