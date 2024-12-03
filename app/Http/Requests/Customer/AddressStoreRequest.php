<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest {
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
      'street' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'state' => 'required|string|max:255',
      'country' => 'required|string|max:255',
      'postal_code' => 'required|string|max:255',
      'building_number' => 'required|string|max:255',
      'mobile_number' => 'required|string|max:255',
      'address_type' => 'required|enum:user,order',
      'order_id' => 'nullable|exists:orders,id,required_if:address_type,order',
    ];
  }
}
