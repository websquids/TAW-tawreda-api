<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();
        $translatedTitle = $this->translate($locale);

        $data = [
            'id' => $this->id,
            'title' => $translatedTitle ? $translatedTitle->title : null,
            'parent_id' => $this->parent_id,
        ];

        if ($request->all_translation_data == 'true') {
            $data['translations'] = $this->getTranslationsArray();
        }

        if ($request->has_image !== 'false') {
            $data['image'] = $this->getMedia('featured')->isNotEmpty()
                ? url($this->getMedia('featured')->first()->getUrl())
                : null;
        }

        return $data;
    }
}
