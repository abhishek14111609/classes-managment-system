@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Institutional Athlete Registry' : 'Institutional Student Registry')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">
                    {{ auth()->user()->school->institute_type === 'sport' ? 'Athletes Registry' : 'Students Registry' }}
                </h3>
                <p class="text-muted small mb-0">Total of {{ number_format($students->total()) }}
                    {{ auth()->user()->school->institute_type === 'sport' ? 'athletes' : 'students' }} registered in the
                    institution.
                </p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('school.students.export') }}"
                    class="btn btn-light border rounded-pill px-4 shadow-sm hover-lift d-flex align-items-center">
                    <i class="bi bi-file-earmark-excel-fill text-success me-2"></i> Export Data
                </a>
                <a href="{{ route('school.students.create') }}"
                    class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                    <i class="bi bi-person-plus-fill me-2"></i> Add
                    {{ auth()->user()->school->institute_type === 'sport' ? 'Athlete' : 'Student' }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Advanced Filter Bar -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('school.students.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Catalog</label>
                        <div class="input-group bg-light rounded-pill px-3 py-1 border">
                            <span class="input-group-text bg-transparent border-0"><i
                                    class="bi bi-search text-muted small"></i></span>
                            <input type="text" name="search" class="form-control bg-transparent border-0 shadow-none tiny"
                                placeholder="Scan by name, email, or roll number..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Filter by Batch</label>
                        <div class="input-group bg-light rounded-pill px-3 py-1 border">
                            <span class="input-group-text bg-transparent border-0"><i
                                    class="bi bi-collection text-muted small"></i></span>
                            <select name="batch_id" class="form-select bg-transparent border-0 shadow-none tiny fw-bold">
                                <option value="">Across All Batches</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }} ({{ $batch->class->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Apply
                            Filters</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Student Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">Identity & Enrollment</th>
                                <th class="py-3 border-0">Primary Contact</th>
                                <th class="py-3 border-0">Batch Assignment</th>
                                <th class="py-3 border-0">Admission Date</th>
                                <th class="py-3 border-0 text-center">Lifecycle</th>
                                <th class="pe-4 py-3 border-0 text-end">Action Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 position-relative">
                                                @if($student->photo)
                                                    <img src="{{ asset('storage/' . $student->photo) }}"
                                                        class="rounded-circle border-2 border-white shadow-sm" width="50"
                                                        height="50">
                                                @else
                                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm"
                                                        style="width: 50px; height: 50px; font-size: 1.2rem;">
                                                        {{ substr($student->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <span
                                                    class="position-absolute bottom-0 end-0 bg-{{ $student->is_active ? 'success' : 'danger' }} border-white border-2 rounded-circle p-1"
                                                    style="width: 12px; height: 12px; border-style: solid;"></span>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $student->user->name }}</div>
                                                <small class="text-muted tiny fw-bold">ROLL:
                                                    {{ $student->roll_number ?? 'UNASSIGNED' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">{{ $student->user->email }}</div>
                                        <small class="text-muted tiny">SEC: Default
                                            {{ auth()->user()->school->institute_type === 'sport' ? 'Athlete' : 'Student' }}
                                            Access</small>
                                    </td>
                                    <td class="border-0">
                                        @if(auth()->user()->school->institute_type === 'sport')
                                            @if($student->batches->isNotEmpty())
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($student->batches as $batch)
                                                        <span class="badge bg-soft-success rounded-pill tiny" style="font-size: 0.65rem;">
                                                            {{ $batch->subject->name ?? 'Activity' }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                                <small class="text-muted tiny mt-1 d-block">Enrolled in {{ $student->batches->count() }} sessions</small>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1 rounded-pill tiny">NO SESSION</span>
                                            @endif
                                        @else
                                            @if($student->batch)
                                                <span class="badge bg-soft-info px-3 py-2 rounded-pill small">
                                                    {{ $student->batch->name }} ({{ $student->batch->class->name }})
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border px-3 py-2 rounded-pill tiny fw-bold">WAITING ASSIGNMENT</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">{{ $student->admission_date->format('d M, Y') }}
                                        </div>
                                        <small class="text-muted tiny">Entry recorded by system</small>
                                    </td>
                                    <td class="border-0 text-center">
                                        <span
                                            class="badge bg-{{ $student->is_active ? 'success' : 'danger' }} rounded-pill px-3 py-1 tiny">
                                            {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('school.payments.collect', $student) }}"
                                                class="btn btn-sm btn-white border-0" title="Collect Fees">
                                                <i class="bi bi-cash-coin text-success"></i>
                                            </a>
                                            <a href="{{ route('school.students.show', $student) }}"
                                                class="btn btn-sm btn-white border-0" title="Full Portfolio">
                                                <i class="bi bi-person-lines-fill text-info"></i>
                                            </a>
                                            <a href="{{ route('school.students.edit', $student) }}"
                                                class="btn btn-sm btn-white border-0" title="Modify Record">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-white border-0"
                                                onclick="if(confirm('Revoke {{ auth()->user()->school->institute_type === 'sport' ? 'athlete' : 'student' }} access and archive record?')) document.getElementById('delete-form-{{ $student->id }}').submit();"
                                                title="Revoke Access">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $student->id }}"
                                            action="{{ route('school.students.destroy', $student) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-people-fill" style="font-size: 5rem;"></i>
                                        </div>
                                        <h5 class="text-muted">No
                                            {{ auth()->user()->school->institute_type === 'sport' ? 'athlete' : 'student' }}
                                            matching records found.
                                        </h5>
                                        <a href="{{ route('school.students.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Add Initial Batch</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $students->links() }}
        </div>
    </div>

    <style>
        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-white {
            background-color: #fff;
        }

        .btn-white:hover {
            background-color: #f8f9fa;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }

        .tiny {
            font-size: 0.75rem;
        }
    </style>
@endsection