<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
        $id = $this->route('category')?->id;
        // dd($this);

        return [
            // 'en.title' => ['required', 'string'],
            // 'ar.title' => ['required', 'string'],
            'image' => ['sometimes', 'image'],
            'parent_id' => [
                'nullable',
                'exists:categories,id,parent_id,NULL',
                function ($attribute, $value, $fail) use ($id) {
                    if ((int) $value === (int) $id) {
                        $fail('The parent category cannot be the same as the current category.');
                    }
                    // Check if the current category is already a parent to another category
                    $isParent = \App\Models\Category::where('parent_id', $id)->exists();
                    if ($isParent) {
                        $fail('This category cannot have a parent because it is already a parent of another category.');
                    }
                }
            ],
        ];
    }
}
