<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id;
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($id)],
            'sku' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:category,id'],
            'sub_category_id' => ['nullable', 'exists:sub_category,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string'],
            'visibility' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'],
            'stock_status' => ['nullable', 'string'],
            'allow_backorders' => ['nullable'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable'], // Could be file or string/URL if not changed
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'slug.required' => 'Product slug is required.',
            'slug.unique' => 'Product slug must be unique.',
            'price.numeric' => 'Product price must be a number.',
            'stock_quantity.numeric' => 'Stock quantity must be a number.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
