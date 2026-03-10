<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isSchoolAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isSport = auth()->user()->school->institute_type === 'sport';

        return [
            'class_id' => [$isSport ? 'nullable' : 'required', Rule::exists('classes', 'id')->where('school_id', $this->user()->school_id)],
            'course_id' => [$isSport ? 'required' : 'nullable', Rule::exists('courses', 'id')->where('school_id', $this->user()->school_id)],
            'subject_id' => ['required', Rule::exists('subjects', 'id')->where('school_id', $this->user()->school_id)],
            'name' => [$isSport ? 'nullable' : 'required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'capacity' => ['required', 'integer', 'min:1'],
            'sport_level' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'teacher_ids' => ['nullable', 'array'],
            'teacher_ids.*' => [Rule::exists('teachers', 'id')->where('school_id', $this->user()->school_id)],
        ];
    }
}
