<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;

class CategoryFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'=> [
                'required',
                'string'
            ],
            'slug'=> [
                'required',
                'string'
            ],
            'description'=> [
                'required'
            ],
            'image'=> [
                'nullable',
                'mimes:jpeg,png,jpg',
            ],
            'meta_title'=> [
                'required',
                'string'
            ],
            'meta_description'=> [
                'required',
                'string'
            ],
            'meta_keyword'=> [
                'required',
                'string'
            ],

        ];
    }
}
