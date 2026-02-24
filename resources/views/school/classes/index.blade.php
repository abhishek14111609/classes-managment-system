@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Institutional Levels & Teams' : 'Institutional Levels & Classes')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">
                    {{ auth()->user()->school->institute_type === 'sport' ? 'Team Management' : 'Class Management' }}</h3>
                <p class="text-muted small mb-0">Define organizational levels and track student distribution across
                    {{ auth()->user()->school->institute_type === 'sport' ? 'teams.' : 'classes.' }}
                </p>
            </div>
            <a href="{{ route('school.classes.create') }}"
                class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                <i class="bi bi-plus-lg me-2"></i> Register New
                {{ auth()->user()->school->institute_type === 'sport' ? 'Team' : 'Class' }}
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
                                    {{ auth()->user()->school->institute_type === 'sport' ? 'Team Name' : 'Class Name' }}
                                </th>
                                <th class="py-3 border-0">Category</th>
                                <th class="py-3 border-0 text-center">Active Batches</th>
                                <th class="py-3 border-0 text-center">Student Capacity</th>
                                <th class="py-3 border-0 text-center">Status</th>
                                <th class="pe-4 py-3 border-0 text-end">Action Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-3">
                                                <i class="bi bi-journal-bookmark-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $class->name }}</div>
                                                <small class="text-muted tiny">ID: #CLS-{{ $class->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <span
                                            class="badge bg-{{ $class->type === 'academic' ? 'soft-info' : 'soft-success' }} px-3 py-2 rounded-pill small">
                                            {{ ucfirst($class->type) }}
                                        </span>
                                    </td>
                                    <td class="border-0 text-center fw-bold">{{ $class->batches_count }}</td>
                                    <td class="border-0 text-center">
                                        @php
                                            $totalStudents = $class->batches->sum(function ($batch) {
                                                return $batch->students_count ?? 0;
                                            });
                                        @endphp
                                        <div class="fw-bold text-dark">{{ $totalStudents }}</div>
                                        <small class="text-muted tiny">Across all batches</small>
                                    </td>
                                    <td class="border-0 text-center">
                                        <span
                                            class="badge bg-{{ $class->is_active ? 'success' : 'secondary' }} rounded-pill px-3 py-1 tiny">
                                            {{ $class->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('school.classes.show', $class) }}"
                                                class="btn btn-sm btn-white border-0" title="View In-Depth">
                                                <i class="bi bi-eye text-info"></i>
                                            </a>
                                            <a href="{{ route('school.classes.edit', $class) }}"
                                                class="btn btn-sm btn-white border-0" title="Edit Properties">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-white border-0"
                                                onclick="if(confirm('Archive this class? associated data remains linked.')) document.getElementById('delete-form-{{ $class->id }}').submit();"
                                                title="Delete {{ auth()->user()->school->institute_type === 'sport' ? 'Team' : 'Class' }}">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $class->id }}"
                                            action="{{ route('school.classes.destroy', $class) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-collection-x" style="font-size: 4rem;"></i>
                                        </div>
                                        <h5 class="text-muted">No institutional
                                            {{ auth()->user()->school->institute_type === 'sport' ? 'teams' : 'classes' }}
                                            registered yet.</h5>
                                        <a href="{{ route('school.classes.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Initialize First
                                            {{ auth()->user()->school->institute_type === 'sport' ? 'Team' : 'Class' }}</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $classes->links() }}
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
    </style>
@endsection