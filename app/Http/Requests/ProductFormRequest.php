<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;

class ProductFormRequest extends FormRequest
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
            'category_id'=> [
                'required',
                'integer'
            ],
            'name'=> [
                'required',
                'string'
            ],
            'slug'=> [
                'required',
                'string',
                'max:255'
            ],
            'brand'=> [
                'required',
                'string',
                'max:255'
            ],
            'small_description'=> [
                'nullable',
                'string'
            ],
            'description'=> [
                'required',
                'string'
            ],
            'original_price'=> [
                'required',
                'integer'
            ],
            'selling_price'=> [
                'required',
                'integer'
            ],
            'quantity'=> [
                'required',
                'integer'
            ],
            'trending'=> [
                'nullable',
            ],
            'featureds'=> [
                'nullable',
            ],
            'status '=> [
                'nullable',
            ],
            'meta_title'=> [
                'required',
                'string',
                'max:255'
            ],
            'meta_description'=> [
                'required',
                'string'
            ],
            'meta_keyword'=> [
                'required',
                'string'
            ],
            'image' => [
                'nullable',
                //'image|mimes:jpeg,png,jpg'
            ],
        ];
    }
}
