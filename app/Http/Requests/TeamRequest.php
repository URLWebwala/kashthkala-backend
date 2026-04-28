<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'facebook_link' => ['nullable', 'string', 'max:255'],
            'twitter_link' => ['nullable', 'string', 'max:255'],
            'linkedin_link' => ['nullable', 'string', 'max:255'],
            'instagram_link' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Name is required.',
            'name.string'     => 'Name must be a valid string.',
            'name.max'        => 'Name cannot exceed 255 characters.',
            'role.required'   => 'Role is required.',
            'role.string'     => 'Role must be a valid string.',
            'role.max'        => 'Role cannot exceed 255 characters.',
            'image.image'             => 'The file must be an image.',
            'image.mimes'             => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max'               => 'The image may not be greater than 2048 kilobytes.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
