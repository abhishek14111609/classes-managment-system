@extends('layouts.app')

@section('title', 'Portal Management')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Modern Settings Header -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-gear-wide-connected text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">System Configurations</h4>
                                <p class="text-white-50 mb-0 small">Manage your identity, security, and notification
                                    preferences</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Master Profile Adjustment --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Identity Hub</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Primary Account</span>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="d-flex align-items-center mb-5 p-4 rounded-4 bg-light border border-dashed">
                                <div class="position-relative me-4">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                            class="rounded-circle border-4 border-white shadow-lg"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold shadow-sm border-4 border-white"
                                            style="width: 100px; height: 100px; font-size: 2rem;">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <label for="avatar"
                                        class="position-absolute bottom-0 end-0 bg-primary shadow-lg rounded-circle p-2 cursor-pointer transition-all hover-lift"
                                        style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-camera-fill text-white small"></i>
                                    </label>
                                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Electronic Identity Photo</h6>
                                    <p class="text-muted small mb-0">JPEG, PNG or SVG formats strictly. Limit 2.0 MB.</p>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Legal Full Name</label>
                                    <input type="text" name="name"
                                        class="form-control form-control-lg rounded-4 shadow-none border-light"
                                        value="{{ old('name', auth()->user()->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Cloud Identifier (Email)</label>
                                    <input type="email" name="email"
                                        class="form-control form-control-lg rounded-4 shadow-none border-light"
                                        value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Contact Telemetry</label>
                                    <input type="text" name="phone"
                                        class="form-control form-control-lg rounded-4 shadow-none border-light"
                                        value="{{ old('phone', auth()->user()->phone) }}" placeholder="+12 345 678 90">
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit"
                                        class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg grow fw-bold">Synchronize
                                        Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Security Protocol Alignment --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Security Protocol Alignment</h5>
                        <i class="bi bi-shield-lock-fill text-danger opacity-75 fs-4"></i>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('patch')

                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Current Access Key</label>
                                    <input type="password" name="current_password"
                                        class="form-control form-control-lg rounded-4 shadow-none border-light"
                                        placeholder="••••••••">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">New Strategic Access Key</label>
                                    <input type="password" name="new_password"
                                        class="form-control form-control-lg rounded-4 shadow-none border-light"
                                        placeholder="••••••••">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Validate New Key</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control form-control-lg rounded-4 shadow-none border-light"
                                        placeholder="••••••••">
                                </div>
                                <div class="col-12">
                                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit"
                                        class="btn btn-danger btn-lg rounded-pill px-5 shadow-lg grow fw-bold">Deploy New
                                        Security Protocol</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Network Handshake Activity --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                    <div
                        class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Portal Context</h5>
                        <i class="bi bi-broadcast text-primary opacity-50 fs-5"></i>
                    </div>
                    <div class="card-body p-4">
                        <div
                            class="d-flex align-items-center mb-4 p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-10">
                            <div class="bg-success rounded-circle me-3 pulse-success"
                                style="width: 12px; height: 12px; box-shadow: 0 0 10px rgba(25, 135, 84, 0.5);"></div>
                            <div class="grow">
                                <h6 class="mb-0 fw-bold text-success">Active Session</h6>
                                <p class="text-success text-opacity-75 tiny mb-0">Uptime: Securely Connected</p>
                            </div>
                        </div>
                        <div class="list-group list-group-flush rounded-4 overflow-hidden border">
                            <div class="list-group-item px-4 py-3 bg-light bg-opacity-50 border-bottom">
                                <small class="text-muted tiny fw-bold text-uppercase d-block mb-1"
                                    style="letter-spacing: 1px;">IP ADDRESS Context</small>
                                <span class="fw-bold text-dark small">{{ request()->ip() }}</span>
                            </div>
                            <div class="list-group-item px-4 py-3 bg-light bg-opacity-50 border-0">
                                <small class="text-muted tiny fw-bold text-uppercase d-block mb-1"
                                    style="letter-spacing: 1px;">Environment Client</small>
                                <span class="fw-bold text-dark small text-truncate d-inline-block w-100"
                                    title="{{ request()->userAgent() }}">
                                    {{ Str::limit(request()->userAgent(), 40) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Frequency / Broadcast Toggles --}}
                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                    <div
                        class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Data Broadcasts</h5>
                        <i class="bi bi-bell-fill text-warning opacity-75 fs-5"></i>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold mb-0 small">Fee Surcharge Alerts</h6>
                                    <p class="text-muted tiny mb-0">Primary billing notifications</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked id="feeAlerts">
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold mb-0 small">Tournament Dispatch</h6>
                                    <p class="text-muted tiny mb-0">Sports & events updates</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked id="examAlerts">
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold mb-0 small">Insight Newsletter</h6>
                                    <p class="text-muted tiny mb-0">Weekly performance summary</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="newsAlerts">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pulse-success {
            animation: pulse-success 2s infinite;
        }

        @keyframes pulse-success {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
            }
        }
    </style>
@endpush