@extends('layouts.app')

@section('title', 'Create Level')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Create New Level</h3>
                <p class="text-muted small mb-0">Define an institutional competence tracking framework.</p>
            </div>
            <a href="{{ route('school.levels.index') }}"
                class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold small">
                <i class="bi bi-arrow-left me-2"></i> Levels Registry
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white p-4 border-0">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-steps me-2 text-primary"></i> Level Configuration
                            Dossier</h6>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <form action="{{ route('school.levels.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label tiny fw-bold text-muted text-uppercase mb-2">Level
                                    Nomenclature <span class="text-danger">*</span></label>
                                <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                    <span class="input-group-text bg-transparent border-0"><i
                                            class="bi bi-tag text-primary"></i></span>
                                    <input type="text"
                                        class="form-control bg-transparent border-0 shadow-none fw-bold @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required
                                        placeholder="e.g. Basic / Foundation">
                                </div>
                                @error('name')
                                    <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description"
                                    class="form-label tiny fw-bold text-muted text-uppercase mb-2">Strategic
                                    Description</label>
                                <textarea
                                    class="form-control rounded-4 shadow-none border small p-3 @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="4"
                                    placeholder="Brief explanation of what this level entails...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check form-switch mb-5 ms-1">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked
                                    value="1">
                                <label class="form-check-label fw-bold text-dark ms-2 mt-1" for="is_active">Institutional
                                    Operational Status (Active)</label>
                            </div>

                            <div class="d-flex gap-3 pt-3 border-top">
                                <button type="submit"
                                    class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm grow text-white">
                                    <i class="bi bi-plus-circle me-2"></i> Commit Level Definition
                                </button>
                                <a href="{{ route('school.levels.index') }}"
                                    class="btn btn-light border rounded-pill px-4 fw-bold">Discard Configuration</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .tiny {
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }

        .grow:hover {
            transform: scale(1.02);
            transition: all 0.2s;
        }

        .input-group:focus-within {
            border-color: #4facfe !important;
            box-shadow: 0 0 0 0.25rem rgba(79, 172, 254, 0.1) !important;
        }
    </style>
@endsection