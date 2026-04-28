<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestinomialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'designation' => 'nullable|string|max:255',
            'states' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'client_name.required' => 'Client name is required.',
            'rating.required' => 'Rating is required.',
            'rating.numeric' => 'Rating must be number.',
        ];
    }
}
