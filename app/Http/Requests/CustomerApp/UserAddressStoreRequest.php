<?php

namespace App\Http\Requests\CustomerApp;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressStoreRequest extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool {
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array {
    return [
      'street' => 'required|string',
      'city' => 'required|string',
      'state' => 'required|string',
      'country' => 'required|string',
      'postal_code' => 'required|string',
      'building_number' => 'required|string',
      'mobile_number' => 'required|string',
      'address_type' => 'required|string',
    ];
  }
}
