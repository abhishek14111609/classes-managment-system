@extends('layouts.app')

@php
    $isSport = auth()->user()->school && auth()->user()->school->institute_type === 'sport';
@endphp

@section('title', $isSport ? 'Training Session Attendance' : 'Institutional Attendance Management')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
        <div>
            <h3 class="fw-bold mb-1 text-gradient">Attendance Logs</h3>
            <p class="text-muted small mb-0">
                @if($isSport)
                    Record and monitor athlete presence across training batches.
                @else
                    Record and monitor student presence across institutional batches.
                @endif
            </p>
        </div>
        <a href="{{ route('school.dashboard') }}" class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold small">
            <i class="bi bi-arrow-left me-2"></i> Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div class="small fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Selection Interface -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4 bg-light bg-opacity-50">
            <form action="{{ route('school.attendance.index') }}" method="GET">
                <div class="row g-4">
                    <div class="col-md-5">
                        <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Operational Batch <span class="text-danger">*</span></label>
                        <div class="input-group bg-white rounded-pill px-3 py-1 border shadow-sm">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-collection text-muted small"></i></span>
                            <select class="form-select bg-transparent border-0 shadow-none tiny fw-bold" name="batch_id" onchange="this.form.submit()">
                                <option value="">Identify Targeted Batch...</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }} ({{ $batch->class->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Registry Date <span class="text-danger">*</span></label>
                        <div class="input-group bg-white rounded-pill px-3 py-1 border shadow-sm">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-calendar-event text-muted small"></i></span>
                            <input type="date" class="form-control bg-transparent border-0 shadow-none tiny fw-bold" name="attendance_date"
                                   value="{{ request('attendance_date', date('Y-m-d')) }}" onchange="this.form.submit()">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($students && $students->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">Personnel Registry <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 ms-2">
                        {{ $students->count() }}
                        @if($isSport) Athletes @else Students @endif
                    </span></h6>
                    <button type="button" class="btn btn-soft-success btn-sm rounded-pill px-3 fw-bold" onclick="markAllPresent()">
                        <i class="bi bi-check-all me-1"></i> Bulk Mark Present
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('school.attendance.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                    <input type="hidden" name="attendance_date" value="{{ request('attendance_date', date('Y-m-d')) }}">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="tiny text-muted text-uppercase fw-bold">
                                    <th class="ps-4 py-3 border-0">Identity</th>
                                    <th class="py-3 border-0">
                                        @if($isSport) Athlete Profile @else Student Profile @endif
                                    </th>
                                    <th class="py-3 border-0">Status Declaration</th>
                                    <th class="pe-4 py-3 border-0">Institutional Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    @php
                                        $existingAttendance = isset($attendanceRecords) ?
                                            $attendanceRecords->where('student_id', $student->id)->first() : null;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 border-0">
                                            <div class="fw-bold text-dark small">{{ $student->roll_number }}</div>
                                            <small class="text-muted tiny">ROLL ID</small>
                                        </td>
                                        <td class="border-0">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if($student->photo)
                                                        <img src="{{ Storage::url($student->photo) }}" class="rounded-circle shadow-sm" width="40" height="40">
                                                    @else
                                                        <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                                            {{ substr($student->user->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="fw-bold text-dark small">{{ $student->user->name }}</div>
                                            </div>
                                        </td>
                                        <td class="border-0">
                                            <input type="hidden" name="attendances[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                            <div class="btn-group shadow-none" role="group">
                                                <input type="radio" class="btn-check" name="attendances[{{ $student->id }}][status]"
                                                       value="present" id="present{{ $student->id }}"
                                                       {{ ($existingAttendance && $existingAttendance->status === 'present') || (!$existingAttendance) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success btn-xs px-3" for="present{{ $student->id }}">P</label>

                                                <input type="radio" class="btn-check" name="attendances[{{ $student->id }}][status]"
                                                       value="absent" id="absent{{ $student->id }}"
                                                       {{ $existingAttendance && $existingAttendance->status === 'absent' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger btn-xs px-3" for="absent{{ $student->id }}">A</label>

                                                <input type="radio" class="btn-check" name="attendances[{{ $student->id }}][status]"
                                                       value="late" id="late{{ $student->id }}"
                                                       {{ $existingAttendance && $existingAttendance->status === 'late' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-warning btn-xs px-3" for="late{{ $student->id }}">L</label>

                                                <input type="radio" class="btn-check" name="attendances[{{ $student->id }}][status]"
                                                       value="excused" id="excused{{ $student->id }}"
                                                       {{ $existingAttendance && $existingAttendance->status === 'excused' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-info btn-xs px-3" for="excused{{ $student->id }}">E</label>
                                            </div>
                                        </td>
                                        <td class="pe-4 border-0">
                                            <input type="text" class="form-control form-control-sm rounded-pill border-0 bg-light px-3 tiny fw-bold"
                                                   name="attendances[{{ $student->id }}][remarks]"
                                                   value="{{ $existingAttendance->remarks ?? '' }}"
                                                   placeholder="Registry annotation...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-white p-4 border-top">
                        <button type="submit" class="btn btn-primary grow-on-hover rounded-pill px-5 py-2 fw-bold shadow-sm border-0 d-flex align-items-center mx-auto">
                            <i class="bi bi-shield-check me-2"></i> Commit Attendance Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif(request('batch_id'))
        <div class="alert alert-soft-info border-0 shadow-sm rounded-4 p-4 d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-3 me-3"></i>
            <div>
                <h6 class="fw-bold mb-1">Inconclusive Search</h6>
                <p class="mb-0 small">No institutional personnel identified in the selected batch. Please verify batch assignments.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="opacity-25 mb-3"><i class="bi bi-calendar-range" style="font-size: 5rem;"></i></div>
            <h5 class="text-muted fw-bold">Ready for Registry</h5>
            <p class="text-muted small">Select an institutional batch and date to initiate the attendance logging process.</p>
        </div>
    @endif
</div>

<style>
.btn-soft-success { background-color: rgba(25, 135, 84, 0.1); color: #198754; border: none; }
.btn-soft-success:hover { background-color: #198754; color: #fff; }
.alert-soft-info { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.text-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.btn-xs { padding: 0.15rem 0.6rem; font-size: 0.7rem; font-weight: 800; border-radius: 4px; }
.tiny { font-size: 0.65rem; letter-spacing: 0.5px; }
.grow-on-hover:hover { transform: scale(1.02); transition: all 0.2s; }
</style>

<script>
function markAllPresent() {
    document.querySelectorAll('input[type="radio"][value="present"]').forEach(radio => {
        radio.checked = true;
    });
}
</script>
@endsection
