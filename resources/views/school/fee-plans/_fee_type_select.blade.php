{{--
Shared fee-type form row partial.
Renders TWO separate selects: Category (fee_type) and Duration.
Usage:
@include('school.fee-plans._fee_type_select', [
'selectedType' => old('fee_type', $feePlan->fee_type ?? ''),
'selectedDuration' => old('duration', $feePlan->duration ?? ''),
])
Requires toggleSportLevel() JS function on the host page.
--}}

@php
    $categories = [
        'tuition' => 'Tuition',
        'sports' => 'Sports',
        'transport' => 'Transport',
        'exam' => 'Exam',
        'library' => 'Library',
        'other' => 'Other',
    ];

    $durations = [
        'monthly' => 'Monthly',
        'quarterly' => 'Quarterly (3 Months)',
        'half_yearly' => 'Half Yearly (6 Months)',
        'annual' => 'Annual / Yearly',
        'one_time' => 'One-Time Payment',
    ];

    $selType = $selectedType ?? '';
    $selDuration = $selectedDuration ?? '';
@endphp

{{-- Row: Category + Duration side by side --}}
<div class="row g-3">

    {{-- Category --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Category <span class="text-danger">*</span>
            <span class="badge bg-primary-subtle text-primary ms-1" style="font-size:.7rem;">WHAT</span>
        </label>
        <select class="form-select {{ $errors->has('fee_type') ? 'is-invalid' : '' }}" id="fee_type" name="fee_type"
            required onchange="toggleSportLevel(this.value)">
            <option value="">— Select Category —</option>
            @foreach($categories as $value => $label)
                <option value="{{ $value }}" {{ $selType === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('fee_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">What kind of fee is this?</div>
    </div>

    {{-- Duration --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Duration
            <span class="badge bg-success-subtle text-success ms-1" style="font-size:.7rem;">HOW OFTEN</span>
        </label>
        <select class="form-select {{ $errors->has('duration') ? 'is-invalid' : '' }}" id="duration" name="duration">
            <option value="">— Select Duration —</option>
            @foreach($durations as $value => $label)
                <option value="{{ $value }}" {{ $selDuration === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('duration')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">How often is this fee charged?</div>
    </div>

</div>