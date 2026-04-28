<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class SeoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_name'   => ['required', 'string', 'max:50'],
            'meta_author' => ['nullable', 'string', 'max:100'],
            'page_title'  => ['nullable', 'string', 'max:191'],
            'meta_title'   => ['required'],
            'slug'         => ['nullable', Rule::unique('seo', 'slug')->ignore($this->id)],
            'canonical_url'=> ['nullable', 'url'],
        ];
    }

    public function messages(): array
    {
        return [
            'page_name.required'   => 'Page name is required.',
            'page_name.string'     => 'Page name must be a valid string.',
            'page_name.max'        => 'Page name cannot exceed 50 characters.',
            'meta_author.string'   => 'Meta author must be a valid string.',
            'meta_author.max'      => 'Meta author cannot exceed 100 characters.',
            'page_title.string'    => 'Page title must be a valid string.',
            'page_title.max'       => 'Page title cannot exceed 191 characters.',
            'meta_title.required' => 'Meta title is required.',
            'slug.unique'         => 'The slug has already been taken.',
            'canonical_url.url'   => 'The canonical URL must be a valid URL.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
