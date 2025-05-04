<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderCreateRequest extends FormRequest {
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
      'is_active' => 'required|boolean',
      'images' => [
        'required',
        'array',
        'min:1',
        'max:5',
      ],
      'images.*' => [
        'required',
        'image',
        'mimes:jpeg,png,jpg,gif,svg',
        'max:2048',
      ],
    ];
  }
}
