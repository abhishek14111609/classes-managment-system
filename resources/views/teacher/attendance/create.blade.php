@extends('layouts.app')

@section('title', 'Smart Attendance Mark')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Premium Header -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-calendar-check-fill text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">Digital Presence Portal</h4>
                                <p class="text-white-50 mb-0 small">High-Fidelity Biometric & Physical Verification</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('teacher.dashboard') }}"
                                class="btn btn-light bg-white border-0 rounded-pill px-4 shadow-sm fw-bold">
                                <i class="bi bi-arrow-left me-1"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show p-4 mb-4"
                role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-shield-check fs-2 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Registry Synchronized</h6>
                        <small>{{ session('success') }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Control Deck: Filter Area -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <form action="{{ route('teacher.attendance.create') }}" method="GET" id="attendanceFilterForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Selected Operational Batch</label>
                                    <div class="input-group overflow-hidden rounded-4 border">
                                        <span class="input-group-text bg-white border-0 ps-3">
                                            <i class="bi bi-diagram-3-fill text-primary"></i>
                                        </span>
                                        <select class="form-select border-0 py-3 shadow-none bg-white fw-bold"
                                            name="batch_id" onchange="this.form.submit()">
                                            <option value="">Awaiting Batch Selection...</option>
                                            @foreach ($batches as $batch)
                                                <option value="{{ $batch->id }}"
                                                    {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                                    {{ $batch->name }}
                                                    ({{ $batch->start_time ? $batch->start_time->format('h:i A') : 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label tiny fw-bold text-muted text-uppercase"
                                        style="letter-spacing: 1px;">Execution Date</label>
                                    <div class="input-group overflow-hidden rounded-4 border">
                                        <span class="input-group-text bg-white border-0 ps-3">
                                            <i class="bi bi-calendar-event-fill text-primary"></i>
                                        </span>
                                        <input type="date"
                                            class="form-control border-0 py-3 shadow-none bg-white fw-bold"
                                            name="attendance_date" value="{{ request('attendance_date', date('Y-m-d')) }}"
                                            onchange="this.form.submit()">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <button type="submit"
                                        class="btn btn-primary bg-gradient-brand border-0 w-100 rounded-pill py-3 fw-bold shadow-lg">
                                        <i class="bi bi-arrow-repeat me-2"></i> Sync Interface
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if ($students && $students->count() > 0)
            @if ($pendingReviews && $pendingReviews->count() > 0)
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                                <h5 class="fw-bold mb-0 text-dark">Pending Photo Reviews</h5>
                                <p class="text-muted small mb-0">Approve or reject pending uploads</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive rounded-4 border overflow-hidden">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr class="tiny fw-bold text-muted text-uppercase">
                                                <th class="ps-4 py-3 border-0">Student</th>
                                                <th class="py-3 border-0">Date</th>
                                                <th class="py-3 border-0">Submitted</th>
                                                <th class="py-3 border-0">Photo</th>
                                                <th class="pe-4 py-3 border-0 text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pendingReviews as $pending)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="fw-bold text-dark">
                                                            {{ $pending->student->user->name ?? 'Student' }}</div>
                                                        <div class="small text-muted">
                                                            #{{ $pending->student->roll_number ?? '--' }}</div>
                                                    </td>
                                                    <td>{{ $pending->attendance_date->format('d M, Y') }}</td>
                                                    <td>{{ $pending->photo_submitted_at ? $pending->photo_submitted_at->format('h:i A') : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if ($pending->photo_path)
                                                            <img src="{{ Storage::url($pending->photo_path) }}"
                                                                alt="Attendance photo" class="rounded-3 border shadow-sm"
                                                                style="width: 56px; height: 56px; object-fit: cover; cursor: pointer;"
                                                                onclick="previewPhoto('{{ Storage::url($pending->photo_path) }}', '{{ $pending->student->user->name ?? 'Student' }}', '{{ $pending->photo_submitted_at ? $pending->photo_submitted_at->format('h:i A') : 'N/A' }}')">
                                                        @else
                                                            <span class="text-muted small">No photo</span>
                                                        @endif
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <div class="d-inline-flex gap-2">
                                                            <form
                                                                action="{{ route('teacher.attendance.approve-photo', $pending) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-success rounded-pill">Approve</button>
                                                            </form>
                                                            <form
                                                                action="{{ route('teacher.attendance.reject-photo', $pending) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger rounded-pill">Reject</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Master Attendance Registry -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div
                            class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Active Participant Roster</h5>
                                <p class="text-muted small">Target Batch:
                                    {{ $batches->firstWhere('id', request('batch_id'))->name ?? 'Selected' }}</p>
                            </div>
                            <button type="button"
                                class="btn btn-success btn-sm rounded-pill px-4 py-2 shadow-sm fw-bold border-0"
                                onclick="markAllPresent()">
                                <i class="bi bi-check-all fs-5 me-1"></i> Global Present Mark
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('teacher.attendance.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                                <input type="hidden" name="attendance_date"
                                    value="{{ request('attendance_date', date('Y-m-d')) }}">

                                <div class="table-responsive rounded-4 border overflow-hidden">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr class="tiny fw-bold text-muted text-uppercase">
                                                <th class="ps-4 py-3 border-0">Athlete Identifier</th>
                                                <th class="py-3 border-0">Internal ID</th>
                                                <th class="py-3 border-0 text-center">Engagement Status</th>
                                                <th class="pe-4 py-3 border-0">Field Performance Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $student)
                                                @php
                                                    $existingAttendance = isset($attendanceRecords)
                                                        ? $attendanceRecords->where('student_id', $student->id)->first()
                                                        : null;
                                                @endphp
                                                <tr
                                                    class="{{ $existingAttendance && $existingAttendance->verification_status === 'pending' ? 'bg-primary bg-opacity-5' : '' }}">
                                                    <td class="ps-4 py-4 border-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-container position-relative me-3">
                                                                @if ($student->user->avatar)
                                                                    <img src="{{ asset('storage/' . $student->user->avatar) }}"
                                                                        class="rounded-circle shadow-sm border"
                                                                        style="width: 48px; height: 48px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                                                                        style="width: 48px; height: 48px; border: 2px solid white;">
                                                                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                                @if ($existingAttendance && $existingAttendance->photo_path)
                                                                    <span
                                                                        class="position-absolute bottom-0 end-0 bg-info rounded-circle border-2 border-white"
                                                                        style="width: 14px; height: 14px;"
                                                                        title="Media Uploaded"></span>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <h6 class="fw-bold text-dark mb-0 me-2">
                                                                        {{ $student->user->name }}</h6>
                                                                    @if ($existingAttendance && $existingAttendance->photo_path)
                                                                        <button type="button"
                                                                            class="badge bg-primary bg-opacity-10 text-primary border-0 rounded-pill px-2 py-1 tiny fw-bold cursor-pointer"
                                                                            onclick="previewPhoto('{{ Storage::url($existingAttendance->photo_path) }}', '{{ $student->user->name }}', '{{ $existingAttendance->photo_submitted_at ? $existingAttendance->photo_submitted_at->format('h:i A') : 'N/A' }}')">
                                                                            <i class="bi bi-camera-fill me-1"></i> VIEW
                                                                            PHOTO
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                                @if ($existingAttendance && $existingAttendance->verification_status === 'pending')
                                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                                        <span
                                                                            class="badge bg-warning text-dark tiny fw-bold animate-pulse">
                                                                            <i
                                                                                class="bi bi-exclamation-triangle-fill me-1"></i>
                                                                            VERIFICATION REQ
                                                                        </span>
                                                                        <small class="text-primary tiny fw-bold">
                                                                            <i class="bi bi-clock-fill me-1"></i>
                                                                            {{ $existingAttendance->photo_submitted_at ? $existingAttendance->photo_submitted_at->format('h:i A') : 'N/A' }}
                                                                        </small>
                                                                    </div>
                                                                @elseif ($existingAttendance && $existingAttendance->verification_status)
                                                                    @php
                                                                        $verificationTheme =
                                                                            [
                                                                                'approved' => 'success',
                                                                                'rejected' => 'danger',
                                                                            ][
                                                                                $existingAttendance->verification_status
                                                                            ] ?? 'secondary';
                                                                    @endphp
                                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                                        <span
                                                                            class="badge bg-{{ $verificationTheme }} text-white tiny fw-bold">
                                                                            {{ strtoupper($existingAttendance->verification_status) }}
                                                                        </span>
                                                                        @if ($existingAttendance->reviewed_at)
                                                                            <small class="text-muted tiny fw-bold">
                                                                                <i
                                                                                    class="bi bi-check-circle-fill me-1"></i>
                                                                                {{ $existingAttendance->reviewed_at->format('h:i A') }}
                                                                            </small>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <small
                                                                        class="text-muted tiny fw-bold mt-1 d-block">{{ $student->user->email }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="border-0">
                                                        <code
                                                            class="text-primary fw-bold">#{{ $student->roll_number }}</code>
                                                    </td>
                                                    <td class="border-0 text-center">
                                                        <input type="hidden"
                                                            name="attendances[{{ $student->id }}][student_id]"
                                                            value="{{ $student->id }}">
                                                        <div class="btn-group p-1 bg-light rounded-pill border overflow-hidden shadow-sm"
                                                            style="height: 48px;">
                                                            @php
                                                                $isPending =
                                                                    $existingAttendance &&
                                                                    $existingAttendance->verification_status ===
                                                                        'pending';
                                                            @endphp

                                                            <input type="radio" class="btn-check"
                                                                name="attendances[{{ $student->id }}][status]"
                                                                value="present" id="present{{ $student->id }}"
                                                                {{ ($existingAttendance && $existingAttendance->status === 'present') || !$existingAttendance || ($existingAttendance && $existingAttendance->verification_status === 'pending') ? 'checked' : '' }}>
                                                            <label
                                                                class="btn btn-modern-status btn-p rounded-pill d-flex align-items-center justify-content-center px-4"
                                                                for="present{{ $student->id }}">
                                                                <span
                                                                    class="fw-bold">{{ $isPending ? 'APPROVE' : 'PRESENT' }}</span>
                                                            </label>

                                                            <input type="radio" class="btn-check"
                                                                name="attendances[{{ $student->id }}][status]"
                                                                value="absent" id="absent{{ $student->id }}"
                                                                {{ $existingAttendance && $existingAttendance->status === 'absent' ? 'checked' : '' }}>
                                                            <label
                                                                class="btn btn-modern-status btn-a rounded-pill d-flex align-items-center justify-content-center px-4"
                                                                for="absent{{ $student->id }}">
                                                                <span
                                                                    class="fw-bold">{{ $isPending ? 'REJECT' : 'ABSENT' }}</span>
                                                            </label>

                                                            <input type="radio" class="btn-check"
                                                                name="attendances[{{ $student->id }}][status]"
                                                                value="late" id="late{{ $student->id }}"
                                                                {{ $existingAttendance && $existingAttendance->status === 'late' ? 'checked' : '' }}>
                                                            <label
                                                                class="btn btn-modern-status btn-l rounded-pill d-flex align-items-center justify-content-center px-4"
                                                                for="late{{ $student->id }}">
                                                                <span class="fw-bold">LATE</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="pe-4 border-0">
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-light border-0"><i
                                                                    class="bi bi-pencil-square opacity-50"></i></span>
                                                            <input type="text"
                                                                class="form-control border-0 bg-light py-2 shadow-none px-3"
                                                                name="attendances[{{ $student->id }}][remarks]"
                                                                value="{{ $existingAttendance->remarks ?? '' }}"
                                                                placeholder="Add performance metrics...">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4 pt-4 border-top d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i> Marks for <strong>Presence</strong> will be
                                        auto-accepted for pending biometric uploads.
                                    </div>
                                    <button type="submit"
                                        class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-lg fw-bold bg-gradient-brand border-0">
                                        <i class="bi bi-shield-lock-fill me-2"></i> Commit Attendance State
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5 bg-white shadow-sm rounded-4 border border-dashed">
                <div class="py-5">
                    <i class="bi bi-terminal-split display-1 text-muted opacity-10"></i>
                    <h5 class="fw-bold text-dark mt-4">Awaiting Signal Synchronization</h5>
                    <p class="text-muted">Select an active operational batch from the deck above to start deployment.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Perspective Modal -->
    <div class="modal fade backdrop-blur" id="photoPerspectiveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-5 overflow-hidden bg-dark">
                <div class="modal-header border-0 bg-transparent px-4 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-pill px-3 py-1 tiny fw-bold text-white me-3">LIVE IMAGE FEED</div>
                        <h6 class="modal-title fw-bold text-white" id="perspectiveTitle">Perspective View</h6>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div
                        class="rounded-4 overflow-hidden shadow-lg border border-white border-opacity-10 position-relative">
                        <img src="" id="perspectiveImg" class="img-fluid"
                            style="width: 100%; max-height: 65vh; object-fit: contain;">
                        <div
                            class="position-absolute bottom-0 start-0 w-100 p-3 bg-dark bg-opacity-50 backdrop-blur d-flex justify-content-between align-items-center text-white">
                            <small class="tiny fw-bold"><i class="bi bi-cpu me-1"></i> Physical Verification
                                Record</small>
                            <small class="tiny fw-bold" id="perspectiveTime"><i class="bi bi-clock me-1"></i> SYMPTOM
                                TIME: --:--</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button"
                        class="btn btn-outline-white border-white border-opacity-25 rounded-pill px-5 fw-bold text-white"
                        data-bs-dismiss="modal">Close Perspective</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-modern-status {
            border: none !important;
            font-size: 0.75rem;
            color: #94a3b8;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .btn-check:checked+.btn-modern-status {
            color: white !important;
        }

        .btn-check:checked+.btn-p {
            background-color: #10b981 !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-check:checked+.btn-a {
            background-color: #ef4444 !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .btn-check:checked+.btn-l {
            background-color: #f59e0b !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .btn-modern-status:hover:not(:checked) {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(0.95);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endpush

@section('scripts')
    <script>
        function markAllPresent() {
            Swal.fire({
                title: 'Registry Sync?',
                text: 'Synchronize all active athletes to Present state?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Initialize Global Present',
                customClass: {
                    container: 'backdrop-blur'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelectorAll('input[type="radio"][value="present"]').forEach(radio => {
                        radio.checked = true;
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'State synchronized: All Present'
                    });
                }
            })
        }

        function previewPhoto(url, name, time) {
            document.getElementById('perspectiveImg').src = url;
            document.getElementById('perspectiveTitle').textContent = name + "'s Deployment Feed";
            document.getElementById('perspectiveTime').innerHTML = '<i class="bi bi-clock me-1"></i> CAPTURE TIME: ' + time;
            new bootstrap.Modal(document.getElementById('photoPerspectiveModal')).show();
        }
    </script>
@endsection
