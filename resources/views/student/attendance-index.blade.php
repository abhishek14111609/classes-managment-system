@extends('layouts.app')

@section('title', 'Attendance History')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4 mb-5 pb-2">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden" style="border-radius: 1.5rem;">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Attendance rate</p>
                        <h2 class="fw-bold mb-0">{{ $summary['percentage'] }}%</h2>
                        <i class="bi bi-percent position-absolute end-0 bottom-0 opacity-10"
                            style="font-size: 5rem; margin-right: -10px; margin-bottom: -15px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white overflow-hidden" style="border-radius: 1.5rem;">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Present Days</p>
                        <h2 class="fw-bold mb-0">{{ $summary['present'] }}</h2>
                        <i class="bi bi-check-circle position-absolute end-0 bottom-0 opacity-10"
                            style="font-size: 5rem; margin-right: -10px; margin-bottom: -15px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white overflow-hidden" style="border-radius: 1.5rem;">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Absent Days</p>
                        <h2 class="fw-bold mb-0">{{ $summary['absent'] }}</h2>
                        <i class="bi bi-x-circle position-absolute end-0 bottom-0 opacity-10"
                            style="font-size: 5rem; margin-right: -10px; margin-bottom: -15px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-dark text-white overflow-hidden" style="border-radius: 1.5rem;">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Total Sessions</p>
                        <h2 class="fw-bold mb-0">{{ $summary['total_days'] }}</h2>
                        <i class="bi bi-collection position-absolute end-0 bottom-0 opacity-10"
                            style="font-size: 5rem; margin-right: -10px; margin-bottom: -15px;"></i>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 bg-success-subtle text-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger">{{ session('error') }}</div>
        @endif

        <!-- Live Photo Attendance Upload -->
        <div class="card border-0 shadow-sm mb-4 bg-white rounded-4 overflow-hidden">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold mb-3">Live Batch Attendance</h5>
                <p class="text-muted small mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    {{ $uploadMessage }}
                </p>

                @if($canUpload)
                    <div id="camera-container" class="mb-3 position-relative d-inline-block w-100" style="max-width: 400px;">
                        <!-- The Video Stream -->
                        <video id="live-camera" class="w-100 rounded-4 shadow-sm bg-dark d-none"
                            style="min-height: 250px; object-fit: cover;" autoplay playsinline></video>

                        <!-- Initial Start Button -->
                        <button type="button" id="start-camera-btn"
                            class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm fw-bold mb-2">
                            <i class="bi bi-camera-video-fill me-2 fs-4 align-middle"></i> Open Camera
                        </button>

                        <!-- Capture Button (hidden until camera starts) -->
                        <button type="button" id="capture-photo-btn"
                            class="btn btn-success btn-lg rounded-pill px-5 shadow-sm fw-bold position-absolute bottom-0 start-50 translate-middle-x mb-3 d-none z-3">
                            <i class="bi bi-record-circle me-2 fs-4 align-middle"></i> Snap Photo
                        </button>
                    </div>

                    <!-- Hidden Form to submit the captured data -->
                    <form id="attendance-form" action="{{ route('student.attendance.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="photo" id="photo-input" class="d-none" required>
                    </form>

                    <!-- Hidden Canvas for taking the photo from video -->
                    <canvas id="photo-canvas" class="d-none"></canvas>

                    <p id="camera-status" class="text-muted small mt-2 mb-0">Live photo is mandatory to mark attendance.</p>
                @else
                    <button class="btn btn-secondary btn-lg rounded-pill px-5 shadow-sm fw-bold disabled">
                        <i class="bi bi-camera-fill me-2 fs-4 align-middle"></i> Camera Locked
                    </button>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('student.attendance.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">START DATE</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">END DATE</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-funnel"></i> Filter Records
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-light border w-100 py-2" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th class="text-center">Status</th>
                            <th>Timing</th>
                            <th>Instructor Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $attendance->attendance_date->format('M d, Y') }}<br><small
                                        class="text-muted">{{ $attendance->attendance_date->format('l') }}</small></td>
                                <td class="text-center">
                                    @php
                                        $statusClass = [
                                            'present' => 'success',
                                            'absent' => 'danger',
                                            'late' => 'warning',
                                            'excused' => 'info'
                                        ][$attendance->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} rounded-pill px-3 py-2">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td><i class="bi bi-clock me-1 text-muted"></i> 09:00 AM</td>
                                <td class="text-muted small">{{ $attendance->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="opacity-25 mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
                                    <p class="text-muted mb-0">No attendance records found for this period.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($canUpload)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const video = document.getElementById('live-camera');
                const canvas = document.getElementById('photo-canvas');
                const startBtn = document.getElementById('start-camera-btn');
                const captureBtn = document.getElementById('capture-photo-btn');
                const form = document.getElementById('attendance-form');
                const fileInput = document.getElementById('photo-input');
                const statusText = document.getElementById('camera-status');

                let stream = null;

                // Start Camera
                startBtn.addEventListener('click', async function () {
                    try {
                        startBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Starting...';
                        startBtn.disabled = true;

                        // Request back camera
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'environment' },
                            audio: false
                        });

                        video.srcObject = stream;
                        video.classList.remove('d-none');

                        // Hide start button, show capture button
                        startBtn.classList.add('d-none');
                        captureBtn.classList.remove('d-none');
                        statusText.innerText = "Camera active. Please snap your photo.";
                        statusText.classList.add('text-success');

                    } catch (err) {
                        console.error("Camera access error:", err);
                        startBtn.innerHTML = '<i class="bi bi-camera-video-fill me-2"></i> Failed. Try Again';
                        startBtn.disabled = false;
                        startBtn.classList.replace('btn-primary', 'btn-danger');
                        statusText.innerText = "Error: Could not access camera. Please check browser permissions.";
                        statusText.classList.replace('text-muted', 'text-danger');
                    }
                });

                // Capture Frame & Submit
                captureBtn.addEventListener('click', function () {
                    captureBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
                    captureBtn.disabled = true;

                    // Set canvas size to match video feed
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // Draw current video frame onto canvas
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

                    statusText.innerText = "Photo snapped! Uploading to server securely...";

                    // Convert canvas to Blob (JPG file format)
                    canvas.toBlob(function (blob) {
                        // Create a faux file for the hidden input
                        const file = new File([blob], "live_attendance_" + Date.now() + ".jpg", { type: "image/jpeg" });

                        // Assign the file cleanly using DataTransfer
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;

                        // Stop the camera stream now that we have the photo
                        if (stream) {
                            stream.getTracks().forEach(track => track.stop());
                        }

                        // Ship it!
                        form.submit();
                    }, 'image/jpeg', 0.85); // 0.85 is reasonable quality standard
                });
            });
        </script>
    @endif
@endsection