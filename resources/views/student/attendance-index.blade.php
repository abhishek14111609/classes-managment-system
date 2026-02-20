@extends('layouts.app')

@section('title', 'Attendance History')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4 mb-5 pb-2">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden" style="border-radius: 1.5rem;">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Attendance rate</p>
                        <h2 class="fw-bold mb-0">{{ $summary['percentage'] }}%</h2>
                        <i class="bi bi-percent position-absolute end-0 bottom-0 opacity-10"
                            style="font-size: 5rem; margin-right: -10px; margin-bottom: -15px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white overflow-hidden" style="border-radius: 1.5rem;">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Present Days</p>
                        <h2 class="fw-bold mb-0">{{ $summary['present'] }}</h2>
                        <i class="bi bi-check-circle position-absolute end-0 bottom-0 opacity-10"
                            style="font-size: 5rem; margin-right: -10px; margin-bottom: -15px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white overflow-hidden" style="border-radius: 1.5rem;">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Absent Days</p>
                        <h2 class="fw-bold mb-0">{{ $summary['absent'] }}</h2>
                        <i class="bi bi-x-circle position-absolute end-0 bottom-0 opacity-10"
                            style="font-size: 5rem; margin-right: -10px; margin-bottom: -15px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-dark text-white overflow-hidden" style="border-radius: 1.5rem;">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Total Sessions</p>
                        <h2 class="fw-bold mb-0">{{ $summary['total_days'] }}</h2>
                        <i class="bi bi-collection position-absolute end-0 bottom-0 opacity-10"
                            style="font-size: 5rem; margin-right: -10px; margin-bottom: -15px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('student.attendance.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">START DATE</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">END DATE</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-funnel"></i> Filter Records
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-light border w-100 py-2" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th class="text-center">Status</th>
                            <th>Timing</th>
                            <th>Instructor Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $attendance->attendance_date->format('M d, Y') }}<br><small
                                        class="text-muted">{{ $attendance->attendance_date->format('l') }}</small></td>
                                <td class="text-center">
                                    @php
                                        $statusClass = [
                                            'present' => 'success',
                                            'absent' => 'danger',
                                            'late' => 'warning',
                                            'excused' => 'info'
                                        ][$attendance->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} rounded-pill px-3 py-2">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td><i class="bi bi-clock me-1 text-muted"></i> 09:00 AM</td>
                                <td class="text-muted small">{{ $attendance->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="opacity-25 mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
                                    <p class="text-muted mb-0">No attendance records found for this period.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection