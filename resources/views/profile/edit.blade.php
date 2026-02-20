@extends('layouts.app')

@section('title', 'My Profile')

@section('sidebar')
    @php
        $user = auth()->user();
        $dashboardRoute = $user->dashboardRoute();
    @endphp

    @if($user->isSuperAdmin())
        <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.schools.index') }}"><i class="bi bi-building"></i> Schools</a></li>
        <li><a href="{{ route('admin.plans.index') }}"><i class="bi bi-card-checklist"></i> Plans</a></li>
    @elseif($user->isSchoolAdmin())
        <li><a href="{{ route('school.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="{{ route('school.classes.index') }}"><i class="bi bi-journal-bookmark"></i> Classes</a></li>
        <li><a href="{{ route('school.batches.index') }}"><i class="bi bi-collection"></i> Batches</a></li>
        <li><a href="{{ route('school.students.index') }}"><i class="bi bi-people"></i> Students</a></li>
    @elseif($user->isTeacher())
        <li><a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="{{ route('teacher.attendance.index') }}"><i class="bi bi-calendar-check"></i> Attendance</a></li>
    @elseif($user->isStudent())
        <li><a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="{{ route('student.profile') }}"><i class="bi bi-person"></i> My Classes</a></li>
    @endif

    <li class="mt-auto">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-white-50 text-decoration-none w-100 text-start">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="mb-4">Account Settings</h2>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Profile Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Update Password</h5>
                            <p class="text-muted small mb-4">Leave blank if you don't want to change your password.</p>

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password"
                                        class="form-control @error('new_password') is-invalid @enderror">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Role Info -->
                <div class="card border-0 bg-light">
                    <div class="card-body d-flex align-items-center">
                        <div class="shrink-0">
                            <i class="bi bi-shield-lock-fill fs-1 text-primary opacity-50"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Access Level</h6>
                            <p class="mb-0 text-muted">You are logged in as
                                <strong>{{ ucfirst(str_replace('_', ' ', $user->getRoleNames()->first() ?? 'User')) }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection