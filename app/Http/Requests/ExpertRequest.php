<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ExpertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $imageRule = $this->id ? 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        return [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => $imageRule,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Name is required.',
            'name.string'     => 'Name must be a valid string.',
            'name.max'        => 'Name cannot exceed 255 characters.',
            'description.string'   => 'Description must be a valid string.',
            'image.image'      => 'The file must be an image.',
            'image.mimes'      => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max'        => 'The image may not be greater than 2048 kilobytes.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
