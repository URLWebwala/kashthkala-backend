<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'  => 'nullable|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:category,id',
            'service_id' => 'nullable|exists:services,id',
            'city'  => 'nullable|string|max:255',
            'state'  => 'nullable|string|max:255',
            'country'  => 'nullable|string|max:255',
            'enquiry'  => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'type' => 'nullable|string|in:contact,enquiry',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'   => 'Name is required.',
            'first_name.string'     => 'Name must be a valid string.',
            'first_name.max'        => 'Name cannot exceed 255 characters.',
            'email.required'  => 'Email is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.unique'    => 'This email is already registered.',
            'message.string'    => 'Message is required.',
        ];
    }
}
