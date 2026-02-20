@extends('layouts.app')

@section('title', 'My Events')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Sports Event Participations</h2>
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Event Date</th>
                                <th>Event Name</th>
                                <th>Category</th>
                                <th>Coach</th>
                                <th>Position</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $participation)
                                <tr>
                                    <td>
                                        {{ $participation->sportsEvent->event_date->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $participation->sportsEvent->event_time }}</small>
                                    </td>
                                    <td><strong>{{ $participation->sportsEvent->name }}</strong></td>
                                    <td>{{ $participation->sportsEvent->category }}</td>
                                    <td>{{ $participation->sportsEvent->coach->user->name }}</td>
                                    <td>{{ $participation->position ?? 'Participant' }}</td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'scheduled' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ][$participation->sportsEvent->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($participation->sportsEvent->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">You haven't participated in any events
                                        yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection