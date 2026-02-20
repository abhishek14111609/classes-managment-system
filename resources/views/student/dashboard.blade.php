@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        @if ($stats['pending_fees'] > 0)
            <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4">
                <i class="bi bi-exclamation-octagon fs-4 me-3"></i>
                <div>
                    <h6 class="mb-0 fw-bold">Pending Payment Due</h6>
                    <p class="mb-0 small">You have an outstanding balance of
                        <strong>₹{{ number_format($stats['pending_fees'], 2) }}</strong>. Please clear your dues to avoid
                        late
                        fees.
                    </p>
                </div>
                <a href="{{ route('student.fees.index') }}" class="btn btn-danger btn-sm ms-auto px-4">Pay Now</a>
            </div>
        @endif

        <div class="row">
            <div class="col-md-3">
                <div class="stat-card info">
                    <h3>{{ $stats['attendance_percentage'] }}%</h3>
                    <p><i class="bi bi-calendar-check"></i> Attendance</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card success">
                    <h3>₹{{ number_format($stats['paid_fees'], 2) }}</h3>
                    <p><i class="bi bi-check-circle"></i> Paid Fees</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card danger">
                    <h3>₹{{ number_format($stats['pending_fees'], 2) }}</h3>
                    <p><i class="bi bi-exclamation-circle"></i> Still Pending</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card primary">
                    <h3>{{ $stats['events_participated'] }}</h3>
                    <p><i class="bi bi-trophy"></i> Participations</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge text-primary me-2"></i>My Profile</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless align-middle">
                            <tr class="border-bottom border-light">
                                <th width="40%" class="text-muted small">ROLL NUMBER</th>
                                <td class="fw-bold text-dark">{{ $student->roll_number ?? 'Not assigned' }}</td>
                                {{-- Top Stats Cards --}}
                                <div class="row g-4 mb-5 pb-3">
                                    <div class="col-md-4">
                                        <div class="stat-card primary h-100 mb-0">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <p class="text-white-50 small fw-bold text-uppercase">Financial Balance</p>
                                                <i class="bi bi-wallet2 fs-4"></i>
                                            </div>
                                            <h3>₹{{ number_format($balance, 2) }}</h3>
                                            <div
                                                class="mt-3 pt-3 border-top border-white border-opacity-10 d-flex justify-content-between">
                                                <small class="text-white-50">Total Paid:
                                                    ₹{{ number_format($paidFees, 2) }}</small>
                                                <i class="bi bi-arrow-right-short"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card success h-100 mb-0">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <p class="text-white-50 small fw-bold text-uppercase">Attendance Rate</p>
                                                <i class="bi bi-calendar-check fs-4"></i>
                                            </div>
                                            <h3>{{ $attendanceRate }}%</h3>
                                            <div
                                                class="mt-3 pt-3 border-top border-white border-opacity-10 d-flex justify-content-between">
                                                <small class="text-white-50">Present Days: {{ $presentDays }}</small>
                                                <div class="bg-white bg-opacity-20 rounded-pill px-2 small">Top 10%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card info h-100 mb-0">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <p class="text-white-50 small fw-bold text-uppercase">Academic Batch</p>
                                                <i class="bi bi-mortarboard fs-4"></i>
                                            </div>
                                            <h3 class="fs-4">{{ $student->batch->name ?? 'Unassigned' }}</h3>
                                            <div class="mt-3 pt-3 border-top border-white border-opacity-10">
                                                <small class="text-white-50">Instructor:
                                                    {{ $student->batch->teachers->first()->user->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4 mb-5">
                                    {{-- Batch Details & Quick Actions --}}
                                    <div class="col-lg-8">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div
                                                class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                                                <h5 class="fw-bold mb-0">Academic Overview</h5>
                                                <a href="{{ route('student.timetable') }}"
                                                    class="btn btn-sm btn-light border">Full Schedule</a>
                                            </div>
                                            <div class="card-body p-4">
                                                @if ($student->batch)
                                                    <div class="p-4 rounded-4 position-relative overflow-hidden mb-4"
                                                        style="background: rgba(79, 70, 229, 0.03);">
                                                        <div class="row align-items-center position-relative z-1">
                                                            <div class="col-md-7">
                                                                <h4 class="fw-bold mb-1">{{ $student->batch->class->name }}
                                                                </h4>
                                                                <p class="text-muted small mb-3">Advanced Course •
                                                                    {{ ucfirst($student->batch->class->type) }}</p>
                                                                <div class="d-flex gap-3">
                                                                    <div
                                                                        class="d-flex align-items-center bg-white px-3 py-2 rounded-3 shadow-sm border">
                                                                        <i class="bi bi-clock text-primary me-2"></i>
                                                                        <span
                                                                            class="small fw-bold">{{ $student->batch->time ?? '09:00 AM' }}</span>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-center bg-white px-3 py-2 rounded-3 shadow-sm border">
                                                                        <i class="bi bi-geo-alt text-danger me-2"></i>
                                                                        <span class="small fw-bold">Room 302</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5 text-md-end mt-4 mt-md-0">
                                                                <img src="https://img.icons8.com/bubbles/100/null/learning.png"
                                                                    alt="Learn" class="opacity-75">
                                                            </div>
                                                        </div>
                                                        <div class="position-absolute end-0 top-0 h-100 w-25 bg-primary opacity-10"
                                                            style="clip-path: polygon(100% 0, 0% 100%, 100% 100%);"></div>
                                                    </div>
                                                @else
                                                    <div class="text-center py-5 text-muted bg-light rounded-4">
                                                        <i class="bi bi-journal-x fs-1 opacity-25 d-block mb-3"></i>
                                                        Please wait while we assign you to a batch.
                                                    </div>
                                                @endif

                                                <h6 class="fw-bold mb-3 small text-muted text-uppercase"
                                                    style="letter-spacing: 0.1em;">Recent Attendance Performance</h6>
                                                <div class="table-responsive border rounded-4 pt-1">
                                                    <table class="table table-hover align-middle mb-0">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th class="border-0 small">Date</th>
                                                                <th class="border-0 small text-center">Status</th>
                                                                <th class="border-0 small">Session Topic</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($recentAttendance as $record)
                                                                <tr>
                                                                    <td class="small fw-medium">
                                                                        {{ $record->attendance_date->format('d M, Y') }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @php $sClass = $record->status == 'present' ? 'success' : ($record->status == 'absent' ? 'danger' : 'warning'); @endphp
                                                                        <span
                                                                            class="badge bg-{{ $sClass }} rounded-pill px-3">{{ ucfirst($record->status) }}</span>
                                                                    </td>
                                                                    <td class="small text-muted">
                                                                        {{ $record->remarks ?? 'Regular Session' }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3"
                                                                        class="text-center py-4 text-muted small">No
                                                                        recent attendance found.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Quick Stats & Events --}}
                                    <div class="col-lg-4">
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header bg-white border-0 pt-4 pb-0">
                                                <h5 class="fw-bold mb-0">Quick Actions</h5>
                                            </div>
                                            <div class="card-body p-4">
                                                <div class="d-grid gap-3">
                                                    <a href="{{ route('student.profile') }}"
                                                        class="btn btn-light bg-white border text-start px-3 py-3 rounded-4 shadow-sm hover-lift">
                                                        <div class="d-flex align-items-center w-100">
                                                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3"><i
                                                                    class="bi bi-person-badge text-primary fs-5"></i></div>
                                                            <div class="grow">
                                                                <h6 class="mb-0 fw-bold small">My Profile</h6>
                                                                <small class="text-muted">Personal & Academic info</small>
                                                            </div>
                                                            <i class="bi bi-chevron-right text-muted"></i>
                                                        </div>
                                                    </a>
                                                    <a href="{{ route('student.fees.index') }}"
                                                        class="btn btn-light bg-white border text-start px-3 py-3 rounded-4 shadow-sm hover-lift">
                                                        <div class="d-flex align-items-center w-100">
                                                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3"><i
                                                                    class="bi bi-cash-stack text-success fs-5"></i></div>
                                                            <div class="grow">
                                                                <h6 class="mb-0 fw-bold small">Fees & Payments</h6>
                                                                <small class="text-muted">Dues and receipts</small>
                                                            </div>
                                                            <i class="bi bi-chevron-right text-muted"></i>
                                                        </div>
                                                    </a>
                                                    <a href="{{ route('student.resources') }}"
                                                        class="btn btn-light bg-white border text-start px-3 py-3 rounded-4 shadow-sm hover-lift">
                                                        <div class="d-flex align-items-center w-100">
                                                            <div class="bg-info bg-opacity-10 p-2 rounded-3 me-3"><i
                                                                    class="bi bi-journal-bookmark text-info fs-5"></i>
                                                            </div>
                                                            <div class="grow">
                                                                <h6 class="mb-0 fw-bold small">Resources</h6>
                                                                <small class="text-muted">Download study materials</small>
                                                            </div>
                                                            <i class="bi bi-chevron-right text-muted"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-0 shadow-sm bg-dark text-white overflow-hidden"
                                            style="border-radius: 1.5rem;">
                                            <div class="card-body p-4 position-relative z-1">
                                                <h6 class="text-white-50 fw-bold mb-4 small text-uppercase"
                                                    style="letter-spacing: 0.1em;">Next Sports Event</h6>
                                                @php $nextEvent = $student->events()->upcoming()->first(); @endphp
                                                @if ($nextEvent)
                                                    <h4 class="fw-bold mb-0">{{ $nextEvent->title }}</h4>
                                                    <p class="small opacity-75 mb-4">
                                                        {{ $nextEvent->event_date->format('M d, Y') }} •
                                                        {{ $nextEvent->venue }}
                                                    </p>
                                                    <a href="{{ route('student.events.index') }}"
                                                        class="btn btn-primary bg-white text-dark rounded-pill px-4 small w-100">View
                                                        All Events</a>
                                                @else
                                                    <p class="mb-0 small opacity-50">No upcoming events scheduled at the
                                                        moment.
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Financial Ledger Section --}}
                                <div class="card border-0 shadow-sm mb-5">
                                    <div class="card-header bg-white border-0 pt-4 pb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="fw-bold mb-0">Financial History Ledger</h5>
                                            <span class="badge bg-light text-dark border">Chronological Record</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="border-0">Date</th>
                                                        <th class="border-0">Description</th>
                                                        <th class="border-0 text-end">Debit (DR)</th>
                                                        <th class="border-0 text-end">Credit (CR)</th>
                                                        <th class="border-0 text-end">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $runningBalance = 0; @endphp
                                                    @forelse($ledger as $entry)
                                                        @php
                                                            // Support both array and object ledger entries safely
                                                            $dr = data_get($entry, 'dr', 0);
                                                            $cr = data_get($entry, 'cr', 0);
                                                            $runningBalance += $dr - $cr;
                                                            $isNeg = $runningBalance > 0;
                                                            $entryDate = data_get($entry, 'date');
                                                            $desc = data_get($entry, 'description', 'N/A');
                                                            $type = data_get($entry, 'type', 'N/A');
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $entryDate ? $entryDate->format('d M, Y') : '—' }}</td>
                                                            <td>
                                                                <div class="fw-semibold">{{ $desc }}</div>
                                                                <small class="text-muted">{{ $type }}</small>
                                                            </td>
                                                            <td class="text-end text-danger fw-medium">
                                                                {{ $dr > 0 ? '₹' . number_format($dr, 2) : '-' }}</td>
                                                            <td class="text-end text-success fw-medium">
                                                                {{ $cr > 0 ? '₹' . number_format($cr, 2) : '-' }}</td>
                                                            <td
                                                                class="text-end fw-bold {{ $isNeg ? 'text-danger' : 'text-success' }}">
                                                                ₹{{ number_format(abs($runningBalance), 2) }}
                                                                {{ $isNeg ? 'DR' : 'CR' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center py-5 text-muted">No
                                                                transactions recorded yet.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                    </div>
                @endsection




                @push('styles')
                    <style>
                        .pulse {
                            animation: pulse-red 2s infinite;
                        }


                        @keyframes pulse-red {


                            0% {
                                transform: scale(0.95);
                                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
                            }

                            70% {
                                transform: scale(1);
                                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
                            }

                            100% {
                                transform: scale(0.95);
                                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
                            }
                        }
                    </style>
                @endpush
