@extends('layouts.app')

@section('title', $isSport ? 'Coach Dashboard' : 'Teacher Dashboard')

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
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-grid-fill text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $isSport ? 'Coach' : 'Teacher' }} Dashboard</h4>
                                <p class="text-white-50 mb-0 small">{{ now()->format('l, d F Y') }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('teacher.attendance.create') }}" class="btn btn-primary bg-gradient-brand border-0 rounded-pill px-4 shadow-sm fw-bold">
                                <i class="bi bi-plus-circle me-1"></i> New Attendance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Cluster -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100 overflow-hidden" style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 shadow-sm border border-white border-opacity-10">
                                <i class="bi bi-people-fill text-white fs-4"></i>
                            </div>
                            <span class="badge bg-white text-primary rounded-pill tiny shadow-sm">Assigned</span>
                        </div>
                        <h2 class="fw-bold text-white mb-1">{{ $totalStudents }}</h2>
                        <p class="text-white text-opacity-75 small mb-0 fw-medium">{{ $label['students'] }}</p>
                    </div>
                    <div class="position-absolute end-0 bottom-0 opacity-10 mb-n3 me-n2">
                        <i class="bi bi-people-fill" style="font-size: 100px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100 overflow-hidden" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 shadow-sm border border-white border-opacity-10">
                                <i class="bi bi-diagram-3-fill text-white fs-4"></i>
                            </div>
                            <span class="badge bg-white text-success rounded-pill tiny shadow-sm">Managed</span>
                        </div>
                        <h2 class="fw-bold text-white mb-1">{{ $batches->count() }}</h2>
                        <p class="text-white text-opacity-75 small mb-0 fw-medium">Assigned Batches</p>
                    </div>
                    <div class="position-absolute end-0 bottom-0 opacity-10 mb-n3 me-n2">
                        <i class="bi bi-diagram-3-fill" style="font-size: 100px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-info text-white h-100 overflow-hidden" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 shadow-sm border border-white border-opacity-10">
                                <i class="bi bi-trophy-fill text-white fs-4"></i>
                            </div>
                            <span class="badge bg-white text-info rounded-pill tiny shadow-sm">Scheduled</span>
                        </div>
                        <h2 class="fw-bold text-white mb-1">{{ $upcomingEvents->count() }}</h2>
                        <p class="text-white text-opacity-75 small mb-0 fw-medium">{{ $label['events'] }}</p>
                    </div>
                    <div class="position-absolute end-0 bottom-0 opacity-10 mb-n3 me-n2">
                        <i class="bi bi-trophy-fill" style="font-size: 100px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 text-white h-100 overflow-hidden" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 shadow-sm border border-white border-opacity-10">
                                <i class="bi bi-calendar-check-fill text-white fs-4"></i>
                            </div>
                            <span class="badge bg-white text-warning rounded-pill tiny shadow-sm">Efficiency</span>
                        </div>
                        <h2 class="fw-bold text-white mb-1">{{ $avgAttendance }}%</h2>
                        <p class="text-white text-opacity-75 small mb-0 fw-medium">Attendance Rate</p>
                    </div>
                    <div class="position-absolute end-0 bottom-0 opacity-10 mb-n3 me-n2">
                        <i class="bi bi-calendar-check-fill" style="font-size: 100px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Strategic Column: Sessions -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0">Today's Sessions</h5>
                            <p class="text-muted small">Your batches scheduled for today</p>
                        </div>
                        <div class="d-flex align-items-center bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold shadow-sm">
                            <span class="pulse-emerald me-2"></span> Live Updates
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline position-relative ps-4">
                            @forelse($todaySessions as $session)
                                <div class="timeline-item pb-4 position-relative">
                                    <div class="timeline-marker bg-primary rounded-circle border-4 border-white shadow-sm" style="position: absolute; left: -31px; top: 0; width: 14px; height: 14px; z-index: 2;"></div>
                                    @if(!$loop->last)
                                        <div class="timeline-line bg-light position-absolute" style="left: -25px; top: 0; width: 2px; height: 100%; z-index: 1;"></div>
                                    @endif
                                    
                                    <div class="card border-0 bg-light bg-opacity-50 rounded-4 overflow-hidden transition-all hover-lift">
                                        <div class="card-body p-4">
                                            <div class="row align-items-center">
                                                <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                                                    <h6 class="fw-bold mb-0 text-dark">{{ $session->start_time ? $session->start_time->format('H:i') : '--' }}</h6>
                                                    <small class="text-muted tiny fw-bold text-uppercase">START TIME</small>
                                                </div>
                                                <div class="col-md-7 border-start-md ps-md-4">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="badge bg-white text-dark shadow-sm border rounded-pill tiny me-2">{{ $session->class->name ?? 'GENERAL' }}</span>
                                                        <h6 class="fw-bold mb-0 text-dark">{{ $session->name }}</h6>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 small text-muted">
                                                            <i class="bi bi-people me-1"></i> {{ $session->students_count }} {{ $label['students'] }}
                                                        </div>
                                                        <div class="d-flex align-items-center grow" style="max-width: 150px;">
                                                            <div class="progress w-100 rounded-pill" style="height: 6px;">
                                                                <div class="progress-bar {{ $session->health_score > 70 ? 'bg-success' : ($session->health_score > 40 ? 'bg-warning' : 'bg-danger') }}"
                                                                    style="width: {{ $session->health_score }}%"></div>
                                                            </div>
                                                            <small class="ms-2 tiny fw-bold">{{ $session->health_score }}%</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-end">
                                                    <a href="{{ route('teacher.attendance.create', ['batch_id' => $session->id]) }}" 
                                                       class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">Mark Session</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-calendar-x display-4 text-muted opacity-25"></i>
                                    <p class="text-muted mt-2">No sessions scheduled for today.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Active Batch Insights -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0 text-dark">My {{ $label['batches'] }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($batches as $batch)
                                <div class="col-md-6">
                                    <div class="p-4 rounded-4 bg-white border shadow-sm position-relative overflow-hidden transition-all hover-lift">
                                        <div class="position-absolute end-0 top-0 p-3 opacity-10">
                                            <i class="bi bi-activity fs-1"></i>
                                        </div>
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                                                <i class="bi bi-people-fill text-primary fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $batch->name }}</h6>
                                                <span class="badge bg-light text-muted border rounded-pill tiny">{{ $batch->class->name ?? 'Level' }}</span>
                                            </div>
                                        </div>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted tiny fw-bold text-uppercase">Attendance Rate</span>
                                            <span class="fw-bold {{ $batch->health_score > 70 ? 'text-success' : ($batch->health_score > 40 ? 'text-warning' : 'text-danger') }}">{{ $batch->health_score }}%</span>
                                        </div>
                                        <div class="progress rounded-pill mb-3" style="height: 8px;">
                                            <div class="progress-bar {{ $batch->health_score > 70 ? 'bg-success' : ($batch->health_score > 40 ? 'bg-warning' : 'bg-danger') }}"
                                                style="width: {{ $batch->health_score }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="small text-muted"><i class="bi bi-person-check me-1"></i> {{ $batch->students_count }} {{ $label['students'] }}</div>
                                            <a href="{{ route('teacher.batches.students', $batch) }}" class="small fw-bold text-primary text-decoration-none">
                                                View {{ $label['students'] }} <i class="bi bi-arrow-right small ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Column: Events & Logs -->
            <div class="col-xl-4">
                <!-- Upcoming Challenges -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Upcoming {{ $label['events'] }}</h5>
                        <a href="{{ route('teacher.events.index') }}" class="btn btn-link btn-sm text-primary p-0 fw-bold text-decoration-none">View All</a>
                    </div>
                    <div class="card-body p-4">
                        @forelse($upcomingEvents as $event)
                            <div class="d-flex align-items-center p-3 rounded-4 bg-light border border-dashed mb-3 transition-all hover-lift">
                                <div class="bg-white p-2 rounded-3 shadow-sm me-3 text-center" style="min-width: 50px;">
                                    <h6 class="fw-bold mb-0 text-primary">{{ $event->event_date->format('d') }}</h6>
                                    <small class="text-muted tiny fw-bold text-uppercase">{{ $event->event_date->format('M') }}</small>
                                </div>
                                <div class="grow">
                                    <h6 class="fw-bold mb-0 text-dark small">{{ $event->title }}</h6>
                                    <div class="tiny text-muted mt-1">
                                        <i class="bi bi-geo-alt me-1"></i> {{ $event->location ?? 'Main Arena' }}
                                    </div>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill tiny px-2">{{ strtoupper($event->status) }}</span>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded-4 border border-dashed">
                                <i class="bi bi-journal-x fs-2 text-muted opacity-25"></i>
                                <p class="text-muted tiny fw-bold text-uppercase mt-2 mb-0">No upcoming events</p>
                            </div>
                        @endforelse
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .timeline-item:last-child {
        padding-bottom: 0 !important;
    }
    .last-child-mb-0:last-child {
        margin-bottom: 0 !important;
    }
    .pulse-emerald {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-emerald 2s infinite;
    }

    @keyframes pulse-emerald {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endpush