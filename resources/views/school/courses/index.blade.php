@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Sports Programs Catalog' : 'Academic Programs Catalog')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">
                    {{ auth()->user()->school->institute_type === 'sport' ? 'Sports Programs' : 'Academic Programs' }}</h3>
                <p class="text-muted small mb-0">Define and manage the core
                    {{ auth()->user()->school->institute_type === 'sport' ? 'training tracks' : 'educational tracks' }} for
                    your institution.</p>
            </div>
            <a href="{{ route('school.courses.create') }}"
                class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                <i class="bi bi-plus-lg me-2"></i> Configure New Program
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse($courses as $course)
                <div class="col-md-4">
                    <div
                        class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all overflow-hidden position-relative">
                        <div class="position-absolute top-0 end-0 p-3">
                            <span
                                class="badge bg-{{ $course->is_active ? 'success' : 'secondary' }} rounded-pill px-3 py-2 small shadow-sm">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="card-body p-4 pt-5">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 d-inline-block text-primary mb-4">
                                <i class="bi bi-book-half fs-3"></i>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h5 class="fw-bold mb-0">{{ $course->name }}</h5>
                                @if($course->code)
                                    <span class="badge bg-light text-muted border tiny">{{ $course->code }}</span>
                                @endif
                            </div>

                            <p class="text-muted small mb-4"
                                style="height: 48px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                {{ $course->description ?? 'No high-level description provided for this ' . (auth()->user()->school->institute_type === 'sport' ? 'sports program' : 'academic program') . '.' }}
                            </p>

                            <div class="row g-2 mb-4 border-top pt-3">
                                <div class="col-6">
                                    <div class="p-2 text-center">
                                        <h6 class="fw-bold mb-0">{{ $course->classes_count }}</h6>
                                        <small
                                            class="text-muted tiny text-uppercase">{{ auth()->user()->school->institute_type === 'sport' ? 'Teams' : 'Classes' }}</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 text-center">
                                        <h6 class="fw-bold mb-0">Active</h6>
                                        <small class="text-muted tiny text-uppercase">Status</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('school.courses.show', $course) }}"
                                    class="btn btn-sm btn-light border rounded-pill px-3" title="View Architecture">
                                    <i class="bi bi-diagram-3 me-1"></i> Details
                                </a>
                                <a href="{{ route('school.courses.edit', $course) }}"
                                    class="btn btn-sm btn-light border rounded-pill px-3" title="Modify Configuration">
                                    <i class="bi bi-gear me-1"></i> Edit
                                </a>
                                <form action="{{ route('school.courses.destroy', $course) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Deleting this program will detach all associated classes. Proceed?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        title="Archive Program">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="opacity-25 mb-3"><i class="bi bi-journal-x" style="font-size: 5rem;"></i></div>
                    <h4 class="text-muted">No {{ auth()->user()->school->institute_type === 'sport' ? 'sports' : 'academic' }}
                        programs registered.</h4>
                    <p class="text-muted small mb-4">Start organizing your
                        {{ auth()->user()->school->institute_type === 'sport' ? 'training regimen' : 'curriculum' }} by creating
                        a new program track.
                    </p>
                    <a href="{{ route('school.courses.create') }}" class="btn btn-primary rounded-pill px-5 shadow-sm border-0">
                        Get Started Now
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $courses->links() }}
        </div>
    </div>

    <style>
        .text-gradient {
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endsection