@extends('layouts.app')

@section('title', $event->title . ' — Institutional Event Analysis')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Event Intelligence Dossier</h3>
                <p class="text-muted small mb-0">Comprehensive analysis of institutional maneuvers, personnel engagement,
                    and status monitoring.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('school.events.edit', $event) }}"
                    class="btn btn-warning rounded-pill px-4 shadow-sm border-0 fw-bold small">
                    <i class="bi bi-pencil-square me-2"></i> Revise Schedule
                </a>
                <a href="{{ route('school.events.index') }}"
                    class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold small">
                    <i class="bi bi-arrow-left me-2"></i> Event Desk
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Event Primary Intel -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative">
                    <div class="card-body p-4 position-relative z-index-10">
                        <h6 class="tiny fw-bold text-muted text-uppercase mb-4" style="letter-spacing: 1px;">Strategic
                            Identity</h6>
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center me-3"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-flag-fill fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $event->title }}</h5>
                                <p class="text-muted tiny fw-bold mb-0">UID:
                                    EV-{{ str_pad($event->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <div class="p-3 rounded-4 bg-light border border-white">
                                <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Lead Coordinator</small>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center text-white tiny fw-bold"
                                        style="width: 24px; height: 24px;">{{ substr($event->coach->user->name, 0, 1) }}
                                    </div>
                                    <span class="small fw-bold text-dark">{{ $event->coach->user->name }}</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-4 bg-light border border-white">
                                <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Temporal Logic</small>
                                <div class="fw-bold text-dark small"><i class="bi bi-calendar3 me-2 text-primary"></i>
                                    {{ $event->event_date->format('d M, Y') }}</div>
                                <div class="text-muted tiny mt-1"><i class="bi bi-clock me-1"></i>
                                    {{ date('h:i A', strtotime($event->start_time)) }} -
                                    {{ date('h:i A', strtotime($event->end_time)) }}</div>
                            </div>
                            <div class="p-3 rounded-4 bg-light border border-white">
                                <small class="text-muted tiny fw-bold text-uppercase d-block mb-1">Operational Zone</small>
                                <div class="fw-bold text-danger small"><i class="bi bi-geo-alt-fill me-2"></i>
                                    {{ $event->location }}</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            @php
                                $statusStyle = [
                                    'upcoming' => 'primary',
                                    'ongoing' => 'success',
                                    'completed' => 'secondary',
                                    'cancelled' => 'danger',
                                ][$event->status] ?? 'dark';
                            @endphp
                            <span
                                class="badge bg-{{ $statusStyle }} rounded-pill px-4 py-2 w-100 fw-bold tiny">{{ strtoupper($event->status) }}
                                CYCLE</span>
                        </div>
                    </div>
                </div>

                @if($event->description)
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h6 class="tiny fw-bold text-muted text-uppercase mb-3" style="letter-spacing: 1px;">Strategic Overview
                        </h6>
                        <p class="text-muted small mb-0 lh-base italic">"{{ $event->description }}"</p>
                    </div>
                @endif
            </div>

            <!-- Personnel & Engagement Area -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div
                        class="card-header bg-white py-3 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill me-2 text-primary"></i> Personnel
                            Engagement Registry</h6>
                        <span
                            class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 fw-bold small">{{ $event->participants->count() }}
                            ACTIVE PARTICIPANTS</span>
                    </div>
                    <div class="card-body p-0">
                        @if($event->participants->isEmpty())
                            <div class="text-center py-5">
                                <div class="opacity-10 mb-3"><i class="bi bi-people" style="font-size: 4rem;"></i></div>
                                <h6 class="text-muted fw-bold">Zero personnel identified in this event cycle.</h6>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr class="tiny text-muted text-uppercase fw-bold">
                                            <th class="ps-4">Personnel Identity</th>
                                            <th>Credential</th>
                                            <th>Engagement Status</th>
                                            <th class="pe-4 text-end">Administration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event->participants as $participant)
                                                <tr class="transition-all hover-lift">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar bg-soft-secondary rounded-circle me-3 d-flex align-items-center justify-content-center small fw-bold"
                                                                style="width: 32px; height: 32px;">
                                                                {{ substr($participant->student->user->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark small">
                                                                    {{ $participant->student->user->name }}</div>
                                                                <small class="text-muted tiny">STUDENT UNIT</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span
                                                            class="small fw-bold text-muted">#{{ $participant->student->roll_number }}</span>
                                                    </td>
                                                    <td>
                                            @php
                                                $pStatusConfig = [
                                                    'won' => ['color' => 'warning', 'label' => 'VICTORY'],
                                                    'participated' => ['color' => 'success', 'label' => 'COMMITTED'],
                                                    'registered' => ['color' => 'info', 'label' => 'QUEUED'],
                                                ][$participant->participation_status] ?? ['color' => 'secondary', 'label' => strtoupper($participant->participation_status)];
                                            @endphp
                                                        <span
                                                            class="badge bg-{{ $pStatusConfig['color'] }} rounded-pill px-3 py-1 tiny fw-bold">
                                                            {{ $pStatusConfig['label'] }}
                                                        </span>
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <a href="{{ route('school.students.show', $participant->student) }}"
                                                            class="btn btn-sm btn-light rounded-pill px-3 border-0"
                                                            title="Inspect Personal Dossier">
                                                            <i class="bi bi-person-lines-fill"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Insights Row -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-gradient-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="tiny fw-bold text-uppercase opacity-75 mb-2">Victory Yield</h6>
                                    <h3 class="fw-bold mb-0">
                                        {{ $event->participants->where('participation_status', 'won')->count() }}</h3>
                                </div>
                                <i class="bi bi-trophy fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-gradient-info text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="tiny fw-bold text-uppercase opacity-75 mb-2">Committed Units</h6>
                                    <h3 class="fw-bold mb-0 text-white">{{ $event->participants->count() }}</h3>
                                </div>
                                <i class="bi bi-graph-up-arrow fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .text-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }

        .tiny {
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }

        .z-index-10 {
            z-index: 10;
        }

        .avatar {
            display: inline-flex;
            overflow: hidden;
        }
    </style>
@endsection