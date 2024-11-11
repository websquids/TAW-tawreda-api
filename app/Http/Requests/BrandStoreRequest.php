<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandStoreRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array {
        return [
            'name_en' => ['required', 'string'],
            'name_ar' => ['required', 'string'],
            'image' => ['required', 'file', 'image'],
            'description_en' => ['required', 'string'],
            'description_ar' => ['required', 'string'],
        ];
    }
}
