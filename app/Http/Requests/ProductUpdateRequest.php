<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array {
        return [
            'en.title' => ['required', 'string'],
            'en.description' => ['required', 'string'],
            'ar.title' => ['required', 'string'],
            'ar.description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'discount' => ['required', 'numeric', 'between:0,100'],
            'current_stock_quantity' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'min_order_quantity' => ['required', 'integer', 'min:1'],
            'max_order_quantity' => ['required', 'integer', 'gte:min_order_quantity'],
            'min_storage_quantity' => ['required', 'integer', 'min:1'],
            'max_storage_quantity' => ['required', 'integer', 'gte:min_storage_quantity'],
        ];
    }
}
