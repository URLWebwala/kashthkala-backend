<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class CareerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_title' => 'required|string|max:255',
            'job_slug' => ['nullable', 'string', 'max:255', Rule::unique('career', 'job_slug')->ignore($this->input('id')),],
            'department' => 'required|string|max:255',
            'openings' => 'required|integer|min:1',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:10192',
            'job_type' => 'required|in:full-time,part-time,internship,contract',
            'work_mode' => 'required|in:onsite,remote,hybrid',
            'location' => 'required|string|max:255',
            'min_experience' => 'required|integer|min:0',
            'max_experience' => 'required|integer|gte:min_experience',
            'salary_type' => 'nullable|in:fixed,range,not_disclosed',
            'min_salary' => 'nullable|integer|min:0',
            'max_salary' => 'nullable|integer|gte:min_salary',
            'short_description' => 'required|string',
            'full_description' => 'required|string',
            'responsibilities' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'skills' => 'required|string',
            'start_date' => 'nullable|date',
            'last_date' => 'required|date|after_or_equal:start_date',
            'job_status' => 'required|in:active,inactive,closed',
            'featured' => 'nullable|boolean',
            'show_homepage' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'application_type' => 'required|in:internal,external',
            'apply_url' => 'nullable|required_if:application_type,external|url',
            'job_pdf' => 'nullable|file|mimes:pdf|max:10192',
            'allow_multiple' => 'nullable|boolean',
            'email_notification' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'job_title.required' => 'Job title is required.',
            'department.required' => 'Department is required.',
            'openings.required' => 'Number of openings is required.',
            'company_name.required' => 'Company name is required.',
            'job_type.required' => 'Job type is required.',
            'work_mode.required' => 'Work mode is required.',
            'location.required' => 'Location is required.',
            'min_experience.required' => 'Minimum experience is required.',
            'max_experience.required' => 'Maximum experience is required.',
            'max_experience.gte' => 'Max experience must be greater than or equal to min experience.',
            'short_description.required' => 'Short description is required.',
            'full_description.required' => 'Full description is required.',
            'responsibilities.required' => 'Responsibilities are required.',
            'requirements.required' => 'Requirements are required.',
            'skills.required' => 'Skills are required.',
            'last_date.required' => 'Last date is required.',
            'last_date.after_or_equal' => 'Last date must be after start date.',
            'job_status.required' => 'Job status is required.',
            'application_type.required' => 'Application type is required.',
            'apply_url.required_if' => 'Apply URL is required for external application.',
            'apply_url.url' => 'Apply URL must be a valid URL.',
            'company_logo.image' => 'Company logo must be an image.',
            'job_pdf.mimes' => 'Job PDF must be a PDF file.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors())
        );
    }
}
