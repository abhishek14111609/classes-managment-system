@extends('layouts.app')

@section('title', 'Refine Institutional Node - ' . $school->name)
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
                <a href="{{ route('admin.schools.index') }}" class="btn btn-link text-decoration-none p-0 mb-2 text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Return to Registry
                </a>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Refine Institutional Node</h2>
                <p class="text-muted mb-0">Synchronizing operational parameters and locality for <span class="fw-bold text-primary">{{ $school->name }}</span>.</p>
            </div>
            <div>
                <a href="{{ route('admin.schools.show', $school) }}" class="btn btn-white border shadow-sm px-4">
                    <i class="bi bi-eye me-2"></i> View Dossier
                </a>
            </div>
        </div>

        <form action="{{ route('admin.schools.update', $school) }}" method="POST" enctype="multipart/form-data" class="fade-in" style="animation-delay: 0.1s;">
            @csrf
            @method('PUT')

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
                                        <input type="text" name="name" class="form-control bg-light border-0 @error('name') is-invalid @enderror" value="{{ old('name', $school->name) }}" required>
                                    </div>
                                    @error('name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Core Communication Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-envelope-at text-primary"></i></span>
                                        <input type="email" name="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" value="{{ old('email', $school->email) }}" required>
                                    </div>
                                    @error('email')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Institutional Line</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-telephone text-primary"></i></span>
                                        <input type="text" name="phone" class="form-control bg-light border-0 @error('phone') is-invalid @enderror" value="{{ old('phone', $school->phone) }}">
                                    </div>
                                    @error('phone')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Operational Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-activity text-primary"></i></span>
                                        <select name="status" class="form-select bg-light border-0 @error('status') is-invalid @enderror" required>
                                            <option value="active" {{ old('status', $school->status) === 'active' ? 'selected' : '' }}>Institutional Active</option>
                                            <option value="inactive" {{ old('status', $school->status) === 'inactive' ? 'selected' : '' }}>Suspended</option>
                                        </select>
                                    </div>
                                    @error('status')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Type of Institute</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-building text-primary"></i></span>
                                        <select name="institute_type" class="form-select bg-light border-0 @error('institute_type') is-invalid @enderror" required>
                                            <option value="academic" {{ old('institute_type', $school->institute_type) === 'academic' ? 'selected' : '' }}>Academic</option>
                                            <option value="sport" {{ old('institute_type', $school->institute_type) === 'sport' ? 'selected' : '' }}>Sport</option>
                                        </select>
                                    </div>
                                    @error('institute_type')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Headquarters Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 align-items-start pt-2"><i class="bi bi-geo-alt text-primary"></i></span>
                                        <textarea name="address" class="form-control bg-light border-0 @error('address') is-invalid @enderror" rows="3">{{ old('address', $school->address) }}</textarea>
                                    </div>
                                    @error('address')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden border-start border-4 border-info">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-info bg-opacity-10 p-3 rounded-4">
                                    <i class="bi bi-info-circle-fill text-info fs-3"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Subscription Lifecycle Management</h6>
                                    <p class="text-muted small mb-3">Institutional subscriptions are managed via specialized tactical vectors. Modification of price tiers or duration should be executed through the extension terminal.</p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="p-2 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                                <span class="tiny fw-bold text-muted text-uppercase">Active Tier</span>
                                                <span class="badge bg-indigo bg-opacity-10 text-indigo rounded-pill px-3">{{ $school->activeSubscription?->plan->name ?? 'No active subscription' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-2 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                                <span class="tiny fw-bold text-muted text-uppercase">Expires On</span>
                                                <span class="small fw-bold {{ $school->subscription_expires_at?->isPast() ? 'text-danger' : 'text-dark' }}">
                                                    {{ $school->subscription_expires_at?->format('d M, Y') ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.schools.index') }}" class="btn btn-sm btn-info text-white fw-bold rounded-pill px-4">Initialize Extension Protocol</a>
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
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-inner position-relative overflow-hidden" style="width: 150px; height: 150px; border: 4px dashed rgba(79, 70, 229, 0.2);">
                                    @if($school->logo)
                                        <img src="{{ asset('storage/' . $school->logo) }}" class="img-fluid rounded-circle" style="object-fit: cover; width: 100%; height: 100%;">
                                    @else
                                        <i class="bi bi-cloud-arrow-up text-primary fs-1"></i>
                                    @endif
                                </div>
                                <p class="text-muted tiny fw-bold text-uppercase mb-0">Synchronize Identity</p>
                            </div>
                            <input type="file" name="logo" class="form-control bg-light border-0 tiny @error('logo') is-invalid @enderror" accept="image/*">
                            @error('logo')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                            <small class="text-muted tiny mt-2 d-block">Leave empty to maintain existing brand identity.</small>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm py-3 w-100">
                            <i class="bi bi-save2-fill me-2"></i> Commit Synchronization
                        </button>
                        <a href="{{ route('admin.schools.index') }}" class="btn btn-light btn-lg rounded-pill fw-bold px-4 py-3 border w-100">
                            Cancel
                        </a>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mt-4 bg-danger bg-opacity-10 border border-danger border-opacity-10 overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <h6 class="fw-bold text-danger mb-2">Institutional Termination</h6>
                            <p class="text-danger tiny mb-4 opacity-75">Warning: Decommissioning this node will permanently eradicate all associated data networks, users, and historical records.</p>
                            <form action="{{ route('admin.schools.destroy', $school) }}" method="POST" onsubmit="return confirm('CRITICAL ACTION: Are you sure you want to decommission this institutional node? This operation is irreversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">Execute Decommissioning</button>
                            </form>
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
        .bg-indigo { background-color: #4f46e5; }
        .text-indigo { color: #4f46e5; }
        .tiny { font-size: 0.75rem; }
    </style>
@endsection