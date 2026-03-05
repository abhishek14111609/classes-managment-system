@extends('layouts.app')

@php
    $isSport = auth()->user()->school && auth()->user()->school->institute_type === 'sport';
@endphp

@section('title', $isSport ? 'Institutional Athlete Portfolio' : 'Institutional Student Portfolio')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">
                    @if($isSport) Athlete Portfolio @else Student Portfolio @endif
                </h3>
                <p class="text-muted small mb-0">
                    @if($isSport)
                        Comprehensive performance dossier for training and event tracking.
                    @else
                        Comprehensive institutional dossier for academic and behavioral tracking.
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('school.students.edit', $student) }}"
                    class="btn btn-warning rounded-pill px-4 shadow-sm border-0 fw-bold small">
                    <i class="bi bi-pencil-square me-2"></i> Edit Record
                </a>
                <a href="{{ route('school.students.statement', $student) }}"
                    class="btn btn-outline-primary rounded-pill px-4 shadow-sm border fw-bold small">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Financial Statement
                </a>
                <a href="{{ route('school.students.index') }}"
                    class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold small">
                    <i class="bi bi-arrow-left me-2"></i> Registry
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Dossier -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-4 mb-4 overflow-hidden position-relative">
                    <div class="card-body py-4 position-relative z-index-10">
                        <div class="mb-4 position-relative d-inline-block">
                            @if($student->photo)
                                <img src="{{ Storage::url($student->photo) }}"
                                    class="rounded-circle border-white shadow-lg" width="140" height="140"
                                    style="object-fit: cover; border-width: 4px; border-style: solid;">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                    style="width: 140px; height: 140px; font-size: 3.5rem; font-weight: 800;">
                                    {{ substr($student->user->name, 0, 1) }}
                                </div>
                            @endif
                            <span
                                class="position-absolute bottom-0 end-0 bg-{{ $student->user->is_active ? 'success' : 'danger' }} border-white rounded-circle p-2"
                                title="Lifecycle Status" style="border-width: 3px; border-style: solid;"></span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">{{ $student->user->name }}</h4>
                        <p class="text-muted small fw-bold mb-3">ROLL ID: #{{ $student->roll_number }}</p>
                        <span
                            class="badge bg-{{ $student->user->is_active ? 'soft-success' : 'soft-danger' }} rounded-pill px-4 py-2 small fw-bold mb-2">
                            {{ $student->user->is_active ? 'ENABLED PROFILE' : 'RESTRICTED ACCESS' }}
                        </span>
                    </div>
                    <div class="bg-primary position-absolute top-0 start-0 w-100 h-25 opacity-10"></div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h6 class="fw-bold text-muted text-uppercase tiny mb-4" style="letter-spacing: 1px;">Engagement Metrics
                    </h6>
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 text-info p-2 rounded-3 me-3"><i
                                        class="bi bi-calendar-check"></i></div>
                                <span class="small fw-bold text-dark">Presence</span>
                            </div>
                            <h6 class="mb-0 fw-bold">{{ number_format($student->getAttendancePercentage(), 1) }}%</h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-3 me-3"><i
                                        class="bi bi-wallet2"></i></div>
                                <span class="small fw-bold text-dark">Outstanding</span>
                            </div>
                            <h6 class="mb-0 fw-bold text-danger">₹{{ number_format($student->getPendingFees(), 0) }}</h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3 me-3"><i
                                        class="bi bi-trophy"></i></div>
                                <span class="small fw-bold text-dark">Events</span>
                            </div>
                            <h6 class="mb-0 fw-bold">{{ $student->eventParticipations->count() }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Dossier -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom-0">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i> Primary
                            Identification</h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Electronic
                                        Mail</small>
                                    <div class="fw-bold text-dark small">{{ $student->user->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small
                                        class="text-muted tiny fw-bold text-uppercase d-block mb-1">Telecommunications</small>
                                    <div class="fw-bold text-dark small">{{ $student->user->phone ?? 'NOT REGISTERED' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">
                                        @if($isSport) Active Session Enrollments @else Batch Placement @endif
                                    </small>
                                    @if($isSport)
                                        @if($student->batches->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                @foreach($student->batches as $batch)
                                                    <span class="badge bg-primary rounded-pill px-3 py-2 small fw-bold">
                                                        <i class="bi bi-tag-fill me-1"></i> {{ $batch->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="fw-bold text-muted small italic">No active sessions assigned.</div>
                                        @endif
                                    @else
                                        <div class="fw-bold text-dark small">{{ $student->batch->name ?? 'N/A' }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Institutional
                                        Level</small>
                                    <div class="fw-bold text-dark small">{{ $student->batch->class->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Admission
                                        Cycle</small>
                                    <div class="fw-bold text-dark small">{{ $student->admission_date->format('d M, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($student->parent_name || $student->parent_phone)
                            <div class="mt-4 p-3 rounded-4 border bg-primary bg-opacity-5">
                                <h6 class="tiny fw-bold text-primary text-uppercase mb-3"><i class="bi bi-people me-1"></i>
                                    Guardian Control</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <small class="text-muted tiny d-block">Guardian Full Name</small>
                                        <div class="fw-bold text-dark small">{{ $student->parent_name ?? '—' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted tiny d-block">Emergency Contact</small>
                                        <div class="fw-bold text-dark small">{{ $student->parent_phone ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tabs / Chronology Area -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs border-0 bg-light px-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active border-0 py-3 px-4 tiny fw-bold text-uppercase"
                                    data-bs-toggle="tab" href="#attendanceTab">Attendance Chronology</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link border-0 py-3 px-4 tiny fw-bold text-uppercase" data-bs-toggle="tab"
                                    href="#feeTab">Financial Ledger</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Attendance Tab -->
                            <div id="attendanceTab" class="tab-pane active fade show p-4">
                                @if($student->attendances->take(10)->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead>
                                                <tr class="tiny text-muted fw-bold">
                                                    <th>Cycle Date</th>
                                                    <th class="text-center">Declaration</th>
                                                    <th>Observations</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->attendances->sortByDesc('attendance_date')->take(10) as $attendance)
                                                    <tr>
                                                        <td class="small fw-bold">
                                                            {{ $attendance->attendance_date->format('d M, Y') }}</td>
                                                        <td class="text-center">
                                                            @php
                                                                $statusStyle = [
                                                                    'present' => 'success',
                                                                    'absent' => 'danger',
                                                                    'late' => 'warning',
                                                                    'excused' => 'info'
                                                                ][$attendance->status] ?? 'secondary';
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $statusStyle }} rounded-pill px-3 py-1 tiny fw-bold">
                                                                {{ strtoupper($attendance->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-muted small italic">
                                                            {{ $attendance->notes ?? 'No annotations recorded.' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4 text-muted small"><i class="bi bi-clock-history me-2"></i> No
                                        chronological data recorded.</div>
                                @endif
                            </div>

                            <!-- Fee Tab -->
                            <div id="feeTab" class="tab-pane fade p-4">
                                @if($student->fees->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead>
                                                <tr class="tiny text-muted fw-bold">
                                                    <th>Asset Cycle</th>
                                                    <th>Financial Value</th>
                                                    <th>Institutional Dues</th>
                                                    <th class="text-center">Lifecycle</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->fees->sortByDesc('due_date') as $fee)
                                                    <tr>
                                                        <td class="small fw-bold">
                                                            <div class="small fw-bold text-dark">
                                                                {{ $fee->due_date->format('d M, Y') }}</div>
                                                            <div class="d-flex gap-2">
                                                                <small class="text-muted tiny text-uppercase">{{ $fee->fee_type }}</small>
                                                                @if($fee->batch)
                                                                    <small class="text-primary tiny fw-bold text-uppercase"><i class="bi bi-tag-fill"></i> {{ $fee->batch->name }}</small>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="small fw-bold">₹{{ number_format($fee->total_amount, 0) }}</td>
                                                        <td class="small fw-bold text-danger">
                                                            ₹{{ number_format($fee->getRemainingAmount(), 0) }}</td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'overdue' ? 'danger' : 'warning') }} rounded-pill px-3 py-1 tiny fw-bold">
                                                                {{ strtoupper($fee->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end text-nowrap">
                                                            <a href="{{ route('school.fees.show', $fee) }}"
                                                                class="btn btn-sm btn-light rounded-pill px-3 border-0"
                                                                title="Inspect Ledger">
                                                                <i class="bi bi-box-arrow-in-right"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4 text-muted small"><i class="bi bi-cash-coin me-2"></i> No
                                        financial ledgers identified.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            transition: all 0.2s;
        }

        .nav-tabs .nav-link.active {
            background-color: #fff !important;
            color: #0d6efd !important;
            border-top: 3px solid #0d6efd !important;
        }

        .nav-tabs .nav-link:hover {
            color: #0d6efd;
        }

        .tiny {
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }

        .z-index-10 {
            z-index: 10;
        }
    </style>
@endsection