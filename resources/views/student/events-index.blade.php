@extends('layouts.app')

@section('title', 'Achievement Vault')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Hero Section: Achievement Stats -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-primary bg-opacity-10 p-4 rounded-circle me-4 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: radial-gradient(circle, #4f46e5 0%, #3730a3 100%);">
                                <i class="bi bi-trophy-fill text-white display-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1">Achievement Vault</h3>
                                <p class="text-white-50 mb-0">Total participation in {{ $stats['total'] }} sanctioned events</p>
                            </div>
                        </div>
                        <div class="d-flex gap-4">
                            <div class="text-center">
                                <div class="medal-slot gold mb-1" style="width: 45px; height: 45px; background: #fbbf24; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(251, 191, 36, 0.4);">
                                    <i class="bi bi-award-fill text-white fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-0">{{ $stats['gold'] }}</h5>
                                <small class="tiny text-white-50 text-uppercase fw-bold">Gold</small>
                            </div>
                            <div class="text-center">
                                <div class="medal-slot silver mb-1" style="width: 45px; height: 45px; background: #94a3b8; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(148, 163, 184, 0.4);">
                                    <i class="bi bi-award-fill text-white fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-0">{{ $stats['silver'] }}</h5>
                                <small class="tiny text-white-50 text-uppercase fw-bold">Silver</small>
                            </div>
                            <div class="text-center">
                                <div class="medal-slot bronze mb-1" style="width: 45px; height: 45px; background: #b45309; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(180, 83, 9, 0.4);">
                                    <i class="bi bi-award-fill text-white fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-0">{{ $stats['bronze'] }}</h5>
                                <small class="tiny text-white-50 text-uppercase fw-bold">Bronze</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Challenges -->
        @if($upcomingEvents->count() > 0)
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-calendar-event text-primary fs-3 me-3"></i>
                        <h4 class="fw-bold mb-0">Upcoming Challenges</h4>
                    </div>
                    <div class="row g-4">
                        @foreach($upcomingEvents as $event)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100 transition-all hover-lift">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 tiny fw-bold">{{ strtoupper($event->status) }}</span>
                                            <div class="text-end">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $event->event_date->format('d M') }}</h6>
                                                <small class="text-muted tiny">{{ $event->event_date->format('Y') }}</small>
                                            </div>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-2">{{ $event->title }}</h5>
                                        <p class="text-muted small mb-4 line-clamp-2">{{ $event->description ?? 'No specific instructions provided. Check with your coach for details.' }}</p>
                                        
                                        <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-auto">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm rounded-circle bg-light border d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person-fill text-muted"></i>
                                                </div>
                                                <small class="fw-bold text-dark">Coach {{ explode(' ', $event->coach->user->name ?? 'N/A')[0] }}</small>
                                            </div>
                                            <span class="small text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $event->location ?? 'Arena' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Achievement History -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Performance History</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                All Participations
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 small">DATE</th>
                                        <th class="small">EVENT DETAILS</th>
                                        <th class="small text-center">RANKING</th>
                                        <th class="small text-center">STATUS</th>
                                        <th class="small pe-4 text-end">PERFORMANCE NOTES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($participations as $participation)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $participation->sportsEvent->event_date->format('d M, Y') }}</div>
                                                <div class="tiny text-muted fw-semibold">{{ $participation->sportsEvent->event_date->diffForHumans() }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $participation->sportsEvent->title }}</div>
                                                <div class="tiny text-muted"><i class="bi bi-person-badge me-1"></i> Coach: {{ $participation->sportsEvent->coach->user->name ?? 'N/A' }}</div>
                                            </td>
                                            <td class="text-center">
                                                @if($participation->rank == 1)
                                                    <div class="badge bg-warning text-dark border-0 rounded-pill px-3 py-2 shadow-sm">
                                                        <i class="bi bi-trophy-fill me-1"></i> 1st Position
                                                    </div>
                                                @elseif($participation->rank == 2)
                                                    <div class="badge bg-light text-secondary border rounded-pill px-3 py-2">
                                                        <i class="bi bi-award me-1"></i> 2nd Position
                                                    </div>
                                                @elseif($participation->rank == 3)
                                                    <div class="badge bg-light text-bronze border rounded-pill px-3 py-2" style="color: #b45309;">
                                                        <i class="bi bi-award me-1"></i> 3rd Position
                                                    </div>
                                                @else
                                                    <span class="text-muted small fw-bold">Participant</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $statusClass = [
                                                        'completed' => 'success',
                                                        'scheduled' => 'info',
                                                        'cancelled' => 'danger'
                                                    ][$participation->sportsEvent->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} rounded-pill px-3">{{ ucfirst($participation->sportsEvent->status) }}</span>
                                            </td>
                                            <td class="pe-4 text-end small">
                                                <span class="text-muted">{{ Str::limit($participation->notes ?? 'Participation recorded in center logs.', 50) }}</span>
                                                @if($participation->notes)
                                                    <a href="#" class="ms-1 tiny fw-bold text-primary text-decoration-none">Read more</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="mb-3 opacity-25">
                                                    <i class="bi bi-trophy display-1"></i>
                                                </div>
                                                <h6 class="fw-bold text-muted">No achievements found yet.</h6>
                                                <p class="text-muted small">Stay committed to your training and your first medal is just around the corner!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Targeted styles for the medal effects if needed */
        .medal-slot {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .medal-slot:hover {
            transform: scale(1.1) rotate(5deg);
        }
    </style>
@endpush