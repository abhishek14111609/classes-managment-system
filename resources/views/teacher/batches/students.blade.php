@extends('layouts.app')

@section('title', 'Athlete Roster - ' . $batch->name)

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Premium Header Area -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-people-fill text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $batch->name }}</h4>
                                <p class="text-white-50 mb-0 small">Operational Athlete Roster & Performance Index</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('teacher.batches.index') }}"
                                class="btn btn-outline-white border-white border-opacity-25 rounded-pill px-4 fw-bold text-white small">
                                <i class="bi bi-arrow-left me-1"></i> Deployment Desk
                            </a>
                            <a href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"
                                class="btn btn-primary bg-gradient-brand border-0 rounded-pill px-4 shadow-sm fw-bold">
                                <i class="bi bi-calendar-check me-1"></i> Session Mark
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($students as $student)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-all hover-lift bg-white">
                        <div class="card-body p-4 text-center">
                            <div class="mb-4 position-relative d-inline-block">
                                <div class="avatar-glow position-absolute top-50 start-50 translate-middle rounded-circle bg-primary opacity-10"
                                    style="width: 120px; height: 120px;"></div>
                                <img src="{{ $student->user->avatar ? asset('storage/' . $student->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name) . '&background=4f46e5&color=fff&size=200' }}"
                                    alt="{{ $student->user->name }}"
                                    class="rounded-circle border-4 border-white shadow-lg position-relative z-1"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <span
                                    class="position-absolute bottom-0 end-0 bg-success border-4 border-white rounded-circle z-1 shadow-sm"
                                    style="width: 24px; height: 24px;" title="Operational Status: Active"></span>
                            </div>

                            <h5 class="fw-bold mb-1 text-dark">{{ $student->user->name }}</h5>
                            <div
                                class="badge bg-light text-primary border rounded-pill px-3 py-1 tiny fw-bold mb-4 shadow-none">
                                ATHLETE #{{ $student->roll_number }}</div>

                            <div class="row g-2 mb-4">
                                <div class="col-12">
                                    <div class="p-3 bg-light bg-opacity-50 rounded-4 border border-dashed">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="tiny fw-bold text-muted text-uppercase">Performance Rate</span>
                                            <span
                                                class="fw-bold text-success small">{{ $student->getAttendancePercentage() }}%</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height: 6px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ $student->getAttendancePercentage() }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group list-group-flush rounded-4 overflow-hidden border mb-4">
                                <div
                                    class="list-group-item px-3 py-2 bg-light bg-opacity-25 border-bottom d-flex justify-content-between align-items-center">
                                    <small class="text-muted tiny fw-bold uppercase">COMM CHANNEL</small>
                                    <span
                                        class="fw-bold text-dark small">{{ Str::limit($student->user->phone ?? 'N/A', 12) }}</span>
                                </div>
                                <div
                                    class="list-group-item px-3 py-2 bg-light bg-opacity-25 border-0 d-flex justify-content-between align-items-center">
                                    <small class="text-muted tiny fw-bold uppercase">IDENTITY</small>
                                    <span class="fw-bold text-dark small text-truncate ms-2"
                                        title="{{ $student->user->email }}">{{ Str::limit($student->user->email, 15) }}</span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('teacher.students.show', $student) }}"
                                    class="btn btn-primary rounded-pill py-2 shadow-sm fw-bold transition-all hover-lift">
                                    <i class="bi bi-graph-up-arrow me-2"></i> ANALYZE PERFORMANCE
                                </a>
                                <div class="d-flex gap-2 mt-1">
                                    <a href="mailto:{{ $student->user->email }}"
                                        class="btn btn-outline-white border rounded-pill grow py-2 text-muted fw-bold small">
                                        <i class="bi bi-envelope-fill me-1"></i> EMAIL
                                    </a>
                                    <a href="tel:{{ $student->user->phone }}"
                                        class="btn btn-outline-white border rounded-pill grow py-2 text-muted fw-bold small">
                                        <i class="bi bi-telephone-fill me-1"></i> CALL
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="py-5 bg-white shadow-sm rounded-4 border border-dashed">
                        <i class="bi bi-person-x display-1 text-muted opacity-10"></i>
                        <h5 class="fw-bold text-dark mt-3">Roster Synchronization Failed</h5>
                        <p class="text-muted small">This operational batch currently has no assigned athletes.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-glow {
            filter: blur(20px);
            transform: translate(-50%, -50%) scale(1.2);
        }

        .grow {
            flex-grow: 1;
        }
    </style>
@endpush