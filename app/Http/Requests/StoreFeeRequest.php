<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeRequest extends FormRequest
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
            'fee_plan_id' => ['nullable', 'exists:fee_plans,id'],
            'student_id' => ['required', 'exists:students,id'],
            'batch_id' => ['nullable', 'exists:batches,id'],
            'fee_type' => ['required', 'string'],
            'duration' => ['nullable', 'string', 'in:monthly,quarterly,half_yearly,annual,one_time'],
            'sport_level' => ['nullable', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'lte:total_amount'],
            'late_fee' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
            'remarks' => ['nullable', 'string'],
            // Initial payment fields
            'initial_paid_amount' => ['nullable', 'numeric', 'min:0', 'lte:total_amount'],
            'payment_method' => ['required_with:initial_paid_amount', 'string', 'in:cash,bank_transfer,card,cheque,upi'],
            'transaction_id' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'student_id' => 'student',
            'fee_type' => 'fee type',
            'sport_level' => 'sports level',
            'total_amount' => 'total amount',
            'due_date' => 'due date',
        ];
    }
}
