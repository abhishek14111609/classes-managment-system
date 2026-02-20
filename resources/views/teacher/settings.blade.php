@extends('layouts.app')

@section('title', 'Portal Settings')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <h3 class="fw-bold mb-4 text-gradient">Portal Settings</h3>

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Account Security -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="bi bi-shield-lock-fill text-primary me-2"></i> Security & Authentication
                        </h5>
                        <form>
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold">CURRENT PASSWORD</label>
                                    <input type="password" class="form-control rounded-3 py-2" placeholder="••••••••">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">NEW PASSWORD</label>
                                    <input type="password" class="form-control rounded-3 py-2"
                                        placeholder="Min. 8 characters">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">CONFIRM NEW PASSWORD</label>
                                    <input type="password" class="form-control rounded-3 py-2"
                                        placeholder="Repeat new password">
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm border-0">
                                <i class="bi bi-key me-2"></i> Update Security Credentials
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Notification Preferences -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="bi bi-bell-fill text-warning me-2"></i> Notification Preferences
                        </h5>
                        <div class="list-group list-group-flush">
                            <div
                                class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                                <div>
                                    <h6 class="mb-1 fw-bold">Session Alerts</h6>
                                    <p class="text-muted small mb-0">Get notified 15 minutes before your batch starts.</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>
                            <div
                                class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                                <div>
                                    <h6 class="mb-1 fw-bold">Student Messages</h6>
                                    <p class="text-muted small mb-0">Receive email alerts for new student inquiries.</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>
                            <div
                                class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-0">
                                <div>
                                    <h6 class="mb-1 fw-bold">Monthly Reports</h6>
                                    <p class="text-muted small mb-0">Automated performance summary of your batches.</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Status & Availability -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Availability Status</h5>
                        <div class="text-center mb-4">
                            <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-check-circle fs-1 text-success"></i>
                            </div>
                            <h6 class="fw-bold">Currently Active</h6>
                            <p class="text-muted small">Your profile is visible to students and admins.</p>
                        </div>
                        <button class="btn btn-light w-100 rounded-pill py-2 border mb-2">Mark as Away</button>
                    </div>
                </div>

                <!-- Support Information -->
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-headset me-2"></i> Coach Support</h5>
                        <p class="small opacity-75 mb-4">Need help with batch assignments or technical issues? Our support
                            team is here for you 24/7.</p>
                        <a href="mailto:support@school.com"
                            class="btn btn-white w-100 rounded-pill py-2 fw-bold text-primary shadow-sm border-0">
                            Contact Admin Team
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection