@extends('layouts.app')

@section('title', 'Mark Digital Attendance')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
        <div>
            <h3 class="fw-bold mb-0 text-gradient">Smart Attendance Tracking</h3>
            <p class="text-muted small mb-0">Record and monitor student presence for your assigned batches.</p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show p-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-0">Action Successful</h6>
                    <small>{{ session('success') }}</small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Selection Filter -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('teacher.attendance.create') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">MY ASSIGNED BATCH</label>
                        <select class="form-select rounded-3 py-2 border-0 bg-light" id="batch_id" name="batch_id" onchange="this.form.submit()">
                            <option value="">Choose a batch to start...</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->name }} ({{ $batch->start_time ? $batch->start_time->format('h:i A') : 'N/A' }} - {{ $batch->end_time ? $batch->end_time->format('h:i A') : 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">ATTENDANCE DATE</label>
                        <input type="date" class="form-control rounded-3 py-2 border-0 bg-light" id="attendance_date" name="attendance_date"
                               value="{{ request('attendance_date', date('Y-m-d')) }}" onchange="this.form.submit()">
                    </div>
                    
                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-secondary w-100 rounded-pill py-2" onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh List
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($students && $students->count() > 0)
        <!-- Attendance Form -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Batch Roster <span class="text-muted fw-normal fs-6">({{ $students->count() }} students)</span></h5>
                <button type="button" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" onclick="markAllPresent()">
                    <i class="bi bi-check-all me-1"></i> Smart Mark All Present
                </button>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('teacher.attendance.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                    <input type="hidden" name="attendance_date" value="{{ request('attendance_date', date('Y-m-d')) }}">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-4 border-0 py-3">STUDENT</th>
                                    <th class="border-0 py-3">ROLL NO.</th>
                                    <th class="border-0 py-3 text-center">PRESENCE STATUS</th>
                                    <th class="border-0 py-3 pe-4">COACH REMARKS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    @php
                                        $existingAttendance = isset($attendanceRecords) ?
                                            $attendanceRecords->where('student_id', $student->id)->first() : null;
                                    @endphp
                                    <tr class="{{ $existingAttendance && $existingAttendance->verification_status === 'pending' ? 'bg-warning bg-opacity-10' : '' }}">
                                        <td class="ps-4 border-0">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3 text-primary d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                    <span class="fw-bold small">{{ strtoupper(substr($student->user->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark w-100 d-flex gap-2 align-items-center">
                                                        {{ $student->user->name }}
                                                        @if($existingAttendance && $existingAttendance->photo_path)
                                                            <a href="{{ Storage::url($existingAttendance->photo_path) }}" target="_blank" class="badge bg-info text-decoration-none" title="View Uploaded Photo">
                                                                <i class="bi bi-camera me-1"></i> View Photo
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted tiny">Student ID: #{{ $student->id }}</small>
                                                    @if($existingAttendance && $existingAttendance->verification_status === 'pending')
                                                        <br><small class="text-warning fw-bold"><i class="bi bi-exclamation-triangle me-1"></i> Needs Approval</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-0 fw-bold text-muted small">#{{ $student->roll_number }}</td>
                                        <td class="border-0 text-center">
                                            <input type="hidden" name="attendances[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                            <div class="attendance-btn-group d-inline-flex border rounded-pill p-1 bg-light">
                                                <input type="radio" class="btn-check"
                                                       name="attendances[{{ $student->id }}][status]"
                                                       value="present" id="present{{ $student->id }}"
                                                       {{ ($existingAttendance && $existingAttendance->status === 'present') || (!$existingAttendance) || ($existingAttendance && $existingAttendance->verification_status === 'pending') ? 'checked' : '' }}>
                                                <label class="btn btn-attendance btn-present rounded-pill" for="present{{ $student->id }}" title="Present/Approve">P</label>

                                                <input type="radio" class="btn-check"
                                                       name="attendances[{{ $student->id }}][status]"
                                                       value="absent" id="absent{{ $student->id }}"
                                                       {{ $existingAttendance && $existingAttendance->status === 'absent' ? 'checked' : '' }}>
                                                <label class="btn btn-attendance btn-absent rounded-pill" for="absent{{ $student->id }}" title="Absent/Reject">A</label>

                                                <input type="radio" class="btn-check"
                                                       name="attendances[{{ $student->id }}][status]"
                                                       value="late" id="late{{ $student->id }}"
                                                       {{ $existingAttendance && $existingAttendance->status === 'late' ? 'checked' : '' }}>
                                                <label class="btn btn-attendance btn-late rounded-pill" for="late{{ $student->id }}" title="Late">L</label>
                                            </div>
                                        </td>
                                        <td class="pe-4 border-0">
                                            <input type="text" class="form-control form-control-sm border-0 bg-light rounded-pill px-3"
                                                   name="attendances[{{ $student->id }}][remarks]"
                                                   value="{{ $existingAttendance->remarks ?? '' }}"
                                                   placeholder="Add coaching remarks...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 bg-light bg-opacity-50 text-end border-top">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm fw-bold">
                            <i class="bi bi-shield-check me-2"></i> Confirm & Finalize Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif(request('batch_id'))
        <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
            <div class="opacity-25 mb-4"><i class="bi bi-people fs-1" style="font-size: 5rem !important;"></i></div>
            <h4 class="fw-bold">No Students Assigned</h4>
            <p class="text-muted">This batch appears to be empty. Please check with your administrator.</p>
        </div>
    @else
        <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
            <div class="opacity-10 mb-4"><i class="bi bi-search" style="font-size: 5rem !important;"></i></div>
            <h4 class="fw-bold text-muted">Ready to Track?</h4>
            <p class="text-muted">Select a batch from the filter above to load the student list.</p>
        </div>
    @endif
</div>

<style>
.btn-attendance {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none !important;
    font-weight: bold;
    color: #6c757d;
    transition: all 0.2s;
}
.btn-attendance:hover { background: rgba(0,0,0,0.05); }

.btn-check:checked + .btn-present { background-color: #198754 !important; color: white !important; box-shadow: 0 4px 6px -1px rgba(25, 135, 84, 0.4); }
.btn-check:checked + .btn-absent { background-color: #dc3545 !important; color: white !important; box-shadow: 0 4px 6px -1px rgba(220, 53, 69, 0.4); }
.btn-check:checked + .btn-late { background-color: #ffc107 !important; color: white !important; box-shadow: 0 4px 6px -1px rgba(255, 193, 7, 0.4); }

.transition-all { transition: all 0.3s ease; }
.hover-lift:hover { transform: translateY(-3px); }
</style>

<script>
function markAllPresent() {
    Swal.fire({
        title: 'Smart Mark All?',
        text: "This will set all students in this list to 'Present'.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, All Present!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelectorAll('input[type="radio"][value="present"]').forEach(radio => {
                radio.checked = true;
            });
            Toast.fire({
                icon: 'success',
                title: 'All students marked as present'
            });
        }
    })
}
</script>
@endsection
