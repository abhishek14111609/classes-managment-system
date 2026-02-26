@extends('layouts.app')

@section('title', 'Squad Deployment Registry')

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
                                <i class="bi bi-diagram-3-fill text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">Squad Deployment Registry</h4>
                                <p class="text-white-50 mb-0 small">Direct management of operational training units</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($batches as $batch)
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-all hover-lift bg-white">
                        <div class="card-header border-0 bg-primary bg-opacity-10 p-4 position-relative overflow-hidden">
                            <div class="position-absolute end-0 top-0 p-4 opacity-10">
                                <i class="bi bi-shield-shaded fs-1"></i>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span
                                    class="badge bg-white text-primary border rounded-pill px-3 tiny fw-bold shadow-sm">{{ strtoupper($batch->class->name ?? 'GENERAL') }}</span>
                                <div class="dropdown">
                                    <button class="btn btn-link text-primary p-0 shadow-none" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots-vertical fs-5"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                                        <li><a class="dropdown-item rounded-3 py-2"
                                                href="{{ route('teacher.batches.students', $batch) }}"><i
                                                    class="bi bi-people me-2"></i> Cohort Members</a></li>
                                        <li><a class="dropdown-item rounded-3 py-2"
                                                href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"><i
                                                    class="bi bi-calendar-check me-2"></i> Attendance Deck</a></li>
                                    </ul>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1 text-dark">{{ $batch->name }}</h4>
                            <div class="d-flex align-items-center text-muted small mt-2">
                                <div class="bg-white border rounded-pill px-3 py-1 me-2 shadow-sm">
                                    <i class="bi bi-clock-fill text-primary me-1"></i>
                                    <span
                                        class="fw-bold">{{ $batch->start_time ? $batch->start_time->format('H:i') . ' - ' . $batch->end_time->format('H:i') : 'OFF-CYCLE' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="bg-light p-3 rounded-4 text-center border border-dashed shadow-sm">
                                        <div class="tiny text-muted fw-bold mb-1 uppercase">SYNC STATUS</div>
                                        <div class="small fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i>
                                            ACTIVE</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-3 rounded-4 text-center border border-dashed shadow-sm">
                                        <div class="tiny text-muted fw-bold mb-1 uppercase">COHORT SIZE</div>
                                        <div class="small fw-bold text-dark">{{ $batch->students->count() }} MEMBERS</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0 tiny text-muted text-uppercase" style="letter-spacing: 1px;">Batch
                                    Health Index</h6>
                                <span class="fw-bold text-primary tiny">84.2%</span>
                            </div>
                            <div class="progress rounded-pill mb-4" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: 84%;"></div>
                            </div>

                            <div class="d-grid gap-3">
                                <a href="{{ route('teacher.batches.students', $batch) }}"
                                    class="btn btn-outline-primary rounded-pill py-2 fw-bold shadow-sm transition-all hover-lift">
                                    <i class="bi bi-person-badge-fill me-2 border-primary"></i> MEMBER ANALYTICS
                                </a>
                                <a href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"
                                    class="btn btn-primary bg-gradient-brand border-0 rounded-pill py-3 fw-bold shadow-lg transition-all hover-lift">
                                    <i class="bi bi-qr-code-scan me-2"></i> INITIALIZE ATTENDANCE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5">
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                        <div class="py-5 opacity-25">
                            <i class="bi bi-diagram-3 display-1 d-block mb-3"></i>
                            <h4 class="fw-bold text-dark">Deployment Registry Empty</h4>
                            <p class="text-muted small mb-0">No operational batches have been assigned to your command yet.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection