<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:schools,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'status' => ['nullable', 'in:active,inactive'],
            'institute_type' => ['required', 'in:academic,sport'],
            'plan_id' => ['required', 'exists:plans,id'],
            'subscription_duration' => ['required', 'integer', 'min:1'],
            'admin_name' => ['nullable', 'string', 'max:255'],
            'admin_email' => ['nullable', 'email', 'unique:users,email'],
            'admin_username' => ['nullable', 'string', 'unique:users,username'],
            'admin_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
