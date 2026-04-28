<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SocialMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'whatsapp_logo'   => ['nullable', 'string'],
            'facebook_logo'   => ['nullable', 'string'],
            'twitter_logo'   => ['nullable', 'string'],
            'instagram_logo'   => ['nullable', 'string'],
            'linkedin_logo'   => ['nullable', 'string'],
            'youtube_logo'   => ['nullable', 'string'],
            'whatsapp_url'   => ['required', 'string', 'url'],
            'facebook_url'   => ['required', 'string', 'url'],
            'twitter_url'   => ['required', 'string', 'url'],
            'instagram_url'   => ['required', 'string', 'url'],
            'linkedin_url'   => ['required', 'string', 'url'],
            'youtube_url'   => ['required', 'string', 'url'],
            'mobile'   => ['required', 'string', 'max:15'],
            'email'   => ['required', 'string', 'email'],
            'address'   => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp_url.url'     => 'WhatsApp URL must be a valid URL.',
            'facebook_url.url'     => 'Facebook URL must be a valid URL.',
            'twitter_url.url'     => 'Twitter URL must be a valid URL.',
            'instagram_url.url'     => 'Instagram URL must be a valid URL.',
            'linkedin_url.url'     => 'LinkedIn URL must be a valid URL.',
            'youtube_url.url'     => 'YouTube URL must be a valid URL.',
            'mobile.required'     => 'Mobile number is required.',
            'mobile.max'     => 'Mobile number must not exceed 15 characters.',
            'email.required'     => 'Email is required.',
            'email.email'     => 'Email must be a valid email address.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
