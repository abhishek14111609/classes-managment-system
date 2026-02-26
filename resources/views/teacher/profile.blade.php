@extends('layouts.app')

@section('title', 'My Professional Profile')

@section('sidebar')
    @include('teacher.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Professional ID Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                    <div class="bg-primary py-5 px-4 text-center text-white">
                        <div class="mb-4 position-relative">
                            @if($teacher->user->avatar)
                                <img src="{{ asset('storage/' . $teacher->user->avatar) }}" alt="{{ $teacher->user->name }}"
                                    class="rounded-circle border-4 border-light shadow-lg mb-3"
                                    style="width: 150px; height: 150px; object-fit: cover; border-style: solid;">
                            @else
                                <div class="avatar bg-soft-primary rounded-circle border-primary border-2 d-flex align-items-center justify-content-center fw-bold text-primary shadow-lg mb-3"
                                    style="width: 150px; height: 150px; font-size: 4rem;">
                                    {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="bg-success position-absolute bottom-0 end-0 rounded-circle border-4 border-white"
                                style="width: 30px; height: 30px; transform: translate(-40px, -20px); border-style: solid;">
                            </div>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $teacher->user->name }}</h3>
                        <p class="text-white-50 small mb-4 text-uppercase fw-bold" style="letter-spacing: 2px;">
                            {{ $teacher->specialization ?? 'Professional Coach' }}
                        </p>

                        <div class="d-flex justify-content-center gap-3 mb-2">
                            <div class="text-center px-3 border-end border-white-50">
                                <h5 class="fw-bold mb-0">
                                    {{ $teacher->joining_date ? $teacher->joining_date->diffInYears(now()) : '0' }}+
                                </h5>
                                <small class="text-white-50">Years Exp.</small>
                            </div>
                            <div class="text-center px-3">
                                <h5 class="fw-bold mb-0">{{ $teacher->batches->count() }}</h5>
                                <small class="text-white-50">Active Batches</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-5">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted text-uppercase fw-bold mb-2">Email Address</label>
                                <div class="d-flex align-items-center bg-light p-3 rounded-4">
                                    <i class="bi bi-envelope text-primary me-3 fs-5"></i>
                                    <span class="fw-bold">{{ $teacher->user->email }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted text-uppercase fw-bold mb-2">Phone Number</label>
                                <div class="d-flex align-items-center bg-light p-3 rounded-4">
                                    <i class="bi bi-telephone text-primary me-3 fs-5"></i>
                                    <span class="fw-bold">{{ $teacher->user->phone ?? 'Not Linked' }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="small text-muted text-uppercase fw-bold mb-2">Educational
                                    Qualification</label>
                                <div class="d-flex align-items-center bg-light p-3 rounded-4">
                                    <i class="bi bi-mortarboard text-primary me-3 fs-5"></i>
                                    <span class="fw-bold">{{ $teacher->qualification ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Details & Experience -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Professional Overview</h5>
                        <div class="row g-4 mb-4">
                            <div class="col-md-4 text-center border-end">
                                <h3 class="fw-bold text-primary mb-1">
                                    {{ $teacher->joining_date ? $teacher->joining_date->diffInYears(now()) : '0' }}
                                </h3>
                                <p class="text-muted small mb-0 fw-bold uppercase" style="letter-spacing: 1px;">Exp. Years
                                </p>
                            </div>
                            <div class="col-md-4 text-center border-end">
                                <h3 class="fw-bold text-success mb-1">{{ $teacher->batches->count() }}</h3>
                                <p class="text-muted small mb-0 fw-bold uppercase" style="letter-spacing: 1px;">Active
                                    Batches</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <h3 class="fw-bold text-info mb-1">{{ $teacher->coachedEvents()->count() }}</h3>
                                <p class="text-muted small mb-0 fw-bold uppercase" style="letter-spacing: 1px;">Events
                                    Coached</p>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3 mt-5">Bio / Professional Summary</h6>
                        <p class="text-muted">
                            {{ $teacher->bio ?? 'Highly dedicated and professional educator with a passion for student development. Specialized in creating engaging learning environments and driving academic excellence across diverse groups.' }}
                        </p>

                        <h6 class="fw-bold mb-3 mt-5">Assigned Subjects / Expertise</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($teacher->batches->pluck('class.name')->unique() as $class)
                                <span class="badge bg-light text-primary border rounded-pill px-4 py-2">{{ $class }}</span>
                            @endforeach
                            <span class="badge bg-light text-primary border rounded-pill px-4 py-2">Advanced Coaching</span>
                            <span class="badge bg-light text-primary border rounded-pill px-4 py-2">Mentorship</span>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Schedule Summary -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Teaching Schedule Summary</h5>
                            <!-- Full Timetable Link Removed -->
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr class="small text-muted border-0">
                                        <th class="border-0 px-3">DAY</th>
                                        <th class="border-0">BATCH</th>
                                        <th class="border-0">TIME</th>
                                        <th class="border-0">ROOM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->batches as $batch)
                                        <tr>
                                            <td class="px-3 fw-bold small text-muted">MON / WED</td>
                                            <td><span class="fw-bold text-dark">{{ $batch->name }}</span><br><small
                                                    class="text-muted">{{ $batch->class->name }}</small></td>
                                            <td><i class="bi bi-clock me-1 text-primary small"></i>
                                                {{ $batch->time ?? '09:00 AM' }}</td>
                                            <td><span class="badge bg-light text-dark rounded-pill">Room 102</span></td>
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