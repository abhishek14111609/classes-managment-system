@extends('layouts.app')

@section('title', 'Architect Commercial Tier')
@section('hide_header', true)
@section('custom_sidebar_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Strategic Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 fade-in">
            <div class="mb-3 mb-md-0">
                <a href="{{ route('admin.plans.index') }}" class="btn btn-link text-decoration-none p-0 mb-2 text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Back to Architecture
                </a>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Architect Commercial Tier</h2>
                <p class="text-muted mb-0">Define the specifications and operational boundaries for a new subscription plan.</p>
            </div>
            <div>
                <span class="badge bg-white border px-3 py-2 text-muted shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-rocket-takeoff text-primary"></i> Tier Construction Mode
                </span>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <form action="{{ route('admin.plans.store') }}" method="POST" class="fade-in" style="animation-delay: 0.1s;">
                    @csrf

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold text-dark mb-0">Functional Identity</h5>
                            <p class="text-muted tiny mb-0">Naming and descriptive parameters for institutional recognition.</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Plan Designation</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-tag-fill text-primary"></i></span>
                                        <input type="text" name="name" class="form-control bg-light border-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Enterprise Elite" required>
                                    </div>
                                    @error('name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Strategic Value Description</label>
                                    <textarea name="description" class="form-control bg-light border-0 @error('description') is-invalid @enderror" rows="3" placeholder="Highlight key institutional benefits and limitations...">{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                                    <h5 class="fw-bold text-dark mb-0">Financial Metrics</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-muted">Access Price (₹)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-currency-rupee text-success"></i></span>
                                            <input type="number" step="0.01" name="price" class="form-control bg-light border-0 @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="0.00" required>
                                        </div>
                                        @error('price')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-uppercase text-muted">Activation Cycle (Days)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-calendar-range text-info"></i></span>
                                            <input type="number" name="duration_days" class="form-control bg-light border-0 @error('duration_days') is-invalid @enderror" value="{{ old('duration_days') }}" placeholder="30" required>
                                        </div>
                                        @error('duration_days')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                                    <h5 class="fw-bold text-dark mb-0">Operational Quotas</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-muted">Student Load Capacity</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-people-fill text-primary"></i></span>
                                            <input type="number" name="student_limit" class="form-control bg-light border-0 @error('student_limit') is-invalid @enderror" value="{{ old('student_limit') }}" placeholder="500" required>
                                        </div>
                                        @error('student_limit')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-uppercase text-muted">Batch / Faculty Slots</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-briefcase-fill text-warning"></i></span>
                                            <input type="number" name="batch_limit" class="form-control bg-light border-0 @error('batch_limit') is-invalid @enderror" value="{{ old('batch_limit') }}" placeholder="20" required>
                                        </div>
                                        @error('batch_limit')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-5">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-{{ old('is_active', true) ? 'success' : 'secondary' }} bg-opacity-10 p-3 rounded-4 me-3" id="status-icon-bg">
                                        <i class="bi bi-power fs-3 text-{{ old('is_active', true) ? 'success' : 'secondary' }}" id="status-icon"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">Market Availability</h6>
                                        <p class="text-muted tiny mb-0">Toggle whether this tier is immediately available for institutional acquisition.</p>
                                    </div>
                                </div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} onchange="toggleStatusUI(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm px-5 py-3 flex-grow-1">
                            <i class="bi bi-plus-lg me-2"></i> Commit Architecture
                        </button>
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-light btn-lg rounded-pill fw-bold px-4 py-3 border">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleStatusUI(checkbox) {
            const bg = document.getElementById('status-icon-bg');
            const icon = document.getElementById('status-icon');
            if(checkbox.checked) {
                bg.classList.remove('bg-secondary');
                bg.classList.add('bg-success');
                icon.classList.remove('text-secondary');
                icon.classList.add('text-success');
            } else {
                bg.classList.remove('bg-success');
                bg.classList.add('bg-secondary');
                icon.classList.remove('text-success');
                icon.classList.add('text-secondary');
            }
        }
    </script>

    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeIn {
            to { opacity: 1; transform: translateY(0); }
        }
        .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }
        .tiny { font-size: 0.75rem; }
    </style>
@endsection