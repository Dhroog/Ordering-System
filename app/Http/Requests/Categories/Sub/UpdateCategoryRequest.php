<?php

namespace App\Http\Requests\Categories\Sub;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required','string'],
            'description' => ['required','string'],
            'restaurant_id' => ['required','integer','exists:restaurants,id'],
            'main_id' => ['required','integer','exists:main_categories,id']
        ];
    }
}
