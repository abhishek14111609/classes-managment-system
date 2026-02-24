<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $title
 * @property string|null $description
 * @property string $event_date
 * @property string|null $location
 * @property int|null $coach_id
 * @property string $status
 * @property array|null $participants
 */
class StoreSportsEventRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date', 'after:now'],
            'location' => ['nullable', 'string', 'max:255'],
            'sport_level' => ['nullable', 'string', 'max:255'],
            'coach_id' => ['nullable', 'exists:teachers,id'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
            'participants' => ['nullable', 'array'],
            'participants.*' => ['exists:students,id'],
        ];
    }
}
