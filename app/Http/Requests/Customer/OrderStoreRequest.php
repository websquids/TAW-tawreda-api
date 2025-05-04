<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest {
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
      'order_type' => 'required|in:customer,investor',
      'order_items' => 'required|array|min:1',
      'order_items.*.product_id' => 'required|exists:products,id',
      'order_items.*.quantity' => 'required|integer|min:1',
      'order_address_id' => 'required_if:order_address,null|exists:addresses,id',
      'order_address' => 'required_if:order_address_id,null,min:1,max:1',
      'order_address.street' => 'required|string|max:255',
      'order_address.city' => 'required|string|max:255',
      'order_address.state' => 'required|string|max:255',
      'order_address.country' => 'required|string|max:255',
      'order_address.postal_code' => 'required|string|max:255',
      'order_address.building_number' => 'required|string|max:255',
      'order_address.mobile_number' => 'required|string|max:255',
    ];
  }
}
