<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class EmailTemaplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('email_templates', 'name')->ignore($this->id)->whereNull('deleted_at'),
            ],
            'from_email' => ['nullable', 'email'],
            'subject' => ['required', 'string'],
            'body' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Template name is required.',
            'name.string'     => 'Template name must be a valid string.',
            'name.max'        => 'Template name cannot exceed 50 characters.',
            'name.unique'     => 'Template name must be unique.',
            'from_email.email'   => 'From email must be a valid email address.',
            'subject.required'   => 'Subject is required.',
            'subject.string'   => 'Subject must be a valid string.',
            'body.required'   => 'Body is required.',
            'body.string'   => 'Body must be a valid string.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
