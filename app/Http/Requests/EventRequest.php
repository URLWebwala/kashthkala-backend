<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'caption' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Title is required.',
            'title.string'     => 'Title must be a valid string.',
            'title.max'        => 'Title cannot exceed 255 characters.',
            'title.unique'     => 'Title must be unique.',
            'caption.required' => 'Caption is required.',
            'caption.string'   => 'Caption must be a valid string.',
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
