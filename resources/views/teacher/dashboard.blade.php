@extends('layouts.app')

@section('title', 'Teacher Console')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
            <div>
                <h3 class="fw-bold mb-0 text-gradient">Coach Dashboard</h3>
                <p class="text-muted small mb-0">Welcome back, <strong>{{ $teacher->user->name }}</strong>. Here's what's
                    happening today.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('teacher.attendance.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Mark Attendance
                </a>
            </div>
        </div>

        <!-- Premium Stat Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card primary h-100">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-3"><i class="bi bi-people fs-4"></i></div>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Total</span>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $totalStudents }}</h2>
                    <p class="text-white-50 small mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">Assigned
                        Students</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card success h-100">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-3"><i class="bi bi-diagram-3 fs-4"></i></div>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Active</span>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $batches->count() }}</h2>
                    <p class="text-white-50 small mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">Managed Batches
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card info h-100">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-3"><i class="bi bi-trophy fs-4"></i></div>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Planned</span>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $upcomingEvents->count() }}</h2>
                    <p class="text-white-50 small mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">Upcoming Events
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card warning h-100">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-3"><i class="bi bi-graph-up fs-4"></i></div>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Rate</span>
                    </div>
                    <h2 class="fw-bold mb-1">94%</h2>
                    <p class="text-white-50 small mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">Avg. Attendance
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Batches Overview -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between">
                        <h5 class="fw-bold mb-0">My Active Programs & Batches</h5>
                        <a href="{{ route('teacher.batches.index') }}" class="small text-decoration-none">View All</a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse($batches as $batch)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-4 bg-light bg-opacity-25 hover-lift transition-all">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $batch->name }}</h6>
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 small">{{ $batch->students->count() }}
                                                Students</span>
                                        </div>
                                        <p class="text-muted small mb-3">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $batch->start_time ? $batch->start_time->format('h:i A') : 'N/A' }} –
                                            {{ $batch->end_time ? $batch->end_time->format('h:i A') : 'N/A' }}
                                        </p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('teacher.batches.students', $batch) }}"
                                                class="btn btn-sm btn-white border flex-fill rounded-pill py-2 shadow-sm">
                                                <i class="bi bi-people me-1"></i> Students
                                            </a>
                                            <a href="{{ route('teacher.attendance.create', ['batch_id' => $batch->id]) }}"
                                                class="btn btn-sm btn-primary flex-fill rounded-pill py-2 shadow-sm">
                                                Mark Today
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 opacity-25 d-block mb-3"></i>
                                    No batches assigned to you yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="fw-bold mb-0">Upcoming Sports Events & Assignments</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th class="border-0">EVENT TITLE</th>
                                        <th class="border-0">DATE & TIME</th>
                                        <th class="border-0">LOCATION</th>
                                        <th class="border-0 text-end">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($upcomingEvents as $event)
                                        <tr>
                                            <td class="border-0">
                                                <div class="fw-bold">{{ $event->title }}</div>
                                                <small class="text-muted">{{ $event->status }}</small>
                                            </td>
                                            <td class="border-0">
                                                <div class="small">{{ $event->event_date->format('d M, Y') }}</div>
                                                <div class="tiny text-muted">{{ $event->event_date->format('h:i A') }}</div>
                                            </td>
                                            <td class="border-0 small">{{ $event->location ?? 'N/A' }}</td>
                                            <td class="border-0 text-end">
                                                <a href="{{ route('teacher.events.index') }}"
                                                    class="btn btn-sm btn-light border rounded-circle"><i
                                                        class="bi bi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted small">No upcoming events.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <div class="card-header bg-white pt-4 pb-0">
                            <h5 class="fw-bold mb-0">Recent Activity Log</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="activity-timeline">
                                @forelse($recentActivities as $activity)
                                    <div class="activity-item d-flex mb-4">
                                        <div class="activity-icon shrink-0 bg-light rounded-circle p-2 text-primary me-3"
                                            style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-lightning-fill small"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="mb-0 small text-dark"><span
                                                    class="fw-bold">{{ $activity->user->name ?? 'System' }}</span>
                                                {{ $activity->description }}</p>
                                            <small class="text-muted tiny">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-muted small py-4">No recent activity logs.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection