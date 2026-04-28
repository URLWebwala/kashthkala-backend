<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // For update case (route model binding or request id)
        $userId = $this->route('user') ?? $this->id ?? null;

        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $userId,
            'phone'      => 'nullable|string|max:15',
            'status'     => 'nullable|in:0,1,true,false',
            'user_type'  => 'nullable|integer',

            'password'              => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'nullable|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Name is required.',
            'name.string'     => 'Name must be a valid string.',
            'name.max'        => 'Name cannot exceed 255 characters.',

            'email.required'  => 'Email is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.unique'    => 'This email is already registered.',

            'phone.string'    => 'Phone must be a valid string.',
            'phone.max'       => 'Phone cannot exceed 15 characters.',

            'password.min'        => 'Password must be at least 6 characters.',
            'password.confirmed'  => 'Password and confirm password do not match.',

            'password_confirmation.min' => 'Confirm password must be at least 6 characters.',
        ];
    }
}
