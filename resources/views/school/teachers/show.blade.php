@extends('layouts.app')

@section('title', 'Institutional Faculty Dossier')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Faculty Intelligence</h3>
                <p class="text-muted small mb-0">Comprehensive professional dossier for institutional faculty and staffing
                    coordination.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('school.teachers.edit', $teacher) }}"
                    class="btn btn-warning rounded-pill px-4 shadow-sm border-0 fw-bold small">
                    <i class="bi bi-pencil-square me-2"></i> Edit Dossier
                </a>
                <a href="{{ route('school.teachers.index') }}"
                    class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold small">
                    <i class="bi bi-arrow-left me-2"></i> Faculty Registry
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Faculty Persona Sidebar -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-4 mb-4 overflow-hidden position-relative">
                    <div class="card-body py-4 position-relative z-index-10">
                        <div class="mb-4 position-relative d-inline-block">
                            @if($teacher->user->avatar)
                                <img src="{{ asset('storage/' . $teacher->user->avatar) }}"
                                    class="rounded-circle border-white shadow-lg" width="140" height="140"
                                    style="object-fit: cover; border-width: 4px; border-style: solid;">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                    style="width: 140px; height: 140px; font-size: 3.5rem; font-weight: 800;">
                                    {{ substr($teacher->user->name, 0, 1) }}
                                </div>
                            @endif
                            <span
                                class="position-absolute bottom-0 end-0 bg-{{ $teacher->is_active ? 'success' : 'danger' }} border-white rounded-circle p-2"
                                title="Faculty Lifecycle" style="border-width: 3px; border-style: solid;"></span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">{{ $teacher->user->name }}</h4>
                        <p class="text-primary small fw-bold mb-3">
                            {{ strtoupper($teacher->specialization ?? 'ACADEMIC SPECIALIST') }}</p>
                        <span
                            class="badge bg-{{ $teacher->is_active ? 'soft-success' : 'soft-danger' }} rounded-pill px-4 py-2 small fw-bold mb-2">
                            {{ $teacher->is_active ? 'ACTIVE REVENUE CENTER' : 'RESTRICTED OPERATIVE' }}
                        </span>
                    </div>
                    <div class="bg-primary position-absolute top-0 start-0 w-100 h-25 opacity-10"></div>
                </div>

                <!-- Contact Vectors -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h6 class="fw-bold text-muted text-uppercase tiny mb-4" style="letter-spacing: 1px;">Communication
                        Vectors</h6>
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded-3 me-3 text-primary"><i class="bi bi-envelope-at-fill"></i>
                            </div>
                            <div>
                                <small class="text-muted tiny d-block fw-bold text-uppercase">Institutional Mail</small>
                                <span class="small fw-bold text-dark">{{ $teacher->user->email }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded-3 me-3 text-success"><i class="bi bi-phone-fill"></i></div>
                            <div>
                                <small class="text-muted tiny d-block fw-bold text-uppercase">Mobile Interface</small>
                                <span class="small fw-bold text-dark">{{ $teacher->user->phone ?? 'NOT REGISTERED' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Intelligence Dossier -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom-0">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-award-fill me-2 text-primary"></i> Professional
                            Standing</h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Human Resource
                                        ID</small>
                                    <div class="fw-bold text-dark small">{{ $teacher->employee_id ?? 'AUTH-PENDING' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Academic
                                        Credentials</small>
                                    <div class="fw-bold text-dark small">{{ $teacher->qualification ?? 'NOT DOCUMENTED' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Institutional
                                        Induction</small>
                                    <div class="fw-bold text-dark small">
                                        {{ $teacher->joining_date ? $teacher->joining_date->format('d M, Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Treasury
                                        Allocation</small>
                                    <div class="fw-bold text-dark small">₹{{ number_format($teacher->salary, 0) }} / Month
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light border border-white">
                                    <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Core
                                        Expertise</small>
                                    <div class="fw-bold text-dark small">{{ $teacher->specialization ?? 'GENERALIST' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operational Assignments -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-white py-3 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-stack me-2 text-primary"></i> Operational Load
                        </h6>
                        <span
                            class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 fw-bold small">{{ $teacher->batches->count() }}
                            ACTIVE BATCHES</span>
                    </div>
                    <div class="card-body p-0">
                        @if($teacher->batches->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr class="tiny text-muted text-uppercase fw-bold">
                                            <th class="ps-4 py-3 border-0">Batch Identity</th>
                                            <th class="py-3 border-0">Level / Class</th>
                                            <th class="py-3 border-0 text-center">Personnel Load</th>
                                            <th class="pe-4 py-3 border-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teacher->batches as $batch)
                                            <tr class="transition-all hover-lift">
                                                <td class="ps-4 border-0">
                                                    <div class="fw-bold text-dark small">{{ $batch->name }}</div>
                                                    <small class="text-muted tiny">AUTH: Institutional Logistics</small>
                                                </td>
                                                <td class="border-0">
                                                    <span
                                                        class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill small fw-bold">
                                                        {{ $batch->class->name }}
                                                    </span>
                                                </td>
                                                <td class="border-0 text-center">
                                                    <div class="fw-bold text-dark small">
                                                        {{ $batch->students_count ?? $batch->students->count() }}</div>
                                                    <small class="text-muted tiny">STUDENTS</small>
                                                </td>
                                                <td class="pe-4 border-0 text-end">
                                                    <a href="{{ route('school.batches.show', $batch) }}"
                                                        class="btn btn-sm btn-light rounded-pill px-3 border-0"
                                                        title="Inspect Batch">
                                                        <i class="bi bi-box-arrow-in-right"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="opacity-10 mb-2"><i class="bi bi-collection-fill" style="font-size: 3rem;"></i>
                                </div>
                                <h6 class="text-muted fw-bold small">Zero operational loads currently assigned to this faculty.
                                </h6>
                            </div>
                        @endif
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

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
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