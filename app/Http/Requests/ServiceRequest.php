<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $serviceId = $this->id ?? null;

        return [
            'service_title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services', 'service_title')->ignore($serviceId)->whereNull('deleted_at'),
            ],
            'icon' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'service_title.required' => 'Service title is required',
            'service_title.unique'   => 'Service title already exists',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
