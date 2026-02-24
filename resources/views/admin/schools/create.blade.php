@extends('layouts.app')

@section('title', 'Institutional Node Activation')
@section('hide_header', true)
@section('custom_sidebar_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Structural Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 fade-in">
            <div class="mb-3 mb-md-0">
                <a href="{{ route('admin.schools.index') }}" class="btn btn-link text-decoration-none p-0 mb-2 text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Return to Registry
                </a>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Institutional Hub Activation</h2>
                <p class="text-muted mb-0">Construct a new institutional node and initialize administrative credentials.</p>
            </div>
            <div>
                <span class="badge bg-white border px-3 py-2 text-muted shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-shield-plus text-primary"></i> Secure Deployment Mode
                </span>
            </div>
        </div>

        <form action="{{ route('admin.schools.store') }}" method="POST" enctype="multipart/form-data" class="fade-in" style="animation-delay: 0.1s;">
            @csrf

            <div class="row g-4">
                <!-- Administrative Configuration -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold text-dark mb-0">Primary Identity & Locality</h5>
                            <p class="text-muted tiny mb-0">Fundamental institutional parameters.</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Legal institutional Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-building text-primary"></i></span>
                                        <input type="text" name="name" class="form-control bg-light border-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Springfield Academy" required>
                                    </div>
                                    @error('name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Core Communication Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-envelope-at text-primary"></i></span>
                                        <input type="email" name="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="admin@ Springfield.edu" required>
                                    </div>
                                    @error('email')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Institutional Line</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-telephone text-primary"></i></span>
                                        <input type="text" name="phone" class="form-control bg-light border-0 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                                    </div>
                                    @error('phone')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Type of Institute</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-building text-primary"></i></span>
                                        <select name="institute_type" class="form-select bg-light border-0 @error('institute_type') is-invalid @enderror" required>
                                            <option value="academic" {{ old('institute_type') === 'academic' ? 'selected' : '' }}>Academic</option>
                                            <option value="sport" {{ old('institute_type') === 'sport' ? 'selected' : '' }}>Sport</option>
                                        </select>
                                    </div>
                                    @error('institute_type')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Headquarters Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 align-items-start pt-2"><i class="bi bi-geo-alt text-primary"></i></span>
                                        <textarea name="address" class="form-control bg-light border-0 @error('address') is-invalid @enderror" rows="3" placeholder="Full physical locality...">{{ old('address') }}</textarea>
                                    </div>
                                    @error('address')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold text-dark mb-0">Administrative Custodian Setup</h5>
                            <p class="text-muted tiny mb-0">Initialize the primary executive credential for this node.</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Executive Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-person-badge text-info"></i></span>
                                        <input type="text" name="admin_name" class="form-control bg-light border-0 @error('admin_name') is-invalid @enderror" value="{{ old('admin_name') }}" placeholder="Full Operational Name">
                                    </div>
                                    @error('admin_name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Executive Alias / Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-fingerprint text-info"></i></span>
                                        <input type="text" name="admin_username" class="form-control bg-light border-0 @error('admin_username') is-invalid @enderror" value="{{ old('admin_username') }}" placeholder="Unique alias...">
                                    </div>
                                    @error('admin_username')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Secured Password Access</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-lock-fill text-info"></i></span>
                                        <input type="password" name="admin_password" class="form-control bg-light border-0 @error('admin_password') is-invalid @enderror" placeholder="••••••••">
                                    </div>
                                    @error('admin_password')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Re-validate Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-shield-lock text-info"></i></span>
                                        <input type="password" name="admin_password_confirmation" class="form-control bg-light border-0" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strategic Placement -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 text-center">
                            <h5 class="fw-bold text-dark mb-0">Institutional Brand</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-inner" style="width: 120px; height: 120px; border: 4px dashed rgba(79, 70, 229, 0.2);">
                                    <i class="bi bi-cloud-arrow-up text-primary fs-1"></i>
                                </div>
                                <p class="text-muted tiny fw-bold text-uppercase mb-0">Branding Vector</p>
                            </div>
                            <input type="file" name="logo" class="form-control bg-light border-0 tiny @error('logo') is-invalid @enderror" accept="image/*">
                            @error('logo')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-primary bg-opacity-20 p-3 rounded-4 me-3">
                                    <i class="bi bi-rocket-takeoff-fill fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Service Tier</h5>
                                    <p class="text-white-50 tiny mb-0 text-uppercase">Commercial Commitment</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-white-50 small fw-bold text-uppercase">Select Strategic Plan</label>
                                <select name="plan_id" class="form-select bg-white bg-opacity-10 border-0 text-white shadow-none @error('plan_id') is-invalid @enderror" required>
                                    <option value="" class="text-dark">-- Commercial Tier --</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" class="text-dark" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} (₹{{ number_format($plan->price) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')<div class="invalid-feedback d-block mt-1 text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-white-50 small fw-bold text-uppercase">Initial Duration (Days)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white bg-opacity-10 border-0 text-white"><i class="bi bi-calendar-check"></i></span>
                                    <input type="number" name="subscription_duration" class="form-control bg-white bg-opacity-10 border-0 text-white shadow-none" value="{{ old('subscription_duration', 30) }}" required>
                                </div>
                            </div>

                            <div class="p-3 bg-white bg-opacity-5 rounded-4 mb-4 border border-white border-opacity-10">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                    <span class="small fw-bold">Pre-deployment Notice</span>
                                </div>
                                <p class="text-white-50 tiny mb-0">Initiating this node will trigger automated environment isolation and executive credential hashing.</p>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm py-3 mb-2">
                                <i class="bi bi-lightning-charge-fill me-2"></i> Execute Node Activation
                            </button>
                            <a href="{{ route('admin.schools.index') }}" class="btn btn-link text-white-50 text-decoration-none w-100 tiny">Abort Deployment</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeIn {
            to { opacity: 1; transform: translateY(0); }
        }
        .form-control:focus, .form-select:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }
    </style>
@endsection