@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Institutional Training Allocation' : 'Institutional Batch Scheduling')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">{{ auth()->user()->school->institute_type === 'sport' ? 'Training Sessions' : 'Batch Management' }}</h3>
                <p class="text-muted small mb-0">{{ auth()->user()->school->institute_type === 'sport' ? 'Optimize institutional scheduling and monitor athlete allocations.' : 'Optimize institutional scheduling and monitor student enrollment density.' }}
                </p>
            </div>
            <a href="{{ route('school.batches.create') }}"
                class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                <i class="bi bi-clock-history me-2"></i> {{ auth()->user()->school->institute_type === 'sport' ? 'Create Dynamic Session' : 'Create Dynamic Batch' }}
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-4 px-4">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <form action="{{ route('school.batches.index') }}" method="GET">
                            <div class="input-group input-group-sm bg-light rounded-pill px-3 py-1 border">
                                <span class="input-group-text bg-transparent border-0 text-muted small fw-bold">FILTER BY
                                    CLASS:</span>
                                <select name="class_id" class="form-select bg-transparent border-0 shadow-none tiny fw-bold"
                                    onchange="this.form.submit()">
                                    <option value="">All Institutional Levels</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">{{ auth()->user()->school->institute_type === 'sport' ? 'Sport & Session' : 'Batch Reference' }}</th>
                                <th class="py-3 border-0">{{ auth()->user()->school->institute_type === 'sport' ? 'Activity & Level' : 'Class Level' }}</th>
                                <th class="py-3 border-0">Operational Window</th>
                                <th class="py-3 border-0 text-center">{{ auth()->user()->school->institute_type === 'sport' ? 'Allocation Status' : 'Enrollment Status' }}</th>
                                <th class="py-3 border-0 text-center">{{ auth()->user()->school->institute_type === 'sport' ? 'Coaches' : 'Faculties' }}</th>
                                <th class="pe-4 py-3 border-0 text-end">Administration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 p-2 rounded-3 text-info me-3">
                                                <i class="bi bi-collection-fill"></i>
                                            </div>
                                            <div>
                                                @if(auth()->user()->school->institute_type === 'sport')
                                                    <div class="fw-bold text-dark">{{ $batch->class->course->name ?? 'N/A' }}</div>
                                                    <small class="text-muted tiny">{{ $batch->name }}</small>
                                                @else
                                                    <div class="fw-bold text-dark">{{ $batch->name }}</div>
                                                    <small class="text-muted tiny">Code: #BTC-{{ $batch->id }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        @if(auth()->user()->school->institute_type === 'sport')
                                            <div>
                                                <span class="badge bg-soft-success px-3 py-2 rounded-pill small">
                                                    {{ $batch->subject->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                            <small class="text-muted mt-1 d-block">{{ $batch->subject->level->name ?? 'Any Level' }}</small>
                                        @else
                                            <span class="badge bg-soft-info px-3 py-2 rounded-pill small">
                                                {{ $batch->class->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">
                                            <i class="bi bi-clock me-1 text-primary"></i>
                                            {{ date('h:i A', strtotime($batch->start_time)) }} –
                                            {{ date('h:i A', strtotime($batch->end_time)) }}
                                        </div>
                                    </td>
                                    <td class="border-0 text-center">
                                        @php
                                            $occupancy = $batch->capacity > 0 ? ($batch->students_count / $batch->capacity) * 100 : 0;
                                            $statusColor = $occupancy >= 90 ? 'danger' : ($occupancy >= 60 ? 'warning' : 'success');
                                        @endphp
                                        <div class="mb-1 d-flex justify-content-between px-1">
                                            <small class="tiny text-muted fw-bold">{{ $batch->students_count }} /
                                                {{ $batch->capacity }}</small>
                                            <small class="tiny text-muted fw-bold">{{ round($occupancy) }}%</small>
                                        </div>
                                        <div class="progress rounded-pill" style="height: 6px; width: 120px; margin: 0 auto;">
                                            <div class="progress-bar bg-{{ $statusColor }}" style="width: {{ $occupancy }}%">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0 text-center fw-bold">{{ $batch->teachers_count }}</td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('school.batches.show', $batch) }}"
                                                class="btn btn-sm btn-white border-0" title="Analytics">
                                                <i class="bi bi-bar-chart-fill text-info"></i>
                                            </a>
                                            <a href="{{ route('school.batches.edit', $batch) }}"
                                                class="btn btn-sm btn-white border-0" title="Modify Slot">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-white border-0"
                                                onclick="if(confirm('Archive this schedule?')) document.getElementById('delete-form-{{ $batch->id }}').submit();"
                                                title="Archive Batch">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $batch->id }}"
                                            action="{{ route('school.batches.destroy', $batch) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-calendar-x" style="font-size: 4rem;"></i>
                                        </div>
                                        <h5 class="text-muted">{{ auth()->user()->school->institute_type === 'sport' ? 'No operating sessions found for the selected criteria.' : 'No operating batches found for the selected criteria.' }}</h5>
                                        <a href="{{ route('school.batches.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">{{ auth()->user()->school->institute_type === 'sport' ? 'Create Initial Session' : 'Create Initial Batch' }}</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $batches->links() }}
        </div>
    </div>

    <style>
        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
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
    </style>
@endsection