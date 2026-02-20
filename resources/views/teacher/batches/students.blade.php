@extends('layouts.app')

@section('title', 'Students in ' . $batch->name)

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Back Button & Header -->
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <a href="{{ route('teacher.batches.index') }}"
                    class="btn btn-link text-decoration-none p-0 mb-2 text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Back to Batches
                </a>
                <h3 class="fw-bold mb-0 text-gradient">{{ $batch->name }} <span class="text-muted fw-normal fs-5">| Student
                        Roster</span></h3>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-white border rounded-pill shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print Roster
                </button>
                <a href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"
                    class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-calendar-check me-1"></i> Mark Attendance
                </a>
            </div>
        </div>

        <!-- Student Grid -->
        <div class="row g-4">
            @forelse($students as $student)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-lift transition-all">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 position-relative d-inline-block">
                                <img src="{{ $student->user->avatar ? asset('storage/' . $student->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name) . '&background=random&color=fff&size=128' }}"
                                    alt="{{ $student->user->name }}" class="rounded-circle border-4 border-light shadow-sm"
                                    style="width: 100px; height: 100px; object-fit: cover; border-style: solid;">
                                <span class="position-absolute bottom-0 end-0 bg-success border-2 border-white rounded-circle"
                                    style="width: 15px; height: 15px;"></span>
                            </div>

                            <h5 class="fw-bold mb-1 text-dark">{{ $student->user->name }}</h5>
                            <p class="text-muted tiny mb-3">Roll No: <strong>#{{ $student->id }}</strong></p>

                            <div class="d-flex justify-content-center gap-2 mb-4">
                                <span class="badge bg-light text-dark border-0 rounded-pill px-3 py-2 small">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    {{ $student->getAttendancePercentage() }}%
                                </span>
                            </div>

                            <div class="p-3 bg-light rounded-4 mb-4">
                                <div class="d-flex justify-content-between mb-1 small">
                                    <span class="text-muted">Contact:</span>
                                    <span class="text-dark fw-bold">{{ $student->user->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex justify-content-between small text-wrap overflow-hidden">
                                    <span class="text-muted">Email:</span>
                                    <span class="text-dark fw-bold text-truncate ms-2">{{ $student->user->email }}</span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="mailto:{{ $student->user->email }}"
                                    class="btn btn-primary rounded-pill py-2 shadow-sm">
                                    <i class="bi bi-envelope me-2"></i> Contact Student
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="opacity-25 mb-3"><i class="bi bi-person-x d-block" style="font-size: 5rem;"></i></div>
                    <h5 class="text-muted">No students found in this batch.</h5>
                </div>
            @endforelse
        </div>
    </div>
@endsection