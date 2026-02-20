@extends('layouts.app')

@section('title', 'Edit Fee Plan')
@section('sidebar') @include('school.sidebar') @endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Fee Plan</h2>
            <p class="text-muted mb-0">Updating: <strong>{{ $feePlan->name }}</strong></p>
        </div>
        <a href="{{ route('school.fee-plans.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">

            @if($feePlan->fees_count > 0)
            <div class="alert alert-warning border-0 rounded-3 mb-4 d-flex gap-3 align-items-start">
                <i class="bi bi-info-circle-fill fs-5 mt-1"></i>
                <div>
                    <strong>{{ $feePlan->fees_count }} fee(s)</strong> already assigned from this plan.
                    Amount changes only affect <em>new</em> assignments — existing fees are unchanged.
                </div>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('school.fee-plans.update', $feePlan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Plan Name --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Plan Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name', $feePlan->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Section divider --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <hr class="grow m-0">
                            <span class="text-muted small fw-semibold text-uppercase">Fee Classification</span>
                            <hr class="grow m-0">
                        </div>

                        {{-- Category + Duration (shared partial) --}}
                        <div class="mb-3">
                            @include('school.fee-plans._fee_type_select', [
                                'selectedType'     => old('fee_type',  $feePlan->fee_type),
                                'selectedDuration' => old('duration',  $feePlan->duration),
                            ])
                        </div>

                        {{-- Sport Level (conditionally shown) --}}
                        <div class="mb-4" id="sportLevelWrap"
                             style="display:{{ old('fee_type', $feePlan->fee_type) === 'sports' ? 'block' : 'none' }};">
                            <label for="sport_level" class="form-label fw-semibold">Sport Level</label>
                            <select class="form-select" id="sport_level" name="sport_level">
                                <option value="">— Not Applicable —</option>
                                <option value="basic"    {{ old('sport_level', $feePlan->sport_level) === 'basic'    ? 'selected' : '' }}>Basic</option>
                                <option value="advanced" {{ old('sport_level', $feePlan->sport_level) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
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
                                           value="{{ old('amount', $feePlan->amount) }}"
                                           step="0.01" min="1" required>
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
                                           value="{{ old('late_fee_per_day', $feePlan->late_fee_per_day) }}"
                                           step="0.01" min="0">
                                </div>
                                <div class="form-text">Charged per day after due date. Set 0 to disable.</div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="2">{{ old('description', $feePlan->description) }}</textarea>
                        </div>

                        {{-- Active toggle --}}
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox"
                                       id="is_active" name="is_active" value="1"
                                       {{ old('is_active', $feePlan->is_active) ? 'checked' : '' }} role="switch">
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
                                <i class="bi bi-save me-1"></i> Update Plan
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
toggleSportLevel(document.getElementById('fee_type').value);
</script>
@endsection