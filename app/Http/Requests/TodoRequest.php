<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['nullable'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.string'   => 'Title must be a valid string.',
            'title.max'      => 'Title cannot exceed 255 characters.',
            'description.string' => 'Description must be a valid text.',
            'priority.required' => 'Priority is required.',
            'priority.in'       => 'Priority must be low, medium, or high.',
            'due_date.date' => 'Due date must be a valid date.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
