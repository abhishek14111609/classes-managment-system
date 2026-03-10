<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isSchoolAdmin() || $this->user()->isTeacher();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'batch_id' => ['required', Rule::exists('batches', 'id')->where('school_id', $this->user()->school_id)],
            'attendance_date' => ['required', 'date'],
            'attendances' => ['required', 'array'],
            'attendances.*.student_id' => ['required', Rule::exists('students', 'id')->where('school_id', $this->user()->school_id)],
            'attendances.*.status' => ['required', 'in:present,absent,late,excused'],
            'attendances.*.remarks' => ['nullable', 'string'],
        ];
    }
}
