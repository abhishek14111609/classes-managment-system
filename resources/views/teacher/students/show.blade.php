@extends('layouts.app')

@section('title', $student->user->name . ' - Student Journey')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <a href="{{ route('teacher.batches.students', $student->batch_id) }}"
                    class="btn btn-link text-decoration-none p-0 mb-1 text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Back to Batch Students
                </a>
                <h3 class="fw-bold mb-0 text-gradient">Student Performance Profile</h3>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary rounded-pill px-4 shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Export Profile
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Profile Card -->
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="bg-primary pt-5 pb-3 text-center position-relative">
                        <img src="{{ $student->user->avatar ? asset('storage/' . $student->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name) . '&background=random&color=fff&size=128' }}"
                            alt="{{ $student->user->name }}"
                            class="rounded-circle border-4 border-white shadow-lg mb-3"
                            style="width: 120px; height: 120px; object-fit: cover; margin-top: 10px;">
                        <h4 class="text-white fw-bold mb-0">{{ $student->user->name }}</h4>
                        <p class="text-white-50 mb-0">Roll No: #{{ $student->id }}</p>
                    </div>
                    <div class="card-body p-4 pt-5">
                        <div class="row text-center mb-4">
                            <div class="col-6 border-end">
                                <h4 class="fw-bold text-dark mb-0">{{ $attendanceSummary['percentage'] }}%</h4>
                                <small class="text-muted text-uppercase tiny fw-bold">Attendance</small>
                            </div>
                            <div class="col-6">
                                <h4 class="fw-bold text-dark mb-0">{{ $student->events->count() }}</h4>
                                <small class="text-muted text-uppercase tiny fw-bold">Events</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush small">
                            <div class="list-group-item d-flex justify-content-between px-0 py-3">
                                <span class="text-muted"><i class="bi bi-calendar-event me-2"></i> Date of Joining</span>
                                <span class="fw-bold text-dark">{{ $student->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0 py-3 text-wrap overflow-hidden">
                                <span class="text-muted"><i class="bi bi-envelope me-2"></i> Email Address</span>
                                <span class="fw-bold text-dark text-truncate ms-2">{{ $student->user->email }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0 py-3">
                                <span class="text-muted"><i class="bi bi-phone me-2"></i> Phone</span>
                                <span class="fw-bold text-dark">{{ $student->user->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0 py-3">
                                <span class="text-muted"><i class="bi bi-layers me-2"></i> Sport Level</span>
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $student->batch->class->name ?? 'Junior' }}</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <a href="mailto:{{ $student->user->email }}"
                                class="btn btn-primary rounded-pill py-2 shadow-sm">
                                <i class="bi bi-chat-dots me-2"></i> Message Student
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Attendance Heatmap (Simple Stats) -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold mb-0">Recent Attendance (Last 10)</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 mb-4">
                            @foreach($attendances->take(10)->reverse() as $att)
                                <div class="flex-fill rounded-pill"
                                    title="{{ $att->attendance_date->format('M d') }}: {{ ucfirst($att->status) }}"
                                    style="height: 30px; background-color: {{ $att->status === 'present' ? '#198754' : ($att->status === 'late' ? '#ffc107' : '#dc3545') }};">
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span><i class="bi bi-circle-fill text-success small me-1"></i> Present</span>
                            <span><i class="bi bi-circle-fill text-warning small me-1"></i> Late</span>
                            <span><i class="bi bi-circle-fill text-danger small me-1"></i> Absent</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline & Participation -->
            <div class="col-xl-8 col-lg-7">
                <!-- Navigation Tabs -->
                <ul class="nav nav-pills gap-2 mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 shadow-sm" data-bs-toggle="pill"
                            data-bs-target="#pills-events">
                            Competition History
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 shadow-sm" data-bs-toggle="pill"
                            data-bs-target="#pills-attendance">
                            Full Attendance Ledger
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <!-- Events History -->
                    <div class="tab-pane fade show active" id="pills-events">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr class="small text-muted text-uppercase">
                                                <th class="ps-4 border-0 py-3">Event Date</th>
                                                <th class="border-0 py-3">Competition</th>
                                                <th class="border-0 py-3">Achievement</th>
                                                <th class="border-0 py-3 pe-4 text-end">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($student->events as $event)
                                                <tr>
                                                    <td class="ps-4 border-0">
                                                        <div class="fw-bold text-dark">
                                                            {{ $event->event_date->format('d M, Y') }}</div>
                                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>
                                                            {{ $event->event_date->format('h:i A') }}</small>
                                                    </td>
                                                    <td class="border-0">
                                                        <span class="fw-bold text-dark">{{ $event->title }}</span><br>
                                                        <small class="text-muted">{{ $event->location }}</small>
                                                    </td>
                                                    <td class="border-0">
                                                        @if($event->pivot->rank)
                                                            @php
                                                                $medalColor = $event->pivot->rank == 1 ? '#ffd700' : ($event->pivot->rank == 2 ? '#c0c0c0' : ($event->pivot->rank == 3 ? '#cd7f32' : ''));
                                                            @endphp
                                                            <span class="badge rounded-pill px-3 py-2 border-0"
                                                                style="background-color: {{ $medalColor ?: '#6c757d' }}; color: {{ $event->pivot->rank <= 3 ? '#000' : '#fff' }};">
                                                                <i class="bi bi-trophy-fill me-1"></i>
                                                                {{ $event->pivot->rank }}{{ date('S', mktime(0, 0, 0, 0, $event->pivot->rank, 2000)) }}
                                                                Place
                                                            </span>
                                                        @elseif($event->pivot->participation_status === 'participated')
                                                            <span
                                                                class="badge bg-info-subtle text-info rounded-pill px-3 py-2 border border-info">Participated</span>
                                                        @else
                                                            <span class="text-muted small">Registered</span>
                                                        @endif
                                                    </td>
                                                    <td class="border-0 pe-4 text-end">
                                                        <button class="btn btn-sm btn-light border-0 rounded-circle active"
                                                            title="{{ $event->pivot->notes ?? 'No coaching notes.' }}">
                                                            <i class="bi bi-info-circle"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-5 text-muted small">
                                                        <i class="bi bi-award fs-1 d-block mb-2 opacity-25"></i>
                                                        No competition records found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Ledger -->
                    <div class="tab-pane fade" id="pills-attendance">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-body p-0 text-dark">
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light position-sticky top-0" style="z-index: 10;">
                                            <tr class="small text-muted text-uppercase">
                                                <th class="ps-4 border-0 py-3">Date</th>
                                                <th class="border-0 py-3">Session Batch</th>
                                                <th class="border-0 py-3 text-center">Status</th>
                                                <th class="border-0 py-3 pe-4">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($attendances as $att)
                                                <tr>
                                                    <td class="ps-4 border-0 fw-bold">
                                                        {{ $att->attendance_date->format('d M, Y') }}</td>
                                                    <td class="border-0">{{ $att->batch->name ?? 'N/A' }}</td>
                                                    <td class="border-0 text-center">
                                                        @php
                                                            $attStatusClass = [
                                                                'present' => 'bg-success',
                                                                'absent' => 'bg-danger',
                                                                'late' => 'bg-warning',
                                                                'excused' => 'bg-info'
                                                            ][$att->status] ?? 'bg-secondary';
                                                        @endphp
                                                        <span
                                                            class="badge {{ $attStatusClass }} rounded-pill px-3 py-2 shadow-none small">
                                                            {{ strtoupper($att->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="border-0 pe-4 small text-muted text-wrap">
                                                        {{ $att->remarks ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-gradient {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .tiny {
            font-size: 0.65rem;
        }
    </style>
@endsection