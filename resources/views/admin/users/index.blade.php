@extends('layouts.app')

@section('title', 'Manage Users')
@section('hide_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 fade-in">
            <div>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">User Management</h2>
                <p class="text-muted mb-0">Oversee all system users, roles, and permissions.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <button class="btn btn-primary shadow-sm px-4">
                    <i class="bi bi-person-plus-fill me-2"></i> Add Platform User
                </button>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4 fade-in" style="animation-delay: 0.1s;">
            <div class="card-body p-4">
                <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-bold text-uppercase">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-start-0 ps-0"
                                placeholder="Search by name, email, or ID..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Role Filter</label>
                        <select name="role" class="form-select bg-light border-0">
                            <option value="">All Roles</option>
                            <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin
                            </option>
                            <option value="school_admin" {{ request('role') == 'school_admin' ? 'selected' : '' }}>School
                                Admin</option>
                            <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            Apply Filters
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.users.index') }}"
                            class="btn btn-outline-secondary w-100 fw-bold border-0 bg-light text-muted">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm fade-in" style="animation-delay: 0.2s;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">User Details</th>
                                <th>Role & Permissions</th>
                                <th>Associated School</th>
                                <th>Account Status</th>
                                <th>Joined Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="position-relative">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                                        class="rounded-circle shadow-sm" width="48" height="48"
                                                        style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold fs-5"
                                                        style="width: 48px; height: 48px;">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <span
                                                    class="position-absolute bottom-0 end-0 p-1 bg-{{ $user->is_active ? 'success' : 'secondary' }} border border-white rounded-circle">
                                                    <span class="visually-hidden">Status</span>
                                                </span>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-0 fw-bold text-dark">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            @php
                                                $roleClass = match ($role->name) {
                                                    'super_admin' => 'bg-purple text-purple',
                                                    'school_admin' => 'bg-primary text-primary',
                                                    'teacher' => 'bg-info text-info',
                                                    'student' => 'bg-success text-success',
                                                    default => 'bg-secondary text-secondary'
                                                };
                                                // Fallback customized classes if CSS doesn't support bg-purple fully yet
                                                if ($role->name == 'super_admin')
                                                    $roleClass = 'bg-dark text-light'; 
                                            @endphp
                                            <span
                                                class="badge {{ $roleClass }} bg-opacity-10 border border-opacity-10 px-3 py-1 rounded-pill text-uppercase"
                                                style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                                {{ str_replace('_', ' ', $role->name) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->school)
                                            <div class="d-flex align-items-center text-dark">
                                                <i class="bi bi-building me-2 text-muted"></i>
                                                <span class="fw-medium">{{ $user->school->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic ms-2">Global Access</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded">Active</span>
                                        @else
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted fw-medium">{{ $user->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-light btn-sm {{ $user->is_active ? 'text-warning' : 'text-success' }} hover-shadow"
                                                    title="Toggle Access">
                                                    <i class="bi bi-shield-{{ $user->is_active ? 'slash' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="btn btn-light btn-sm text-primary hover-shadow ms-1"
                                                title="Modify Credentials">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="bg-light p-3 rounded-circle mb-3">
                                                <i class="bi bi-people text-muted fs-2"></i>
                                            </div>
                                            <h5 class="text-muted">No users found</h5>
                                            <p class="text-muted small mb-0">Try adjusting your filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($users->hasPages())
                <div class="mt-4 d-flex justify-content-end">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection