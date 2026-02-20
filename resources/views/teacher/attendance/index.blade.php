@extends('layouts.app')

@section('title', 'Attendance Management')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
            <div>
                <h3 class="fw-bold mb-0 text-gradient">Smart Attendance Tracking</h3>
                <p class="text-muted small mb-0">Select your session and manage student presence with ease.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-light border rounded-pill px-4 shadow-sm">
                    <i class="bi bi-house me-1"></i> Dashboard
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($batches as $batch)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all overflow-hidden">
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 small">
                                {{ $batch->class->name }}
                            </span>
                        </div>
                        <div class="card-body p-4 pt-5">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 d-inline-block text-primary mb-4">
                                <i class="bi bi-journal-check fs-3"></i>
                            </div>

                            <h5 class="fw-bold mb-1">{{ $batch->name }}</h5>
                            <p class="text-muted small mb-4">
                                <i class="bi bi-clock me-1"></i>
                                {{ $batch->start_time ? $batch->start_time->format('h:i A') : 'N/A' }} –
                                {{ $batch->end_time ? $batch->end_time->format('h:i A') : 'N/A' }}
                            </p>

                            <div class="row g-3 mb-4">
                                <div class="col-6 text-center border-end">
                                    <div class="fw-bold text-dark h5 mb-0">{{ $batch->students->count() }}</div>
                                    <small class="text-muted tiny text-uppercase">Total Students</small>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="fw-bold text-success h5 mb-0">94%</div>
                                    <small class="text-muted tiny text-uppercase">Avg. Presence</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"
                                    class="btn btn-primary rounded-pill py-2 shadow-sm">
                                    <i class="bi bi-pencil-square me-2"></i> Mark Attendance
                                </a>
                                <a href="{{ route('teacher.batches.students', $batch) }}"
                                    class="btn btn-light border rounded-pill py-2">
                                    <i class="bi bi-people me-2"></i> Student Roster
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="opacity-25 mb-3"><i class="bi bi-journal-x" style="font-size: 5rem;"></i></div>
                    <h4 class="text-muted">No batches assigned to your coaching registry yet.</h4>
                    <p class="text-muted small mb-4">Please contact the admin if you believe this is an error.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection