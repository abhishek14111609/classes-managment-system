<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
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
        return [
            'course_id' => ['nullable', 'exists:courses,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:academic,sports'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
