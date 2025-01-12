<?php

namespace App\Http\Requests\CustomerApp;

use App\Constants\AddressTypes;
use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'street' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string',
            'building_number' => 'required|string',
            'mobile_number' => 'required|string',
            'model_type' => [
            'required',
            'string',
            'in:' . implode(',', AddressTypes::getAllTypes()),
        ],
        'model_id' => 'required|integer'
        ];
    }
}
