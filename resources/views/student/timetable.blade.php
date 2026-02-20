@extends('layouts.app')

@section('title', 'My Timetable')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                                <i class="bi bi-clock-history fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Current Batch</h5>
                                <p class="text-muted small mb-0">{{ $student->batch->name ?? 'Not assigned' }}</p>
                            </div>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-light">
                                <span class="text-muted small fw-bold">START TIME</span>
                                <span class="fw-bold text-dark">{{ $student->batch->start_time ?? '09:00 AM' }}</span>
                            </li>
                            <li
                                class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-light">
                                <span class="text-muted small fw-bold">END TIME</span>
                                <span class="fw-bold text-dark">{{ $student->batch->end_time ?? '04:00 PM' }}</span>
                            </li>
                            <li
                                class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-0">
                                <span class="text-muted small fw-bold">CLASSROOM</span>
                                <span class="fw-bold text-dark">{{ $student->batch->room ?? 'Room 101' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4 bg-primary text-white overflow-hidden">
                    <div class="card-body p-4 position-relative z-1">
                        <h6 class="fw-bold mb-3">Notice Board</h6>
                        <p class="small opacity-75 mb-0">Class timings might change during examination weeks. Stay tuned for
                            official updates.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="fw-bold mb-0">Weekly Schedule</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3">Day</th>
                                        <th class="py-3">Session 1</th>
                                        <th class="py-3">Session 2</th>
                                        <th class="py-3">Break</th>
                                        <th class="py-3">Session 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                        $subjects = ['Mathematics', 'Physics', 'History', 'English', 'Science', 'Art', 'Sports'];
                                    @endphp
                                    @foreach($days as $day)
                                        <tr>
                                            <td class="fw-bold text-primary">{{ $day }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $subjects[array_rand($subjects)] }}</div>
                                                <small class="text-muted">09:00 - 10:30</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $subjects[array_rand($subjects)] }}</div>
                                                <small class="text-muted">10:30 - 12:00</small>
                                            </td>
                                            <td class="bg-light-subtle">
                                                <span class="badge bg-secondary opacity-50">LUNCH</span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $subjects[array_rand($subjects)] }}</div>
                                                <small class="text-muted">01:00 - 03:00</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection