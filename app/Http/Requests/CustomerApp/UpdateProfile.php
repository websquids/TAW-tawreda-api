<?php

namespace App\Http\Requests\CustomerApp;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool {
    $user = auth()->user();
    return !empty($user?->phone_verified_at);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array {
    $user = auth()->user();
    return [
      'name' => ['sometimes', 'required', 'max:255'],
      'email' => ['sometimes', 'required', 'email', 'unique:users,email,' . $user?->id],
    ];
  }
}
