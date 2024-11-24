<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class ProductResource extends JsonResource {
  /**
   * Transform the resource into an array.
   */
  public function toArray(Request $request): array {
    $media = $this->getMedia('featured')->first();
    $locale = App::getLocale();
    $translatedTitle = $this->translate($locale);
    // dd($this);
    $data = [
      'id' => $this->id,
      'price' => $this->price,
      'discount' => $this->discount,
      'current_stock_quantity' => $this->current_stock_quantity,
      'category_id' => $this->category_id,
      'brand' => $this->whenLoaded('brand', function () {
        return [
          'id' => $this->brand->id,
          'name' => $this->brand->name,
        ];
      }),
      'unit' => $this->whenLoaded('unit', function () {
        return [
          'id' => $this->unit->id,
          'name' => $this->unit->name,
        ];
      }),
      'category' => $this->whenLoaded('category', function () {
        return [
          'id' => $this->category->id,
          'title' => $this->category->title,
          'parent_id' => $this->category->parent_id,
        ];
      }),
      'unit_id' => $this->unit_id,
      'min_order_quantity' => $this->min_order_quantity,
      'max_order_quantity' => $this->max_order_quantity,
      'min_storage_quantity' => $this->min_storage_quantity,
      'max_storage_quantity' => $this->max_storage_quantity,
    ];
    if ($request->get('has_image') !== 'false') {
      $data['images'] = $this->getMedia('gallery')->map(fn($media) => url($media->getUrl()))->toArray();
    }
    if ($request->get('all_translation_data') == 'true') {
      $data['translations'] = $this->getTranslationsArray();
    } else {
      $data['title'] = $translatedTitle->title;
      $data['description'] = $translatedTitle->description;
    }
    return $data;
  }
}
