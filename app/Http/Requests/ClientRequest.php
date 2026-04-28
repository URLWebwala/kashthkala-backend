<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email,' . $this->id,
            'weburl' => 'required|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'client_name.required'   => 'Name is required.',
            'client_name.string'     => 'Name must be a valid string.',
            'client_name.max'        => 'Name cannot exceed 255 characters.',
            'email.required'  => 'Email is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.unique'    => 'This email is already registered.',
            'weburl.required' => 'Website URL is required.',
            'weburl.url'      => 'Please enter a valid website URL.',
            'weburl.max'      => 'Website URL cannot exceed 255 characters.',
        ];
    }
}
