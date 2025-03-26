<?php

namespace App\Http\Requests\CustomerApp;

use Illuminate\Foundation\Http\FormRequest;

class GetProducts extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array {
    return [
      'search' => ['nullable', 'array'],
      'search.brand_id' => ['nullable', 'exists:brands,id'],
      'search.min_price' => ['required_with:search.max_price', 'integer', 'lt:search.max_price'],
      'search.max_price' => ['required_with:search.min_price', 'integer', 'gt:search.min_price'],
    ];
  }
}
