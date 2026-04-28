<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class HeroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'main_title' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'primary_button_text' => ['nullable', 'string', 'max:255'],
            'primary_link' => ['nullable', 'string', 'max:255'],
            'secondary_button_text' => ['nullable', 'string', 'max:255'],
            'secondary_link' => ['nullable', 'string', 'max:255'],
            'image' => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'display_order' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'main_title.required' => 'Main title is required.',
            'image.required' => 'Hero image is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
