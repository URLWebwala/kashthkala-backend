<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('portfolio', 'title')->ignore($this->id),
            ],
            'description' => 'nullable|string',
            'portfolio_category_id' => 'nullable|exists:portfolio_category,id',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Title is required.',
            'title.string'     => 'Title must be a valid string.',
            'title.max'        => 'Title cannot exceed 255 characters.',
            'title.unique'     => 'Title must be unique.',
            'description.required' => 'Description is required.',
            'description.string'   => 'Description must be a valid string.',
            'portfolio_category_id.exists'     => 'Portfolio category does not exist.',
            'image.image'             => 'The file must be an image.',
            'image.mimes'             => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max'               => 'The image may not be greater than 2048 kilobytes.',
            'icon.image'              => 'The file must be an image.',
            'icon.mimes'              => 'The icon must be a file of type: jpeg, png, jpg, gif, svg.',
            'icon.max'                => 'The icon may not be greater than 2048 kilobytes.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
