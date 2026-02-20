@extends('layouts.app')

@section('title', 'My Managed Batches')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-gradient">My Managed Batches</h3>
                <p class="text-muted small mb-0">Overview of all classes and programs currently under your supervision.</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($batches as $batch)
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="card-header border-0 bg-primary bg-opacity-10 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary rounded-pill px-3">{{ $batch->class->name }}</span>
                                <div class="dropdown">
                                    <button class="btn btn-link text-primary p-0" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                        <li><a class="dropdown-item" href="{{ route('teacher.batches.students', $batch) }}"><i
                                                    class="bi bi-people me-2"></i> View Students</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"><i
                                                    class="bi bi-calendar-check me-2"></i> Mark Attendance</a></li>
                                    </ul>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1">{{ $batch->name }}</h4>
                            <p class="text-muted small mb-0"><i class="bi bi-clock me-1"></i>
                                {{ $batch->start_time ? $batch->start_time->format('h:i A') . ' - ' . $batch->end_time->format('h:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded-3 text-center">
                                        <div class="tiny text-muted uppercase fw-bold mb-1">Schedule</div>
                                        <div class="small fw-bold text-dark">
                                            {{ $batch->start_time ? $batch->start_time->format('h:i A') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded-3 text-center">
                                        <div class="tiny text-muted uppercase fw-bold mb-1">Students</div>
                                        <div class="small fw-bold text-dark">{{ $batch->students->count() }}</div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold mb-3 small text-muted text-uppercase" style="letter-spacing: 1px;">Recent
                                Progress</h6>
                            <div class="progress rounded-pill mb-4" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 75%;"></div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('teacher.batches.students', $batch) }}"
                                    class="btn btn-white border rounded-pill py-2 shadow-sm">
                                    <i class="bi bi-person-lines-fill me-2"></i> Manage Students
                                </a>
                                <a href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"
                                    class="btn btn-primary rounded-pill py-2 shadow-sm">
                                    <i class="bi bi-check2-circle me-2"></i> Record Attendance
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://img.icons8.com/bubbles/200/null/empty-box.png" alt="No Batches" class="mb-4">
                    <h3>No Batches Assigned</h3>
                    <p class="text-muted">You haven't been assigned to any batches yet. Please contact the administrator.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection