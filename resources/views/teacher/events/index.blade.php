@extends('layouts.app')

@section('title', 'My Coaching Assignments')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
            <div>
                <h3 class="fw-bold mb-0 text-gradient">Sports & Events Coaching</h3>
                <p class="text-muted small mb-0">Manage and track all competitions where you are assigned as a coach.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-white border rounded-pill shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Export Schedule
                </button>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-light border rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Events List -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="ps-4 border-0 py-3">SCHEDULE</th>
                            <th class="border-0 py-3">EVENT TITLE</th>
                            <th class="border-0 py-3">LOCATION</th>
                            <th class="border-0 py-3 text-center">STATUS</th>
                            <th class="border-0 py-3 pe-4 text-end">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr class="hover-lift transition-all">
                                <td class="ps-4 border-0">
                                    <div class="fw-bold text-dark">{{ $event->event_date->format('d M, Y') }}</div>
                                    <small class="text-muted tiny"><i class="bi bi-clock me-1"></i>
                                        {{ $event->event_date->format('h:i A') }}</small>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-3">
                                            <i class="bi bi-trophy-fill"></i>
                                        </div>
                                        <span class="fw-bold">{{ $event->title }}</span>
                                    </div>
                                </td>
                                <td class="border-0 small text-muted"><i class="bi bi-geo-alt me-1"></i>
                                    {{ $event->location ?? 'Not Specified' }}</td>
                                <td class="border-0 text-center">
                                    @php
                                        $statusClass = [
                                            'scheduled' => 'info',
                                            'upcoming' => 'info',
                                            'ongoing' => 'warning',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ][$event->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} rounded-pill px-3 py-2 small shadow-sm">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td class="border-0 pe-4 text-end">
                                    <a href="{{ route('teacher.events.show', $event) }}"
                                        class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" title="Manage Participants">
                                        <i class="bi bi-people me-1"></i> Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="opacity-25 mb-3"><i class="bi bi-calendar-x" style="font-size: 4rem;"></i></div>
                                    <h5 class="text-muted">No events assigned to your coaching schedule.</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($events->hasPages())
                <div class="card-footer bg-white border-0 p-4">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection