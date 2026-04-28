<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BrandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_logo'   => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'],
            'website_favicon'   => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'],
            'meta_favicon'   => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'],
            'app_favicon'   => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'],
        ];
    }

    public function messages(): array
    {
        return [
            'website_logo.image'     => 'Website logo must be an image.',
            'website_logo.mimes'        => 'Website logo must be a valid image file (jpeg, png, jpg, gif).',
            'website_logo.max'        => 'Website logo cannot exceed 2MB.',
            'website_favicon.image'     => 'Website favicon must be an image.',
            'website_favicon.mimes'        => 'Website favicon must be a valid image file (jpeg, png, jpg, gif).',
            'website_favicon.max'        => 'Website favicon cannot exceed 1MB.',
            'meta_favicon.image'     => 'Meta favicon must be an image.',
            'meta_favicon.mimes'        => 'Meta favicon must be a valid image file (jpeg, png, jpg, gif).',
            'meta_favicon.max'        => 'Meta favicon cannot exceed 1MB.',
            'app_favicon.image'     => 'App favicon must be an image.',
            'app_favicon.mimes'        => 'App favicon must be a valid image file (jpeg, png, jpg, gif).',
            'app_favicon.max'        => 'App favicon cannot exceed 1MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
