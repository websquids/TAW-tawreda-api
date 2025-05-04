<?php

namespace App\Http\Requests\CustomerApp;

use Illuminate\Foundation\Http\FormRequest;

class GetOrders extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool {
    return true;
  }

  protected function prepareForValidation() {
    $this->merge([
      'search' => array_merge(
        ['order_type' => 'customer'],
        (array) $this->get('search', []),
      ),
    ]);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array {
    return [
      'search' => ['sometimes', 'array'],
      'search.order_type' => ['sometimes', 'required', 'in:customer,investor'],
      'search.order_status' => ['sometimes'],
      'search.created_at' => ['sometimes', 'array'],
      'search.created_at.from' => ['required_with:search.created_at', 'date:y-m-d'],
      'search.created_at.to' => ['required_with:search.created_at', 'date:y-m-d'],
    ];
  }
}
