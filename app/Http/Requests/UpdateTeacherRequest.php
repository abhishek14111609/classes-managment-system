<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $employee_id
 * @property string|null $qualification
 * @property string|null $specialization
 * @property float|null $salary
 * @property bool|null $is_active
 * @property array|null $batches
 */
class UpdateTeacherRequest extends FormRequest
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
        $teacher = $this->route('teacher');
        $userId = $teacher->user_id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'batches' => ['nullable', 'array'],
            'batches.*' => ['exists:batches,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
