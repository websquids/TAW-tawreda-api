<?php

namespace App\Http\Requests\CustomerApp;

use App\Constants\OrderTypes;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest {
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
      'address_id' => 'required|exists:addresses,id',
      'order_type' => ['required', 'in:'.implode(',', OrderTypes::getAllTypes())],
    ];
  }
}
