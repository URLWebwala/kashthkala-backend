<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:50'],
            'link'   => ['nullable', 'string'],
            'description'   => ['nullable', 'string'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Product name is required.',
            'name.string'     => 'Product name must be a valid string.',
            'name.max'        => 'Product name cannot exceed 50 characters.',
            'link.string'   => 'Product link must be a valid string.',
            'description.string'   => 'Product description must be a valid string.',
            'image.image' => 'Product image must be a valid image.',
            'image.mimes' => 'Product image must be a valid file type.',
            'image.max' => 'Product image size cannot exceed 2MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
