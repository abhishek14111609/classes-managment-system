@extends('layouts.app')

@section('title', 'Manage Event: ' . $event->title)

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <a href="{{ route('teacher.events.index') }}"
                    class="btn btn-link text-decoration-none p-0 mb-1 text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Back to Events
                </a>
                <h3 class="fw-bold mb-0 text-gradient">{{ $event->title }}</h3>
                <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $event->location }} | <i
                        class="bi bi-calendar-event me-1"></i> {{ $event->event_date->format('M d, Y h:i A') }}</p>
            </div>
            <div class="d-flex gap-2">
                @if($event->status !== 'completed' && $event->status !== 'cancelled')
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#addParticipantsModal">
                        <i class="bi bi-person-plus me-1"></i> Add Students
                    </button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show p-4 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                    <div class="text-dark fw-bold">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Event Stats -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3 text-primary">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $event->students->count() }}</h2>
                        <p class="text-muted small text-uppercase mb-0">Total Participants</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-body p-4 text-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3 text-warning">
                            <i class="bi bi-trophy fs-3"></i>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $event->students->whereNotNull('pivot.rank')->count() }}</h2>
                        <p class="text-muted small text-uppercase mb-0">Achievements Recorded</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Event Status</h6>
                        <div class="d-grid">
                            @php
                                $statusClass = [
                                    'scheduled' => 'info',
                                    'upcoming' => 'info',
                                    'ongoing' => 'warning',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ][$event->status] ?? 'secondary';
                            @endphp
                            <span
                                class="badge bg-{{ $statusClass }} py-2 rounded-pill fs-6">{{ ucfirst($event->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants Table -->
            <div class="col-md-9">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between">
                        <h5 class="fw-bold mb-0">Competition Roster</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="small text-muted text-uppercase">
                                        <th class="ps-4 border-0 py-3">STUDENT</th>
                                        <th class="border-0 py-3">STATUS</th>
                                        <th class="border-0 py-3">RANK/REWARD</th>
                                        <th class="border-0 py-3 pe-4 text-end">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($event->students as $student)
                                        <tr>
                                            <td class="ps-4 border-0">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=random&color=fff&size=40"
                                                        class="rounded-circle me-2 shadow-sm">
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $student->user->name }}</div>
                                                        <small class="text-muted">{{ $student->batch->name ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-0">
                                                @php
                                                    $pStatusClass = [
                                                        'registered' => 'bg-info',
                                                        'participated' => 'bg-success',
                                                        'withdrawn' => 'bg-danger'
                                                    ][$student->pivot->participation_status] ?? 'bg-secondary';
                                                @endphp
                                                <span
                                                    class="badge rounded-pill {{ $pStatusClass }} px-3">{{ ucfirst($student->pivot->participation_status) }}</span>
                                            </td>
                                            <td class="border-0">
                                                @if($student->pivot->rank)
                                                    <span class="fw-bold text-warning"><i class="bi bi-award-fill me-1"></i> Rank
                                                        #{{ $student->pivot->rank }}</span>
                                                @else
                                                    <span class="text-muted small">Not set</span>
                                                @endif
                                            </td>
                                            <td class="border-0 pe-4 text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3"
                                                        onclick="openResultModal({{ $student->id }}, '{{ $student->user->name }}', '{{ $student->pivot->participation_status }}', '{{ $student->pivot->rank }}', '{{ $student->pivot->notes }}')">
                                                        <i class="bi bi-pencil me-1"></i> Results
                                                    </button>
                                                    <form
                                                        action="{{ route('teacher.events.participants.remove', [$event, $student]) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Remove student from this event?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger border rounded-circle"><i
                                                                class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-person-dash fs-1 d-block mb-3 opacity-25"></i>
                                                No students added to this event yet.
                                            </td>
                                        </tr>
                                        @hide
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Participants Modal -->
    <div class="modal fade" id="addParticipantsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">Add Students to Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('teacher.events.participants.add', $event) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 text-dark">
                        <p class="text-muted small mb-3">Select students from your assigned batches to participate in this
                            event.</p>
                        <div class="mb-3">
                            <div class="input-group mb-3 border rounded-pill overflow-hidden">
                                <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control border-0" id="studentSearch"
                                    placeholder="Search students by name...">
                            </div>
                        </div>
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light position-sticky top-0" style="z-index: 1;">
                                    <tr class="small text-muted text-uppercase">
                                        <th width="40" class="ps-3 border-0 py-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th class="border-0">STUDENT</th>
                                        <th class="border-0">BATCH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($availableStudents as $student)
                                        <tr class="student-row">
                                            <td class="ps-3 border-0">
                                                <div class="form-check">
                                                    <input class="form-check-input check-student" type="checkbox"
                                                        name="student_ids[]" value="{{ $student->id }}">
                                                </div>
                                            </td>
                                            <td class="border-0">
                                                <span class="fw-bold student-name">{{ $student->user->name }}</span>
                                            </td>
                                            <td class="border-0 small text-muted">{{ $student->batch->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted small">No more students available
                                                for selection.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-toggle="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4" id="submitAddBtn" disabled>Add
                            Selected Students</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Result/Achievement Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4 text-dark">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">Update Result: <span id="modalStudentName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="resultForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">PARTICIPATION STATUS</label>
                            <select class="form-select rounded-3 border-0 bg-light" name="participation_status"
                                id="modalStatus">
                                <option value="registered">Registered</option>
                                <option value="participated">Participated</option>
                                <option value="withdrawn">Withdrawn</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">RANK / POSITION (OPTIONAL)</label>
                            <input type="number" class="form-control rounded-3 border-0 bg-light" name="rank" id="modalRank"
                                placeholder="e.g. 1 for 1st place...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold text-muted">COACHING NOTES / PERFORMANCE FEEDBACK</label>
                            <textarea class="form-control rounded-3 border-0 bg-light" name="notes" id="modalNotes" rows="4"
                                placeholder="How did the student perform? Any specific strengths or weaknesses..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">Save Result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .text-gradient {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .transition-all {
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Search students
            const studentSearch = document.getElementById('studentSearch');
            if (studentSearch) {
                studentSearch.addEventListener('keyup', function () {
                    const query = this.value.toLowerCase();
                    document.querySelectorAll('.student-row').forEach(row => {
                        const name = row.querySelector('.student-name').textContent.toLowerCase();
                        row.style.display = name.includes(query) ? '' : 'none';
                    });
                });
            }

            // Select All
            const selectAll = document.getElementById('selectAll');
            const studentChecks = document.querySelectorAll('.check-student');
            const submitAddBtn = document.getElementById('submitAddBtn');

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    studentChecks.forEach(check => {
                        if (check.closest('.student-row').style.display !== 'none') {
                            check.checked = this.checked;
                        }
                    });
                    updateAddButton();
                });
            }

            studentChecks.forEach(check => {
                check.addEventListener('change', updateAddButton);
            });

            function updateAddButton() {
                const checkedCount = document.querySelectorAll('.check-student:checked').length;
                if (submitAddBtn) {
                    submitAddBtn.disabled = checkedCount === 0;
                    submitAddBtn.textContent = checkedCount > 0 ? `Add Selected (${checkedCount})` : 'Add Selected Students';
                }
            }
        });

        function openResultModal(studentId, name, status, rank, notes) {
            document.getElementById('modalStudentName').textContent = name;
            document.getElementById('modalStatus').value = status;
            document.getElementById('modalRank').value = rank || '';
            document.getElementById('modalNotes').value = notes || '';

            const form = document.getElementById('resultForm');
            form.action = "{{ route('teacher.events.participants.update', [$event->id, ':studentId']) }}".replace(':studentId', studentId);

            new bootstrap.Modal(document.getElementById('resultModal')).show();
        }
    </script>
@endsection