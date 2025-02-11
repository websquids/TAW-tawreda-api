<?php

namespace App\Http\Requests\CustomerApp;

use App\Constants\VerifyTypes;
use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class VerifySMSRequest extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool {
    return true;
  }

  protected function prepareForValidation() {
    if ($this->has('phone')) {
      $this->merge([
        'phone' => ltrim($this->input('phone'), '0'), // Remove leading zero
      ]);
    }
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array {
    return [
      'phone' => ['required', 'exists:users,phone', 'exists:otps,phone', new ValidPhoneNumber()],
      'otp' => 'required|numeric|digits:6',
      'fcm_token' => 'required_if:verify_type,register', // required if verify type is register
      'device_name' => 'required_if:verify_type,register', // required if verify type is register
      'verify_type' => ['required', 'string', 'in:'. implode(',', VerifyTypes::getAllTypes())],
    ];
  }
}
