@extends('layouts.app')

@section('title', 'Create Fee Plan')
@section('sidebar') @include('school.sidebar') @endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Create Fee Plan</h2>
            <p class="text-muted mb-0">Define a reusable fee template for students.</p>
        </div>
        <a href="{{ route('school.fee-plans.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('school.fee-plans.store') }}" method="POST">
                        @csrf

                        {{-- Plan Name --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Plan Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   placeholder="e.g. Monthly Tuition, Annual Sports Fee…"
                                   required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Divider --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <hr class="grow m-0">
                            <span class="text-muted small fw-semibold text-uppercase">Fee Classification</span>
                            <hr class="grow m-0">
                        </div>

                        {{-- Category + Duration (shared partial) --}}
                        <div class="mb-3">
                            @include('school.fee-plans._fee_type_select', [
                                'selectedType'     => old('fee_type', ''),
                                'selectedDuration' => old('duration', ''),
                            ])
                        </div>

                        {{-- Sport Level (conditionally shown) --}}
                        <div class="mb-4" id="sportLevelWrap" style="display:none;">
                            <label for="sport_level" class="form-label fw-semibold">Sport Level</label>
                            <select class="form-select" id="sport_level" name="sport_level">
                                <option value="">— Not Applicable —</option>
                                <option value="basic"    {{ old('sport_level') === 'basic'    ? 'selected' : '' }}>Basic</option>
                                <option value="advanced" {{ old('sport_level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                            <div class="form-text">Select the level for sports fees.</div>
                        </div>

                        {{-- Divider --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <hr class="grow m-0">
                            <span class="text-muted small fw-semibold text-uppercase">Amounts</span>
                            <hr class="grow m-0">
                        </div>

                        {{-- Amount + Late Fee --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="amount" class="form-label fw-semibold">
                                    Plan Amount (₹) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           id="amount" name="amount"
                                           value="{{ old('amount') }}"
                                           step="0.01" min="1" required placeholder="0.00">
                                </div>
                                @error('amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="late_fee_per_day" class="form-label fw-semibold">
                                    Late Fee / Day (₹)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" class="form-control"
                                           id="late_fee_per_day" name="late_fee_per_day"
                                           value="{{ old('late_fee_per_day', 0) }}"
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="form-text">Charged per day after the due date. Set 0 to disable.</div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="2" placeholder="Optional notes…">{{ old('description') }}</textarea>
                        </div>

                        {{-- Active toggle --}}
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox"
                                       id="is_active" name="is_active" value="1"
                                       {{ old('is_active', '1') ? 'checked' : '' }} role="switch">
                                <label class="form-check-label fw-semibold" for="is_active">
                                    Active
                                    <span class="text-muted fw-normal small ms-1">
                                        — visible when assigning fees to students
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-save me-1"></i> Save Plan
                            </button>
                            <a href="{{ route('school.fee-plans.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSportLevel(type) {
    const wrap = document.getElementById('sportLevelWrap');
    if (wrap) {
        wrap.style.display = type === 'sports' ? 'block' : 'none';
        if (type !== 'sports') document.getElementById('sport_level').value = '';
    }
}
// Run on load in case of old() repopulation
toggleSportLevel(document.getElementById('fee_type').value);
</script>
@endsection