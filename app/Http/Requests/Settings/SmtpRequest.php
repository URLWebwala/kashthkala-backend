<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SmtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'host'   => ['required', 'string', 'max:50'],
            'port'   => ['nullable', 'integer'],
            'username'   => ['nullable', 'string'],
            'password'   => ['nullable', 'string'],
            'encryption'   => ['nullable', 'string'],
            'from_address'   => ['nullable', 'email'],
            'from_name'   => ['nullable', 'string'],
            'reply_to_address'   => ['nullable', 'email'],
            'reply_to_name'   => ['nullable', 'string'],
            'cc_address'   => ['nullable', 'string'],
            'bcc_address'   => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'host.required'   => 'Host is required.',
            'host.string'     => 'Host must be a valid string.',
            'host.max'        => 'Host cannot exceed 50 characters.',
            'port.integer'   => 'Port must be an integer.',
            'username.string'   => 'Username must be a valid string.',
            'password.string'   => 'Password must be a valid string.',
            'encryption.string'   => 'Encryption must be a valid string.',
            'from_address.email'   => 'From address must be a valid email address.',
            'from_name.string'   => 'From name must be a valid string.',
            'reply_to_address.email'   => 'Reply to address must be a valid email address.',
            'reply_to_name.string'   => 'Reply to name must be a valid string.',
            'cc_address.string'   => 'CC address must be a valid string.',
            'bcc_address.string'   => 'BCC address must be a valid string.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
