@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Institutional Athletic Calendar' : 'Institutional Event Calendar')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">{{ auth()->user()->school->institute_type === 'sport' ? 'Events & Calendar' : 'Events & Calendar' }}</h3>
                <p class="text-muted small mb-0">{{ auth()->user()->school->institute_type === 'sport' ? 'Manage sports meets, tournaments, and athletic coordination.' : 'Manage institutional events, assemblies, and coordination.' }}</p>
            </div>
            <a href="{{ route('school.events.create') }}"
                class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center fw-bold">
                <i class="bi bi-calendar-plus me-2"></i> Schedule New Event
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div class="small fw-bold">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Searching & Filter Bar -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4 bg-light bg-opacity-50 border-bottom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold text-muted text-uppercase mb-2">Event Lifecycle</label>
                        <form action="{{ route('school.events.index') }}" method="GET">
                            <select name="status" class="form-select rounded-pill px-3 shadow-none border small fw-bold"
                                onchange="this.form.submit()">
                                <option value="">{{ auth()->user()->school->institute_type === 'sport' ? 'All Athletic Events' : 'All Institutional Events' }}</option>
                                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming
                                    Schedule</option>
                                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Currently
                                    Ongoing</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Past
                                    Events</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Archived /
                                    Cancelled</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-8 d-flex align-items-end justify-content-end">
                        <div class="d-flex gap-4">
                            <div class="text-center">
                                <span
                                    class="h5 mb-0 fw-bold text-primary">{{ $events->where('status', 'upcoming')->count() }}</span>
                                <small class="d-block text-muted tiny fw-bold text-uppercase">Upcoming</small>
                            </div>
                            <div class="text-center">
                                <span
                                    class="h5 mb-0 fw-bold text-success">{{ $events->where('status', 'ongoing')->count() }}</span>
                                <small class="d-block text-muted tiny fw-bold text-uppercase">Operational</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="tiny text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">Event Identity</th>
                                <th class="py-3 border-0">Temporal Logic</th>
                                <th class="py-3 border-0">Institutional Location</th>
                                <th class="py-3 border-0 text-center">{{ auth()->user()->school->institute_type === 'sport' ? 'Coach In-charge' : 'Faculty In-charge' }}</th>
                                <th class="py-3 border-0 text-center">Lifecycle</th>
                                <th class="pe-4 py-3 border-0 text-end">Action Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-3">
                                                <i class="bi bi-flag-fill fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $event->title }}</div>
                                                <small
                                                    class="text-muted tiny fw-bold">EV-{{ str_pad($event->id, 5, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div class="small fw-bold text-dark">{{ $event->event_date->format('d M, Y') }}</div>
                                        <small class="text-muted tiny">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ date('h:i A', strtotime($event->start_time)) }} -
                                            {{ date('h:i A', strtotime($event->end_time)) }}
                                        </small>
                                    </td>
                                    <td class="border-0">
                                        <div class="small text-muted fw-bold"><i
                                                class="bi bi-geo-alt-fill me-1 text-danger"></i> {{ $event->location }}</div>
                                    </td>
                                    <td class="border-0 text-center">
                                        <div class="small fw-bold text-dark">{{ $event->coach->user->name }}</div>
                                        <small class="text-muted tiny">{{ auth()->user()->school->institute_type === 'sport' ? 'Lead Coach' : 'Lead Coordinator' }}</small>
                                    </td>
                                    <td class="border-0 text-center">
                                        @php
                                            $statusConfig = [
                                                'upcoming' => ['label' => 'UPCOMING', 'color' => 'soft-primary'],
                                                'ongoing' => ['label' => 'OPERATIONAL', 'color' => 'soft-success'],
                                                'completed' => ['label' => 'ARCHIVED', 'color' => 'soft-secondary'],
                                                'cancelled' => ['label' => 'VOID', 'color' => 'soft-danger'],
                                            ][$event->status] ?? ['label' => 'UNKNOWN', 'color' => 'soft-dark'];
                                        @endphp
                                        <span class="badge bg-{{ $statusConfig['color'] }} rounded-pill px-3 py-1 tiny fw-bold">
                                            {{ $statusConfig['label'] }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden border bg-white">
                                            <a href="{{ route('school.events.show', $event) }}"
                                                class="btn btn-sm btn-white border-0 px-3" title="Event Log">
                                                <i class="bi bi-eye text-info"></i>
                                            </a>
                                            <a href="{{ route('school.events.edit', $event) }}"
                                                class="btn btn-sm btn-white border-0 px-3" title="Revise Schedule">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-white border-0 px-3"
                                                onclick="if(confirm('Cancel this institutional event?')) document.getElementById('delete-form-{{ $event->id }}').submit();"
                                                title="Cancel Event">
                                                <i class="bi bi-calendar-x text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $event->id }}"
                                            action="{{ route('school.events.destroy', $event) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-calendar2-x" style="font-size: 5rem;"></i>
                                        </div>
                                        <h5 class="text-muted fw-bold">No institutional events scheduled in the current window.
                                        </h5>
                                        <a href="{{ route('school.events.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Create First Event</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $events->links() }}
        </div>
    </div>

    <style>
        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-soft-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .bg-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .text-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }
    </style>
@endsection