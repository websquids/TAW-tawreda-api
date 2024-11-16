<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'en.title' => ['required', 'string'],
            'ar.title' => ['required', 'string'],
            'image' => ['required', 'mimes:jpg,jpeg,png'],
            'parent_id' => ['nullable', 'exists:categories,id,parent_id,NULL'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException(
            $validator,
            response()->json($validator->errors(), 422)
        );
    }
}
