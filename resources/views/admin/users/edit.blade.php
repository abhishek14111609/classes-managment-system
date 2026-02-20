@extends('layouts.app')

@section('title', 'Modify User Credentials - ' . $user->name)
@section('hide_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Back Navigation & Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 fade-in">
            <div class="mb-3 mb-md-0">
                <a href="{{ route('admin.users.index') }}"
                    class="btn btn-link text-decoration-none p-0 mb-2 text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Back to User Registry
                </a>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Modify User Credentials</h2>
                <p class="text-muted mb-0">Synchronizing data for <span
                        class="fw-bold text-primary">{{ $user->name }}</span></p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="bg-primary bg-opacity-10 p-4 text-center border-bottom">
                        <div class="position-relative d-inline-block mb-3">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle shadow-sm" width="100"
                                    height="100" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold fs-1 shadow-sm mx-auto"
                                    style="width: 100px; height: 100px;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <span
                                class="position-absolute bottom-0 end-0 p-2 bg-{{ $user->is_active ? 'success' : 'danger' }} border-4 border-white rounded-circle"></span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                        <div class="d-flex justify-content-center gap-2">
                            @foreach($user->roles as $role)
                                <span
                                    class="badge bg-dark bg-opacity-75 rounded-pill px-3">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Legal Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="name" class="form-control bg-light border-0"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                @error('name') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Primary Email
                                    Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="bi bi-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-0"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>
                                @error('email') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Communication Line</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="bi bi-telephone text-muted"></i></span>
                                    <input type="text" name="phone" class="form-control bg-light border-0"
                                        value="{{ old('phone', $user->phone) }}">
                                </div>
                                @error('phone') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>

                            <div class="alert alert-info border-0 rounded-4 p-3 small d-flex mb-4">
                                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                <div>
                                    Institutional mapping and roles are managed via specific administrative protocols.
                                    Contact system architecture for role reassignment.
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm py-3">
                                    <i class="bi bi-save2-fill me-2"></i> Commit Synchronization
                                </button>
                                <a href="{{ route('admin.users.index') }}"
                                    class="btn btn-link text-muted text-decoration-none small">Abort & Return</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection