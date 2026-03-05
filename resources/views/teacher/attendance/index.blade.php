@extends('layouts.app')

@section('title', 'Attendance Tracking Pulse')

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
                                <i class="bi bi-calendar2-check-fill text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">Attendance Tracking Pulse</h4>
                                <p class="text-white-50 mb-0 small">Select an operational squad to initialize presence
                                    verification</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($batches as $batch)
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all overflow-hidden bg-white">
                        <div class="position-absolute top-0 end-0 p-3 z-1">
                            <span class="badge bg-white text-primary border rounded-pill px-3 py-2 tiny fw-bold shadow-sm">
                                {{ strtoupper($batch->class->name ?? 'GENERAL') }}
                            </span>
                        </div>
                        <div class="card-body p-4 pt-5">
                            <div
                                class="bg-primary bg-opacity-10 p-4 rounded-4 d-inline-block text-primary mb-4 border border-primary border-opacity-10 shadow-sm">
                                <i class="bi bi-shield-lock-fill fs-2"></i>
                            </div>

                            <h5 class="fw-bold mb-1 text-dark">{{ $batch->name }}</h5>
                            <div class="d-flex align-items-center mb-4">
                                <div class="badge bg-light text-muted border rounded-pill px-3 py-1 tiny shadow-none fw-bold">
                                    <i class="bi bi-clock-fill text-primary me-1"></i>
                                    {{ $batch->start_time ? $batch->start_time->format('H:i') : '--:--' }} –
                                    {{ $batch->end_time ? $batch->end_time->format('H:i') : '--:--' }}
                                </div>
                            </div>

                            <div class="row g-3 mb-4 bg-light bg-opacity-50 p-3 rounded-4 border border-dashed text-center">
                                <div class="col-6 border-end">
                                    <div class="fw-bold text-dark h5 mb-0">{{ $batch->students->count() }}</div>
                                    <small class="text-muted tiny fw-bold text-uppercase">Total Squad</small>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-success h5 mb-0">{{ $batch->health_score }}%</div>
                                    <small class="text-muted tiny fw-bold text-uppercase">Yield Rate</small>
                                </div>
                            </div>

                            <div class="d-grid gap-3">
                                <a href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"
                                    class="btn btn-primary bg-gradient-brand border-0 rounded-pill py-3 shadow-lg fw-bold transition-all hover-lift">
                                    <i class="bi bi-qr-code-scan me-2"></i> MARK ATTENDANCE
                                </a>
                                <a href="{{ route('teacher.batches.students', $batch) }}"
                                    class="btn btn-outline-white border rounded-pill py-2 fw-bold text-muted small transition-all hover-lift">
                                    <i class="bi bi-people-fill me-2"></i> MEMBER ROSTER
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="py-5 bg-white shadow-sm rounded-4 border border-dashed">
                        <div class="opacity-10 mb-3"><i class="bi bi-journal-x" style="font-size: 5rem;"></i></div>
                        <h4 class="fw-bold text-dark">Registry Synchronization Failed</h4>
                        <p class="text-muted small mb-0">No active operational squads found in your command registry.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection