<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:5'],
            'confirm_password' => ['required', 'same:new_password']
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Current password is required',
            'new_password.required' => 'New password is required',
            'new_password.min' => 'New password must be at least 6 characters',
            'confirm_password.required' => 'Confirm password is required',
            'confirm_password.same' => 'Confirm password is not the same as new password',
        ];
    }
}
