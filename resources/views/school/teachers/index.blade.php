@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Institutional Coach Registry' : 'Institutional Faculty Registry')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">
                    {{ auth()->user()->school->institute_type === 'sport' ? 'Coaches & Staff' : 'Faculty & Staff' }}</h3>
                <p class="text-muted small mb-0">Total of {{ number_format($teachers->total()) }}
                    {{ auth()->user()->school->institute_type === 'sport' ? 'expert coaches' : 'educators' }} powering your
                    institution.</p>
            </div>
            <a href="{{ route('school.teachers.create') }}"
                class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                <i class="bi bi-person-plus-fill me-2"></i>
                {{ auth()->user()->school->institute_type === 'sport' ? 'Recruit New Coach' : 'Recruit New Faculty' }}
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Searching & Metrics -->
        <div class="row g-4 mb-4">
            <div class="col-md-9">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <form action="{{ route('school.teachers.index') }}" method="GET">
                            <div class="input-group bg-light rounded-pill px-3 py-1 border">
                                <span class="input-group-text bg-transparent border-0"><i
                                        class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search"
                                    class="form-control bg-transparent border-0 shadow-none small fw-bold"
                                    placeholder="{{ auth()->user()->school->institute_type === 'sport' ? 'Identify coach by name, employee ID, or email...' : 'Identify faculty by name, employee ID, or email...' }}"
                                    value="{{ request('search') }}" onchange="this.form.submit()">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary bg-opacity-10">
                    <div class="card-body p-3 d-flex align-items-center justify-content-center">
                        <span class="fw-bold text-primary small"><i class="bi bi-check-circle-fill me-2"></i>
                            {{ $teachers->where('user.is_active', true)->count() }} Active Now</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">
                                    {{ auth()->user()->school->institute_type === 'sport' ? 'Coach Portfolio' : 'Educator Portfolio' }}
                                </th>
                                <th class="py-3 border-0">Institutional ID</th>
                                <th class="py-3 border-0">Digital Contact</th>
                                <th class="py-3 border-0 text-center">Batch load</th>
                                <th class="py-3 border-0 text-center">Lifecycle</th>
                                <th class="pe-4 py-3 border-0 text-end">Administration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($teacher->user->avatar)
                                                    <img src="{{ Storage::url($teacher->user->avatar) }}"
                                                        class="rounded-circle border-2 border-white shadow-sm" width="48"
                                                        height="48">
                                                @else
                                                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center fw-bold shadow-sm"
                                                        style="width: 48px; height: 48px;">
                                                        {{ substr($teacher->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $teacher->user->name }}</div>
                                                <small
                                                    class="text-muted tiny">{{ auth()->user()->school->institute_type === 'sport' ? 'Sr. Coaching Staff' : 'Sr. Faculty Member' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1 tiny fw-bold">
                                            #{{ $teacher->employee_id }}
                                        </span>
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">{{ $teacher->user->email }}</div>
                                        <small
                                            class="text-muted tiny">{{ $teacher->user->phone ?? 'NO MOBILE REGISTERED' }}</small>
                                    </td>
                                    <td class="border-0 text-center">
                                        <div class="fw-bold text-primary">{{ $teacher->batches_count }}</div>
                                        <small class="text-muted tiny">Batches</small>
                                    </td>
                                    <td class="border-0 text-center">
                                        <span
                                            class="badge bg-{{ $teacher->user->is_active ? 'success' : 'danger' }} rounded-pill px-3 py-1 tiny">
                                            {{ $teacher->user->is_active ? 'ENABLED' : 'DISABLED' }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('school.teachers.show', $teacher) }}"
                                                class="btn btn-sm btn-white border-0" title="Performance Profile">
                                                <i class="bi bi-award-fill text-info"></i>
                                            </a>
                                            <a href="{{ route('school.teachers.edit', $teacher) }}"
                                                class="btn btn-sm btn-white border-0" title="Edit Credentials">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-white border-0"
                                                onclick="if(confirm('Restrict {{ auth()->user()->school->institute_type === 'sport' ? 'coach' : 'faculty' }} access and archive records?')) document.getElementById('delete-form-{{ $teacher->id }}').submit();"
                                                title="{{ auth()->user()->school->institute_type === 'sport' ? 'Deactivate Coach' : 'Deactivate Faculty' }}">
                                                <i class="bi bi-slash-circle text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $teacher->id }}"
                                            action="{{ route('school.teachers.destroy', $teacher) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-person-video3"
                                                style="font-size: 5rem;"></i></div>
                                        <h5 class="text-muted">No
                                            {{ auth()->user()->school->institute_type === 'sport' ? 'coach' : 'faculty' }}
                                            matching records identified.</h5>
                                        <a href="{{ route('school.teachers.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Begin Recruitment</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $teachers->links() }}
        </div>
    </div>

    <style>
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