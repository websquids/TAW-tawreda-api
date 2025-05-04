<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest {
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
      'price' => ['required', 'numeric', 'between:-999999.99,999999.99'],
      'discount' => ['required', 'numeric', 'between:-999999.99,999999.99'],
      'current_stock_quantity' => ['required', 'integer'],
      'category_id' => ['required', 'integer', 'exists:categories,id'],
      'brand_id' => ['required', 'integer', 'exists:brands,id'],
      'unit_id' => ['required', 'integer', 'exists:units,id'],
      'min_order_quantity' => ['required', 'integer'],
      'max_order_quantity' => ['required', 'integer'],
      'min_storage_quantity' => ['required', 'integer'],
      'max_storage_quantity' => ['required', 'integer'],
      'images' => ['required', 'array', 'max:10'],
      'images.*' => ['file', 'image'],
      'storage_discount' => ['required', 'numeric', 'between:-999999.99,999999.99'],
    ];
  }
}
