<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

use function PHPUnit\Framework\isEmpty;

class ProductResource extends JsonResource {
  /**
   * Transform the resource into an array.
   */
  public function toArray(Request $request): array {
    // get app_mode from the request parameters
    $appMode = $request->get('app_mode');
    $locale = App::getLocale();
    $mediaItems = $this->getMedia('products');

    $mediaWithConversions = $mediaItems->map(function ($media) {
      return [
        'original' => $media->getUrl(),
        'thumb' => $media->getUrl('thumb'),
        'medium' => $media->getUrl('medium'),
        'large' => $media->getUrl('large'),
      ];
    });
    $translatedTitle = $this->translate($locale);
    $data = [
      'id' => $this->id,
      'price' => $this->price,
      'discount' => $this->discount,
      'current_stock_quantity' => $this->current_stock_quantity,
      'category_id' => $this->category_id,
      'media' => $mediaWithConversions,
      'brand' => $this->whenLoaded('brand', function () {
        return [
          'id' => $this->brand->id,
          'name' => $this->brand->name,
          'image' => $this->brand->getFirstMediaUrl('featured'),
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
      'storage_discount' => $this->storage_discount,
    ];
    if ($request->get('has_image') !== 'false') {
      $data['images'] = $this->getMedia('gallery')->map(fn ($media) => url($media->getUrl()))->toArray();
      $data['featureImage'] = $this->getFirstMediaUrl('featured');
      if (isEmpty($data['featureImage'])) {
        $data['featureImage'] = $this->getFirstMediaUrl('gallery');
      }
    }
    if ($request->get('all_translation_data') == 'true') {
      $data['translations'] = $this->getTranslationsArray();
    } else {
      $data['title'] = $translatedTitle?->title ?? '';
      $data['description'] = $translatedTitle?->description ?? '';
    }
    if ($appMode == 'investor') {
      $data['investor_price'] = calcPriceWithDiscount($this->price, $this->storage_discount);
    }
    return $data;
  }
}
