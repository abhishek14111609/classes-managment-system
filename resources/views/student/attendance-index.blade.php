@extends('layouts.app')

@section('title', 'Attendance Tracking')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- High-Impact Stats -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm grow-on-hover"
                    style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white text-opacity-75 small fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">
                            Success Rate</p>
                        <h2 class="fw-bold text-white mb-0">{{ $summary['percentage'] }}%</h2>
                        <div class="progress mt-3 bg-white bg-opacity-20" style="height: 6px;">
                            <div class="progress-bar bg-white" style="width: {{ $summary['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm grow-on-hover"
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white text-opacity-75 small fw-bold text-uppercase mb-1"
                            style="letter-spacing: 1px;">Present Days</p>
                        <h2 class="fw-bold text-white mb-0">{{ $summary['present'] }}</h2>
                        <div class="mt-3 small text-white text-opacity-75">Committed & Consistent</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm grow-on-hover"
                    style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white text-opacity-75 small fw-bold text-uppercase mb-1"
                            style="letter-spacing: 1px;">Absences</p>
                        <h2 class="fw-bold text-white mb-0">{{ $summary['absent'] }}</h2>
                        <div class="mt-3 small text-white text-opacity-75">Days missed from training</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm grow-on-hover"
                    style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <p class="text-white text-opacity-75 small fw-bold text-uppercase mb-1"
                            style="letter-spacing: 1px;">Total Units</p>
                        <h2 class="fw-bold text-white mb-0">{{ $summary['total_days'] }}</h2>
                        <div class="mt-3 small text-white text-opacity-75">Scheduled sessions recorded</div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div
                class="alert alert-success border-0 shadow-sm rounded-4 fade-in bg-success bg-opacity-10 text-success mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
            </div>
        @endif

        <!-- Live Photo Attendance Upload Section -->
        <div class="card border-0 shadow-sm mb-5 bg-white rounded-4 overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="row g-0">
                <div class="col-lg-6 bg-light d-flex align-items-center justify-content-center p-4"
                    style="min-height: 400px;">
                    <div id="camera-frame"
                        class="w-100 position-relative rounded-4 shadow-lg bg-dark overflow-hidden border-4 border-white"
                        style="max-width: 450px; aspect-ratio: 4/3;">
                        <!-- The Video Stream -->
                        <video id="live-camera" class="w-100 h-100 d-none" style="object-fit: cover;" autoplay
                            playsinline></video>

                        <!-- Camera Placeholder -->
                        <div id="camera-placeholder"
                            class="position-absolute top-50 start-50 translate-middle text-center text-white-50">
                            <i class="bi bi-camera-fill display-1 opacity-25 mb-3"></i>
                            <p class="small fw-bold text-uppercase" style="letter-spacing: 2px;">Identity Verification
                                System</p>
                        </div>

                        <!-- Scan Overlay (HUD) -->
                        <div id="scan-hud" class="position-absolute inset-0 d-none pointer-events-none">
                            <div
                                class="position-absolute top-0 start-0 w-10 h-10 border-top border-start border-primary border-4 m-4">
                            </div>
                            <div
                                class="position-absolute top-0 end-0 w-10 h-10 border-top border-end border-primary border-4 m-4">
                            </div>
                            <div
                                class="position-absolute bottom-0 start-0 w-10 h-10 border-bottom border-start border-primary border-4 m-4">
                            </div>
                            <div
                                class="position-absolute bottom-0 end-0 w-10 h-10 border-bottom border-end border-primary border-4 m-4">
                            </div>
                            <div class="position-absolute top-50 start-0 w-100 border-top border-primary border-2 opacity-25"
                                style="animation: scanTopToBottom 3s linear infinite;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body p-5 d-flex flex-column justify-content-center h-100">
                        <div class="mb-4">
                            <div class="badge bg-primary rounded-pill mb-3 px-3 py-2">LIVE VERIFICATION</div>
                            <h3 class="fw-bold text-dark">Punch In for Today</h3>
                            <p class="text-muted">{{ $uploadMessage }}</p>
                        </div>

                        @if ($canUpload)
                            <div class="d-grid gap-3">
                                <button type="button" id="start-camera-btn"
                                    class="btn btn-primary btn-lg rounded-pill shadow-lg py-3 fw-bold transition-all grow">
                                    <i class="bi bi-camera-video me-2"></i> Activate Camera
                                </button>

                                <button type="button" id="capture-photo-btn"
                                    class="btn btn-success btn-lg rounded-pill shadow-lg py-3 fw-bold d-none transition-all grow">
                                    <i class="bi bi-check2-circle me-2"></i> Confirm My Identity
                                </button>
                            </div>

                            <!-- Hidden Form -->
                            <form id="attendance-form" action="{{ route('student.attendance.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="photo" id="photo-input" class="d-none" required>
                            </form>
                            <canvas id="photo-canvas" class="d-none"></canvas>

                            <div class="mt-4 pt-4 border-top">
                                <div class="d-flex align-items-start">
                                    <div class="bg-info bg-opacity-10 p-2 rounded-3 me-3 text-info">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </div>
                                    <div class="small text-muted">
                                        <strong>Biometric Protocol:</strong> This photo will be used to verify your
                                        attendance
                                        against your saved profile. Ensure your face is clearly visible.
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-light rounded-4 text-center">
                                <i class="bi bi-lock-fill text-muted display-6 mb-3 d-block"></i>
                                <h6 class="fw-bold mb-1">Upload Window is Locked</h6>
                                <p class="text-muted small mb-0">Check the batch schedule for tomorrow's window.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- History Filter & Table -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden fade-in" style="animation-delay: 0.2s;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Attendance Logs</h5>
                    <button class="btn btn-sm btn-light border rounded-pill px-3" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Export Report
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('student.attendance.index') }}" method="GET"
                    class="row g-3 mb-4 p-3 bg-light rounded-4 border">
                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold text-muted">DATE RANGE (FROM)</label>
                        <input type="date" name="start_date" class="form-control rounded-pill border-0 shadow-sm px-4"
                            value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold text-muted">DATE RANGE (TO)</label>
                        <input type="date" name="end_date" class="form-control rounded-pill border-0 shadow-sm px-4"
                            value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold">
                            <i class="bi bi-funnel-fill me-2"></i> Update View
                        </button>
                    </div>
                </form>

                <div class="table-responsive rounded-4 border overflow-hidden mt-2">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 small">DATE / WEEKDAY</th>
                                <th class="text-center small">STATUS</th>
                                <th class="text-center small">VERIFICATION</th>
                                <th class="small">SESSION WINDOW</th>
                                <th class="small pe-4 text-end">REMARKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">
                                            {{ $attendance->attendance_date->format('d M, Y') }}
                                        </div>
                                        <div class="tiny text-muted fw-semibold text-uppercase"
                                            style="font-size: 0.65rem;">
                                            {{ $attendance->attendance_date->format('l') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusTheme =
                                                [
                                                    'present' => 'success',
                                                    'absent' => 'danger',
                                                    'late' => 'warning',
                                                    'excused' => 'info',
                                                ][$attendance->status] ?? 'secondary';
                                        @endphp
                                        <span
                                            class="badge bg-{{ $statusTheme }}-subtle text-{{ $statusTheme }} border-0 rounded-pill px-4 transition-all">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $verification = $attendance->verification_status;
                                            $verificationTheme =
                                                [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                ][$verification] ?? 'secondary';
                                        @endphp
                                        @if ($verification)
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <span
                                                    class="badge bg-{{ $verificationTheme }}-subtle text-{{ $verificationTheme }} border-0 rounded-pill px-3">
                                                    {{ ucfirst($verification) }}
                                                </span>
                                                @if ($attendance->photo_submitted_at)
                                                    <small class="text-muted tiny">
                                                        {{ $attendance->photo_submitted_at->format('h:i A') }}
                                                    </small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center small text-muted">
                                            <i class="bi bi-clock-history me-2 text-primary"></i>
                                            {{ $batch->start_time ?? '09:00 AM' }} -
                                            {{ \Carbon\Carbon::parse($batch->start_time ?? '09:00:00')->addMinutes(60)->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end small text-muted">
                                        {{ $attendance->remarks ?? 'System recorded' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">
                                        <img src="https://img.icons8.com/bubbles/100/null/cancel.png"
                                            class="mb-3 opacity-50" style="width: 80px;">
                                        <p class="mb-0">No attendance data found in this range.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if ($canUpload)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                        const video = document.getElementById('live-camera');
                        const canvas = document.getElementById('photo-canvas');
                        const startBtn = document.getElementById('start-camera-btn');
                        const captureBtn = document.getElementById('capture-photo-btn');
                        const placeholder = document.getElementById('camera-placeholder');
                        const hud = document.getElementById('scan-hud');
                        const form = document.getElementById('attendance-form');
                        const fileInput = document.getElementById('photo-input');
                        let stream = null;
                        startBtn.addEventListener('click', async function() {
                            try {
                                startBtn.innerHTML =
                                    '<span class="spinner-border spinner-border-sm me-2"></span> Handshaking...';
                                startBtn.disabled = true;
                                stream = await navigator.mediaDevices.getUserMedia({
                                        video: {
                                            facingMode: 'user'
                                        }, // Changed to 'user' for selfie-style attendance                     audio: false                 });
                                        video.srcObject = stream;video.classList.remove('d-none');placeholder
                                        .classList.add('d-none');hud.classList.remove('d-none');
                                        startBtn.classList.add('d-none');captureBtn.classList.remove('d-none');
                                    }
                                    catch (err) {
                                        console.error("Camera access error:", err);
                                        alert(
                                            "Secure access to camera failed. Please ensure permissions are granted.");
                                        startBtn.innerHTML =
                                            '<i class="bi bi-camera-video me-2"></i> Error (Retry)';
                                        startBtn.disabled = false;
                                    }
                                });
                            captureBtn.addEventListener('click', function() {
                                captureBtn.innerHTML =
                                    '<span class="spinner-border spinner-border-sm me-2"></span> Securing Identity...';
                                captureBtn.disabled = true;
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                                canvas.toBlob(function(blob) {
                                    const file = new File([blob], "STU_VERIFY_" + Date.now() +
                                        ".jpg", {
                                            type: "image/jpeg"
                                        });
                                    const dataTransfer = new DataTransfer();
                                    dataTransfer.items.add(file);
                                    fileInput.files = dataTransfer.files;
                                    if (stream) {
                                        stream.getTracks().forEach(track => track.stop());
                                    }
                                    form.submit();
                                }, 'image/jpeg', 0.9);
                            });
                        });
        </script>
    @endif

    <style>
        @keyframes scanTopToBottom {
            0% {
                top: 10%;
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                top: 90%;
                opacity: 0;
            }
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .pointer-events-none {
            pointer-events: none;
        }
    </style>
@endsection
