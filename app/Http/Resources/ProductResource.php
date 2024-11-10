<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'price' => $this->price,
            'discount' => $this->discount,
            'current_stock_quantity' => $this->current_stock_quantity,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'unit_id' => $this->unit_id,
            'min_order_quantity' => $this->min_order_quantity,
            'max_order_quantity' => $this->max_order_quantity,
            'min_storage_quantity' => $this->min_storage_quantity,
            'max_storage_quantity' => $this->max_storage_quantity,
            'featured_image' => $this->getFirstMediaUrl('featured'),
            'images' => $this->getMedia('gallery')->map(function ($media) {
                return $media->getUrl();
            }),
        ];
    }
}
