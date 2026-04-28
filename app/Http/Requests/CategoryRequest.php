<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name'   => ['required', 'string', 'max:100', Rule::unique('category', 'category_name')->ignore($this->id)->whereNull('deleted_at'),],
            'category_image' => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'category_icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'Category name is required.',
            'category_image.required' => 'Category image is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
