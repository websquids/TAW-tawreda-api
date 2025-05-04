<?php

namespace App\Http\Requests;

use App\Constants\AppSettingTypes;
use Illuminate\Foundation\Http\FormRequest;

class AppSettingStoreRequest extends FormRequest {
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
      'key' => 'required|unique:app_settings,key',
      'has_translation' => 'required|boolean',
      'value' => 'required_if:has_translation,false',
      'en.value' => 'required_if:has_translation,true',
      'ar.value' => 'required_if:has_translation,true',
      'type' => [
        'required',
        'in:'. implode(',', AppSettingTypes::getAllTypes()),
      ],
    ];
  }
}
