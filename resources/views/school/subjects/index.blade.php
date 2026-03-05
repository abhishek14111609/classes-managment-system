@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Training Activities & Exercises' : 'Institutional Syllabus & Modules')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">
                    {{ auth()->user()->school->institute_type === 'sport' ? 'Activities & Exercises' : 'Syllabus & Subjects' }}
                </h3>
                <p class="text-muted small mb-0">Total of {{ number_format($subjects->total()) }} modules currently active
                    in the
                    {{ auth()->user()->school->institute_type === 'sport' ? 'training framework.' : 'academic framework.' }}
                </p>
            </div>
            <a href="{{ route('school.subjects.create') }}"
                class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                <i class="bi bi-journal-plus me-2"></i>
                {{ auth()->user()->school->institute_type === 'sport' ? 'Add New Activity' : 'Construct New Module' }}
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
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">
                                    {{ auth()->user()->school->institute_type === 'sport' ? 'Sport & Activity' : 'Module & Nomenclature' }}
                                </th>
                                <th class="py-3 border-0">
                                    {{ auth()->user()->school->institute_type === 'sport' ? 'Sports Level' : 'Institutional Level' }}
                                </th>
                                <th class="py-3 border-0">Classification</th>
                                <th class="py-3 border-0 text-center">Description</th>
                                <th class="py-3 border-0 text-center">Lifecycle</th>
                                <th class="pe-4 py-3 border-0 text-end">Action Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 p-2 rounded-3 text-warning me-3">
                                                <i class="bi bi-journal-text fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $subject->name }}</div>
                                                <small class="text-muted tiny">
                                                    @if(auth()->user()->school->institute_type === 'sport' && $subject->schoolClass && $subject->schoolClass->course)
                                                        {{ $subject->schoolClass->course->name }}
                                                    @else
                                                        MOD-{{ str_pad($subject->id, 4, '0', STR_PAD_LEFT) }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-muted">
                                            @if(auth()->user()->school->institute_type === 'sport')
                                                {{ $subject->level->name ?? 'N/A' }}
                                            @else
                                                {{ $subject->schoolClass->name }}
                                            @endif
                                        </div>
                                        <small class="text-muted tiny">Level Category</small>
                                    </td>
                                    <td class="border-0">
                                        <span
                                            class="badge bg-{{ $subject->type === 'academic' ? 'soft-info' : 'soft-success' }} px-3 py-2 rounded-pill small">
                                            {{ ucfirst($subject->type) }}
                                        </span>
                                    </td>
                                    <td class="border-0 text-center text-muted small" style="max-width: 250px;">
                                        <div class="text-truncate" title="{{ $subject->description }}">
                                            {{ $subject->description ?? 'No granular description available for this module.' }}
                                        </div>
                                    </td>
                                    <td class="border-0 text-center">
                                        <span
                                            class="badge bg-{{ $subject->is_active ? 'success' : 'danger' }} rounded-pill px-3 py-1 tiny">
                                            {{ $subject->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('school.subjects.edit', $subject) }}"
                                                class="btn btn-sm btn-white border-0" title="Revise Configuration">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-white border-0"
                                                onclick="if(confirm('Archive this academic module? Associated data remains accessible.')) document.getElementById('delete-form-{{ $subject->id }}').submit();"
                                                title="{{ auth()->user()->school->institute_type === 'sport' ? 'Archive Activity' : 'Decommission Subject' }}">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $subject->id }}"
                                            action="{{ route('school.subjects.destroy', $subject) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-journal-medical"
                                                style="font-size: 5rem;"></i></div>
                                        <h5 class="text-muted">No
                                            {{ auth()->user()->school->institute_type === 'sport' ? 'training activities' : 'academic modules' }}
                                            configured yet.</h5>
                                        <a href="{{ route('school.subjects.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">{{ auth()->user()->school->institute_type === 'sport' ? 'Add First Activity' : 'Create First Subject' }}</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $subjects->links() }}
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
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
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