@extends('layouts.app')

@section('title', 'Institutional Event Modification')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Modify Event Cycle</h3>
                <p class="text-muted small mb-0">Revise athletic parameters, temporal logic, or institutional coordination
                    for active events.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('school.events.show', $event) }}"
                    class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold small">
                    <i class="bi bi-eye me-2"></i> Inspect Dossier
                </a>
                <a href="{{ route('school.events.index') }}"
                    class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold small">
                    <i class="bi bi-arrow-left me-2"></i> Event Registry
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white p-4 border-0">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i> Revision Protocol:
                            {{ $event->title }}
                        </h6>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <form action="{{ route('school.events.update', $event) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4 mb-4">
                                <div class="col-md-7">
                                    <label for="title" class="form-label tiny fw-bold text-muted text-uppercase mb-2">Event
                                        Nomenclature <span class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                        <span class="input-group-text bg-transparent border-0"><i
                                                class="bi bi-flag text-primary"></i></span>
                                        <input type="text"
                                            class="form-control bg-transparent border-0 shadow-none fw-bold @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                    </div>
                                    @error('title')
                                        <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-5">
                                    <label for="coach_id"
                                        class="form-label tiny fw-bold text-muted text-uppercase mb-2">Lead Coordinator
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                        <span class="input-group-text bg-transparent border-0"><i
                                                class="bi bi-person-badge text-primary"></i></span>
                                        <select
                                            class="form-select bg-transparent border-0 shadow-none fw-bold @error('coach_id') is-invalid @enderror"
                                            id="coach_id" name="coach_id" required>
                                            <option value="">Select Faculty</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('coach_id', $event->coach_id) == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('coach_id')
                                        <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <label for="event_date"
                                        class="form-label tiny fw-bold text-muted text-uppercase mb-2">Cycle Date <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                        <span class="input-group-text bg-transparent border-0"><i
                                                class="bi bi-calendar-event text-primary"></i></span>
                                        <input type="date"
                                            class="form-control bg-transparent border-0 shadow-none fw-bold @error('event_date') is-invalid @enderror"
                                            id="event_date" name="event_date"
                                            value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                    </div>
                                    @error('event_date')
                                        <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="start_time"
                                        class="form-label tiny fw-bold text-muted text-uppercase mb-2">Operational Start
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                        <span class="input-group-text bg-transparent border-0"><i
                                                class="bi bi-clock text-primary"></i></span>
                                        <input type="time"
                                            class="form-control bg-transparent border-0 shadow-none fw-bold @error('start_time') is-invalid @enderror"
                                            id="start_time" name="start_time"
                                            value="{{ old('start_time', date('h:i', strtotime($event->start_time))) }}"
                                            required>
                                    </div>
                                    @error('start_time')
                                        <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="end_time"
                                        class="form-label tiny fw-bold text-muted text-uppercase mb-2">Operational End <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                        <span class="input-group-text bg-transparent border-0"><i
                                                class="bi bi-clock-history text-primary"></i></span>
                                        <input type="time"
                                            class="form-control bg-transparent border-0 shadow-none fw-bold @error('end_time') is-invalid @enderror"
                                            id="end_time" name="end_time"
                                            value="{{ old('end_time', date('h:i', strtotime($event->end_time))) }}"
                                            required>
                                    </div>
                                    @error('end_time')
                                        <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <label for="location"
                                        class="form-label tiny fw-bold text-muted text-uppercase mb-2">Institutional
                                        Location <span class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                        <span class="input-group-text bg-transparent border-0"><i
                                                class="bi bi-geo-alt text-danger"></i></span>
                                        <input type="text"
                                            class="form-control bg-transparent border-0 shadow-none fw-bold @error('location') is-invalid @enderror"
                                            id="location" name="location" value="{{ old('location', $event->location) }}"
                                            required>
                                    </div>
                                    @error('location')
                                        <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="sport_level"
                                        class="form-label tiny fw-bold text-muted text-uppercase mb-2">Sport Level</label>
                                    <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                        <span class="input-group-text bg-transparent border-0"><i
                                                class="bi bi-bar-chart-steps text-info"></i></span>
                                        <select
                                            class="form-select bg-transparent border-0 shadow-none fw-bold @error('sport_level') is-invalid @enderror"
                                            id="sport_level" name="sport_level">
                                            <option value="">Select Level (Optional)</option>
                                            @foreach($levels as $level)
                                                <option value="{{ $level->name }}" {{ old('sport_level', $event->sport_level) == $level->name ? 'selected' : '' }}>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('sport_level')
                                        <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="status"
                                        class="form-label tiny fw-bold text-muted text-uppercase mb-2">Lifecycle Status
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-pill px-3 py-1 border shadow-sm">
                                        <span class="input-group-text bg-transparent border-0"><i
                                                class="bi bi-activity text-primary"></i></span>
                                        <select
                                            class="form-select bg-transparent border-0 shadow-none fw-bold @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                            <option value="upcoming" {{ old('status', $event->status) === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                            <option value="ongoing" {{ old('status', $event->status) === 'ongoing' ? 'selected' : '' }}>Operational</option>
                                            <option value="completed" {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>Archived</option>
                                            <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Void</option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-5">
                                <label for="description"
                                    class="form-label tiny fw-bold text-muted text-uppercase mb-2">Strategic
                                    Description</label>
                                <textarea
                                    class="form-control rounded-4 shadow-none border small p-3 @error('description') is-invalid @enderror"
                                    id="description" name="description"
                                    rows="4">{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger tiny fw-bold mt-2 ms-3">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-3 pt-3 border-top">
                                <button type="submit"
                                    class="btn btn-warning rounded-pill px-5 py-2 fw-bold shadow-sm grow text-dark">
                                    <i class="bi bi-check2-circle me-2"></i> Commit Parameter Revisions
                                </button>
                                <a href="{{ route('school.events.index') }}"
                                    class="btn btn-light border rounded-pill px-4 fw-bold">Discard Changes</a>
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