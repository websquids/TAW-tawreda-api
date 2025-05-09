<?php

namespace App\Http\Requests\Auth;

use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool {
    return true;
  }

  /**
   * Prepare the data for validation.
   */
  protected function prepareForValidation() {
    if ($this->has('phone')) {
      $phone = preg_replace('/\D/', '', $this->input('phone')); // Remove non-digit characters

      if (str_starts_with($phone, '20') && strlen($phone) === 12) {
        $phone = substr($phone, 2); // Remove country code
      }

      if (str_starts_with($phone, '0') && strlen($phone) === 11) {
        $phone = substr($phone, 1); // Remove leading zero
      }

      $this->merge(['phone' => $phone]);
    }
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array {
    return [
      'name' => 'required|string|max:255',
      'phone' => ['required', 'string', new ValidPhoneNumber(), 'unique:users'],
      'email' => 'nullable|string|email|max:255|unique:users,email',

      'password' => 'required|string|min:8|confirmed',
    ];
  }
}
