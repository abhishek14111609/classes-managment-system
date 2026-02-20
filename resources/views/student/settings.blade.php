@extends('layouts.app')

@section('title', 'Profile Settings')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Account Security --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-shield-lock text-primary me-2"></i>Security Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('patch')

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-muted">CURRENT PASSWORD</label>
                                    <input type="password" name="current_password" class="form-control"
                                        placeholder="••••••••">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">NEW PASSWORD</label>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">CONFIRM NEW PASSWORD</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="••••••••">
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn btn-primary px-4">Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Profile Information (Request Update) --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square text-success me-2"></i>Contact Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning bg-warning bg-opacity-10 border-0 rounded-4 mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <small class="text-dark">Some details can only be updated by the school administrator. You can
                                request changes to your primary contact information here.</small>
                        </div>

                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">FULL NAME</label>
                                    <input type="text" class="form-control border-0 bg-light"
                                        value="{{ auth()->user()->name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">EMAIL ADDRESS</label>
                                    <input type="email" class="form-control border-0 bg-light"
                                        value="{{ auth()->user()->email }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">CONTACT PHONE</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->phone }}"
                                        placeholder="Update phone number">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">PARENT'S CONTACT</label>
                                    <input type="text" class="form-control"
                                        value="{{ auth()->user()->student->parent_phone ?? '' }}"
                                        placeholder="Update parents contact">
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="button" class="btn btn-success px-4"
                                        onclick="alert('Update request sent to administrator.')">Request Info
                                        Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Session Activity --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-activity text-secondary me-2"></i>Session Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success rounded-circle me-3"
                                style="width: 10px; height: 10px; box-shadow: 0 0 10px rgba(25, 135, 84, 0.5);"></div>
                            <div>
                                <h6 class="mb-0 fw-bold">Current Login</h6>
                                <p class="text-muted small mb-0">{{ now()->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-3 bg-transparent border-light border-bottom">
                                <small class="text-muted d-block">IP ADDRESS</small>
                                <span class="fw-semibold">192.168.1.1</span>
                            </div>
                            <div class="list-group-item px-0 py-3 bg-transparent border-0">
                                <small class="text-muted d-block">DEVICE</small>
                                <span class="fw-semibold">Chrome (Windows 11)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notification Preferences --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-bell text-warning me-2"></i>Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" checked id="feeAlerts">
                            <label class="form-check-label small fw-semibold" for="feeAlerts">Email for Fee Dues</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" checked id="examAlerts">
                            <label class="form-check-label small fw-semibold" for="examAlerts">Upcoming Exam Alerts</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="newsAlerts">
                            <label class="form-check-label small fw-semibold" for="newsAlerts">Weekly Newsletter</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection