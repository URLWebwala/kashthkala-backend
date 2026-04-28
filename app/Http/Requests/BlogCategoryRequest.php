<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class BlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_category', 'category_name')->ignore($this->id)->whereNull('deleted_at'),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_category', 'slug')->ignore($this->id)->whereNull('deleted_at'),
            ],
            'image' => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'icon'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required'   => 'Name is required.',
            'category_name.string'     => 'Name must be a valid string.',
            'category_name.max'        => 'Name cannot exceed 255 characters.',
            'category_name.unique'     => 'Name must be unique.',
            'slug.required'            => 'Slug is required.',
            'slug.unique'              => 'Slug must be unique.',
            'image.required'           => 'The image is required.',
            'image.image'              => 'The file must be an image.',
            'image.mimes'              => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max'                => 'The image may not be greater than 2048 kilobytes.',
            'icon.image'               => 'The file must be an image.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
