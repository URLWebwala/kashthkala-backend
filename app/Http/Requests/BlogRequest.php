<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
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
                Rule::unique('blog', 'title')->ignore($this->id)->whereNull('deleted_at'),
            ],
            'content' => 'required|string',
            'author_name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog', 'slug')->ignore($this->id)->whereNull('deleted_at'),
            ],
            'blog_category_id' => 'required|exists:blog_category,id',
            'visibility' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'image' => [$this->id ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Title is required.',
            'title.string'     => 'Title must be a valid string.',
            'title.max'        => 'Title cannot exceed 255 characters.',
            'title.unique'     => 'Title must be unique.',
            'content.required' => 'Content is required.',
            'content.string'   => 'Content must be a valid string.',
            'author_name.required'   => 'Author name is required.',
            'author_name.string'     => 'Author name must be a valid string.',
            'author_name.max'        => 'Author name cannot exceed 255 characters.',
            'slug.required'   => 'Slug is required.',
            'slug.string'     => 'Slug must be a valid string.',
            'slug.max'        => 'Slug cannot exceed 255 characters.',
            'slug.unique'     => 'Slug must be unique.',
            'blog_category_id.required'     => 'Blog category is required.',
            'blog_category_id.exists'     => 'Blog category does not exist.',
            'image.required'          => 'The featured image is required.',
            'image.image'             => 'The file must be an image.',
            'image.mimes'             => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'image.max'               => 'The image may not be greater than 4096 kilobytes.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
