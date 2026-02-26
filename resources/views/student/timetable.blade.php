@extends('layouts.app')

@section('title', 'Training Schedule')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-primary bg-opacity-10 p-4 rounded-4 me-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-clock-history text-primary display-6"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1">Weekly Training Roadmap</h3>
                                <p class="text-muted mb-0">Assigned Batch: <span class="fw-bold text-dark">{{ $student->batch->name ?? 'Elite Training Division' }}</span></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                             <div class="bg-light p-3 rounded-4 px-4 border text-center">
                                <small class="tiny text-muted fw-bold d-block text-uppercase">Start</small>
                                <span class="fw-bold text-dark">{{ $student->batch->start_time ?? '09:00 AM' }}</span>
                             </div>
                             <div class="bg-light p-3 rounded-4 px-4 border text-center">
                                <small class="tiny text-muted fw-bold d-block text-uppercase">End</small>
                                <span class="fw-bold text-dark">{{ $student->batch->end_time ?? '11:00 AM' }}</span>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0">Active Schedule</h5>
                            <p class="text-muted small">Standard operational hours for your current batch level</p>
                        </div>
                        <div class="btn-group rounded-pill border p-1 bg-light">
                            <button class="btn btn-white btn-sm rounded-pill px-3 fw-bold active shadow-sm">Grid View</button>
                            <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-muted border-0">Feed View</button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="py-4 small border-0 bg-white" style="width: 120px;">DAY</th>
                                        <th class="py-4 small border-0">SESSION 01</th>
                                        <th class="py-4 small border-0">SESSION 02</th>
                                        <th class="py-4 small border-0 bg-light" style="width: 80px;">REST</th>
                                        <th class="py-4 small border-0">SESSION 03</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                        $sessions = [
                                            ['Strength & Conditioning', 'Tactical Drill', 'Recovery'],
                                            ['Endurance Training', 'Skill Workshop', 'Pool Session'],
                                            ['Mental Coaching', 'Gameplay Analysis', 'Field Practice'],
                                            ['Agility Drills', 'Combat Basics', 'Meditation'],
                                            ['Core Stability', 'Speed Testing', 'Physical Therapy'],
                                            ['Team Scrimmage', 'Review Session', 'Open Gym']
                                        ];
                                    @endphp
                                    @foreach($days as $index => $day)
                                        <tr>
                                            <td class="fw-bold text-dark bg-light border-0 py-4">{{ strtoupper($day) }}</td>
                                            <td class="border-0">
                                                <div class="card border-0 bg-primary bg-opacity-10 py-3 mx-2 rounded-4 shadow-sm transition-all hover-lift">
                                                    <div class="fw-bold text-primary small">{{ $sessions[$index][0] }}</div>
                                                    <div class="tiny text-primary text-opacity-75 mt-1 fw-bold">09:00 - 10:30</div>
                                                </div>
                                            </td>
                                            <td class="border-0">
                                                <div class="card border-0 bg-info bg-opacity-10 py-3 mx-2 rounded-4 shadow-sm transition-all hover-lift">
                                                    <div class="fw-bold text-info small">{{ $sessions[$index][1] }}</div>
                                                    <div class="tiny text-info text-opacity-75 mt-1 fw-bold">10:45 - 12:15</div>
                                                </div>
                                            </td>
                                            <td class="border-0 bg-light-subtle position-relative overflow-hidden">
                                                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: radial-gradient(#64748b 1px, transparent 1px); background-size: 8px 8px;"></div>
                                                <div class="rotate-90 tiny text-muted fw-bold opacity-50 position-relative z-1" style="letter-spacing: 4px;">P A U S E</div>
                                            </td>
                                            <td class="border-0">
                                                <div class="card border-0 bg-success bg-opacity-10 py-3 mx-2 rounded-4 shadow-sm transition-all hover-lift">
                                                    <div class="fw-bold text-success small">{{ $sessions[$index][2] }}</div>
                                                    <div class="tiny text-success text-opacity-75 mt-1 fw-bold">01:30 - 03:30</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 p-4 rounded-4 bg-primary bg-opacity-10 d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-primary mb-0 fs-3 me-3"></i>
                            <div class="small text-dark">
                                <strong>Coach's Note:</strong> Always arrive 15 minutes prior to the start time for mandatory warm-ups. Changes to the weekend scrimmage will be notified via the alert portal.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .rotate-90 {
            transform: rotate(-90deg);
            white-space: nowrap;
        }
        .table > :not(caption) > * > * {
            padding: 1.5rem 0.5rem;
        }
    </style>
@endpush