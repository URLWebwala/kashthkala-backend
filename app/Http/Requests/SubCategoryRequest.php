<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Lib\Api;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'   => ['required', 'integer', 'exists:category,id'],
            'subcategory_name'   => ['required', 'string', 'max:100', Rule::unique('sub_category', 'subcategory_name')->ignore($this->id)->whereNull('deleted_at'),],
            'subcategory_image' => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], // if update time is required then make it nullable
            'subcategory_icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category ID is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'subcategory_name.required' => 'Sub Category name is required.',
            'sub_category_image.required'      => 'Sub Category image is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())  
        );
    }
}
