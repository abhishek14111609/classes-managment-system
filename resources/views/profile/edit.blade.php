@extends('layouts.app')

@section('title', 'Account Settings')

@section('sidebar')
    @php
        $user = auth()->user();
    @endphp

    @if($user->isSuperAdmin())
        @include('admin.sidebar')
    @elseif($user->isSchoolAdmin())
        @include('school.sidebar')
    @elseif($user->isTeacher())
        @include('teacher.sidebar')
    @elseif($user->isStudent())
        @include('student.sidebar')
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Premium Header Area -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-gradient-brand text-white overflow-hidden p-2">
                    <div class="card-body p-5 d-flex align-items-center justify-content-between position-relative z-1">
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-4">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                        class="rounded-circle shadow-lg border-4 border-white border-opacity-25" width="100"
                                        height="100" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center border-4 border-white border-opacity-25 shadow-lg"
                                        style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 800;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="position-absolute bottom-0 end-0 bg-success border-white border-2 rounded-circle"
                                    style="width: 20px; height: 20px;" title="Online"></div>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                                <p class="mb-0 text-white-50"><i class="bi bi-shield-check me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $user->getRoleNames()->first() ?? 'Authorized User')) }}
                                    Account</p>
                            </div>
                        </div>
                        <div class="text-end d-none d-md-block">
                            <div class="bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur">
                                <small class="text-white-50 fw-bold d-block mb-1 text-uppercase small"
                                    style="letter-spacing: 1px;">Member Since</small>
                                <h5 class="fw-bold mb-0">{{ $user->created_at->format('M Y') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Profile Info & Security -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Identity & Credentials</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Primary Info</span>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Display Name</label>
                                    <div
                                        class="input-group input-group-lg border rounded-4 overflow-hidden shadow-none transition-all">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="bi bi-person text-primary"></i></span>
                                        <input type="text" name="name"
                                            class="form-control border-0 @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required placeholder="Enter full name">
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1 ps-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Email Connectivity</label>
                                    <div
                                        class="input-group input-group-lg border rounded-4 overflow-hidden shadow-none transition-all">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="bi bi-envelope text-primary"></i></span>
                                        <input type="email" name="email"
                                            class="form-control border-0 @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required
                                            placeholder="email@example.com">
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1 ps-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-top">
                                <div class="d-flex align-items-center mb-4 text-primary">
                                    <i class="bi bi-key-fill fs-4 me-3"></i>
                                    <h6 class="fw-bold mb-0">Security Handshake & Password Recovery</h6>
                                </div>

                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label tiny fw-bold text-muted text-uppercase"
                                            style="letter-spacing: 1px;">Current Authorizing Password</label>
                                        <input type="password" name="current_password"
                                            class="form-control form-control-lg rounded-4 @error('current_password') is-invalid @enderror"
                                            placeholder="••••••••">
                                        <small class="text-muted mt-1 d-block opacity-75">Required only if you are changing
                                            your password.</small>
                                        @error('current_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tiny fw-bold text-muted text-uppercase"
                                            style="letter-spacing: 1px;">New Access Key</label>
                                        <input type="password" name="new_password"
                                            class="form-control form-control-lg rounded-4 @error('new_password') is-invalid @enderror"
                                            placeholder="New Secret Key">
                                        @error('new_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label tiny fw-bold text-muted text-uppercase"
                                            style="letter-spacing: 1px;">Validate Access Key</label>
                                        <input type="password" name="new_password_confirmation"
                                            class="form-control form-control-lg rounded-4" placeholder="Repeat Secret Key">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <button type="submit"
                                    class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg fw-bold transition-all grow">
                                    <i class="bi bi-shield-check me-2"></i> Deploy Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Sidebar / Info -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3">Portal Connectivity</h6>
                        <div class="d-grid gap-3">
                            <div class="p-3 rounded-4 bg-light d-flex align-items-center border border-dashed text-primary">
                                <i class="bi bi-clock-history fs-4 me-3"></i>
                                <div>
                                    <small class="text-muted d-block tiny fw-bold text-uppercase">Last Login
                                        Activity</small>
                                    <span class="fw-bold small">{{ now()->diffForHumans() }} (Current Session)</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-4 bg-light d-flex align-items-center border border-dashed text-success">
                                <i class="bi bi-browser-safari fs-4 me-3"></i>
                                <div>
                                    <small class="text-muted d-block tiny fw-bold text-uppercase">Environment
                                        Context</small>
                                    <span class="fw-bold small">Web Application Access</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-4 bg-light d-flex align-items-center border border-dashed text-info">
                                <i class="bi bi-geo-fill fs-4 me-3"></i>
                                <div>
                                    <small class="text-muted d-block tiny fw-bold text-uppercase">Network Context</small>
                                    <span class="fw-bold small">{{ request()->ip() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10">
                    <div class="card-body p-4 text-center">
                        <div class="bg-white rounded-circle p-3 d-inline-block shadow-sm mb-3">
                            <i class="bi bi-shield-shaded text-primary display-6"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Account Security Protocol</h6>
                        <p class="text-muted small mb-0">Your data is secured using industry-standard hashing and encryption
                            protocols. Always ensure you log out after using public devices.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection