<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewslatterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('newslatter', 'email')->whereNull('deleted_at'),
            ],
            'phone'      => 'nullable|string|max:15',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'  => 'Email is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.unique'    => 'This email is already registered.',
            'phone.string'    => 'Phone must be a valid string.',
            'phone.max'       => 'Phone cannot exceed 15 characters.',
        ];
    }
}
