<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'class_id' => [$isSport ? 'nullable' : 'required', 'exists:classes,id'],
            'course_id' => [$isSport ? 'required' : 'nullable', 'exists:courses,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'name' => [$isSport ? 'nullable' : 'required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'capacity' => ['required', 'integer', 'min:1'],
            'sport_level' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'teacher_ids' => ['nullable', 'array'],
            'teacher_ids.*' => ['exists:teachers,id'],
        ];
    }
}
