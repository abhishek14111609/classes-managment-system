@extends('layouts.app')

@section('title', $isSport ? 'Athlete Dashboard' : 'Student Dashboard')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Premium Personalized Header -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden p-2">
                    <div class="card-body p-4 d-flex align-items-center flex-wrap position-relative z-1">
                        <div class="me-4 position-relative">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle border border-white border-opacity-25 shadow-sm" style="width: 90px; height: 90px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-20 d-flex align-items-center justify-content-center text-primary fw-bold shadow-sm border border-primary border-opacity-25" style="width: 90px; height: 90px; font-size: 2rem;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border-white border-2" style="width: 20px; height: 20px; box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);"></div>
                        </div>
                        <div class="grow">
                            <h2 class="fw-bold mb-1">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! <span class="animate-wave d-inline-block">🚀</span></h2>
                            <div class="d-flex align-items-center flex-wrap gap-2 mt-2">
                                @forelse($student->batches as $batch)
                                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 rounded-pill px-3 py-2 small backdrop-blur">
                                        <i class="bi bi-patch-check-fill text-primary me-1"></i> {{ $batch->class->name }} ({{ $batch->name }})
                                    </span>
                                @empty
                                    <span class="badge bg-secondary bg-opacity-20 text-white border border-white border-opacity-10 rounded-pill px-3 py-2 small backdrop-blur">
                                        <i class="bi bi-clock-history me-1"></i> Pending Squad
                                    </span>
                                @endforelse
                                <span class="text-white text-opacity-75 small ms-md-2 d-block d-md-inline-block mt-2 mt-md-0">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ auth()->user()->school->name ?? 'Campus Arena' }}
                                </span>
                            </div>
                        </div>
                        <div class="ms-md-auto mt-4 mt-md-0 d-flex flex-column align-items-md-end">
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-end">
                                    <p class="mb-0 tiny text-white text-opacity-50 fw-bold text-uppercase" style="letter-spacing: 1.5px;">Performance</p>
                                    <h4 class="fw-bold mb-0 text-white">{{ $athleteScore ?? 0 }}%</h4>
                                </div>
                                <div class="position-relative d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <svg class="w-100 h-100" viewBox="0 0 36 36">
                                        <circle cx="18" cy="18" r="16" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="3"></circle>
                                        <circle cx="18" cy="18" r="16" fill="none" stroke="#6366f1" stroke-width="3" stroke-dasharray="{{ $athleteScore ?? 0 }}, 100" stroke-linecap="round"></circle>
                                    </svg>
                                    <div class="position-absolute">
                                        <i class="bi bi-stars text-warning pulse-yellow"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($stats['pending_fees'] > 0)
            <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4 rounded-4 fade-in">
                <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
                <div class="grow">
                    <h6 class="mb-0 fw-bold">Financial Alert</h6>
                    <p class="mb-0 small">An outstanding balance of <strong>₹{{ number_format($stats['pending_fees'], 2) }}</strong> requires your attention. Late fees may apply soon.</p>
                </div>
                <a href="{{ route('student.fees.index') }}" class="btn btn-danger btn-sm ms-3 px-4 rounded-pill fw-bold shadow-sm">Review & Pay</a>
            </div>
        @endif

        <!-- Premium Stat Cards -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 h-100 shadow-sm transition-all grow" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 shadow-sm border border-white border-opacity-10">
                                <i class="bi bi-calendar-check-fill text-white fs-4"></i>
                            </div>
                            <span class="badge bg-white text-primary rounded-pill tiny shadow-sm">Consistency</span>
                        </div>
                        <h2 class="fw-bold text-white mb-1">{{ $stats['attendance_percentage'] }}%</h2>
                        <p class="text-white text-opacity-75 small mb-0 fw-medium">Overall Attendance Rate</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 h-100 shadow-sm transition-all grow" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 shadow-sm border border-white border-opacity-10">
                                <i class="bi bi-trophy-fill text-white fs-4"></i>
                            </div>
                            <span class="badge bg-white text-success rounded-pill tiny shadow-sm">Achievements</span>
                        </div>
                        <h2 class="fw-bold text-white mb-1">{{ $stats['events_participated'] }}</h2>
                        <p class="text-white text-opacity-75 small mb-0 fw-medium">Sports Participations</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 h-100 shadow-sm transition-all grow" style="background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 shadow-sm border border-white border-opacity-10">
                                <i class="bi bi-wallet-fill text-white fs-4"></i>
                            </div>
                            <span class="badge bg-white text-danger rounded-pill tiny shadow-sm">Account</span>
                        </div>
                        <h2 class="fw-bold text-white mb-1">₹{{ number_format($stats['pending_fees']) }}</h2>
                        <p class="text-white text-opacity-75 small mb-0 fw-medium">Remaining Balance</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 h-100 shadow-sm transition-all grow" style="background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 shadow-sm border border-white border-opacity-10">
                                <i class="bi bi-patch-check-fill text-white fs-4"></i>
                            </div>
                            <span class="badge bg-white text-info rounded-pill tiny shadow-sm">Wallet</span>
                        </div>
                        <h2 class="fw-bold text-white mb-1">₹{{ number_format($stats['paid_fees']) }}</h2>
                        <p class="text-white text-opacity-75 small mb-0 fw-medium">Total Fees Contributed</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Learning Journey & Next Sessions -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0">{{ $label['timetable'] }}</h5>
                            <p class="text-muted small mb-0">Your upcoming sessions</p>
                        </div>
                        <a href="{{ route('student.timetable') }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-none">View full timetable</a>
                    </div>
                    <div class="card-body p-4">
                        @foreach($upcomingSessions as $session)
                            <div class="p-4 rounded-4 position-relative overflow-hidden mb-4 shadow-sm border" style="background: linear-gradient(to right, #f8fafc, #ffffff);">
                                <div class="row align-items-center position-relative z-1">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary rounded-pill tiny me-2 px-3 py-2">LIVE SESSION</span>
                                            <span class="text-primary fw-bold small"><i class="bi bi-clock-fill me-1"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }}</span>
                                        </div>
                                        <h4 class="fw-bold text-dark mb-1">{{ $session->class->name }}</h4>
                                        <p class="text-muted mb-3"><i class="bi bi-person-workspace me-1"></i>
                                            {{ $isSport ? 'Coach' : 'Teacher' }}: <span class="fw-semibold text-dark">{{ $session->teachers->first()->user->name ?? 'Instructor' }}</span></p>
                                        <div class="d-flex gap-2">
                                            <div class="bg-white px-3 py-2 rounded-pill shadow-sm border small fw-bold"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Training Area A</div>
                                            <div class="bg-white px-3 py-2 rounded-pill shadow-sm border small fw-bold"><i class="bi bi-people-fill text-info me-1"></i> {{ $session->students_count ?? '0' }} Peers</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center d-none d-md-block">
                                        <img src="https://img.icons8.com/bubbles/100/null/physical-fitness.png" alt="Fitness" style="width: 120px;">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <h6 class="fw-bold mb-3 small text-muted text-uppercase mt-5" style="letter-spacing: 1px;">Recent Attendance Records</h6>
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 small ps-4">DATE</th>
                                        <th class="border-0 small">BATCH / SESSION</th>
                                        <th class="border-0 small text-center">STATUS</th>
                                        <th class="border-0 small pe-4 text-end">REMARKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentAttendance as $record)
                                        <tr>
                                            <td class="ps-4 fw-medium">{{ $record->attendance_date->format('d M, Y') }}</td>
                                            <td>
                                                <div class="fw-bold small">{{ $record->batch->name }}</div>
                                                <div class="tiny text-muted">{{ $record->batch->class->name }}</div>
                                            </td>
                                            <td class="text-center">
                                                @php $sClass = $record->status == 'present' ? 'success' : ($record->status == 'absent' ? 'danger' : 'warning'); @endphp
                                                <span class="badge bg-{{ $sClass }}-subtle text-{{ $sClass }} border-0 rounded-pill px-3">{{ ucfirst($record->status) }}</span>
                                            </td>
                                            <td class="pe-4 text-end small text-muted">{{ $record->remarks ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted small">No recent attendance found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info & Events -->
            <div class="col-lg-4">
                <!-- Upcoming Sports Events -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0">Upcoming {{ $label['events'] }}</h5>
                    </div>
                    <div class="card-body p-4">
                        @forelse($upcomingEvents as $event)
                            <div class="d-flex mb-4 transition-all hover-lift">
                                <div class="shrink-0 bg-primary bg-opacity-10 text-primary rounded-4 d-flex flex-column align-items-center justify-content-center p-2 me-3" style="width: 60px; height: 60px;">
                                    <span class="fw-bold lh-1">{{ $event->event_date->format('d') }}</span>
                                    <span class="tiny fw-bold text-uppercase">{{ $event->event_date->format('M') }}</span>
                                </div>
                                <div class="grow">
                                    <h6 class="fw-bold text-dark mb-1">{{ $event->name }}</h6>
                                    <p class="text-muted tiny mb-1"><i class="bi bi-geo-alt me-1"></i> {{ $event->location ?? 'Main Arena' }}</p>
                                    <small class="text-primary fw-bold tiny"><i class="bi bi-person-fill me-1"></i> {{ $label['teacher'] }} {{ $event->coach->user->name ?? 'Head' }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded-4">
                                <img src="https://img.icons8.com/bubbles/100/null/trophy.png" alt="No Events" style="width: 60px;" class="mb-2 opacity-50">
                                <p class="text-muted small mb-0">Stay tuned for upcoming tournaments!</p>
                            </div>
                        @endforelse
                        <a href="{{ route('student.events.index') }}" class="btn btn-light bg-white border rounded-pill w-100 fw-bold mt-2 py-2">View All</a>
                    </div>
                </div>

                <!-- Financial Health Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-dark text-white overflow-hidden">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="text-white opacity-75 fw-bold mb-0 tiny" style="letter-spacing: 1px;">Fee Summary</h6>
                            <i class="bi bi-credit-card-2-front-fill fs-4 text-white-50"></i>
                        </div>
                        @php 
                            $totalAmount = $stats['total_fees'];
                            $paidAmount = $stats['paid_fees'];
                            $payPercent = $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0;
                        @endphp
                        <h3 class="fw-bold mb-1">₹{{ number_format($stats['pending_fees']) }}</h3>
                        <p class="small text-white-50 mb-4">Remaining Balance for this term</p>
                        
                        <div class="progress mb-2 bg-secondary bg-opacity-25" style="height: 8px; border-radius: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $payPercent }}%;" aria-valuenow="{{ $payPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between small text-white-50 mb-4">
                            <span>Contribution: {{ round($payPercent) }}%</span>
                            <span>Limit: ₹{{ number_format($totalAmount) }}</span>
                        </div>
                        
                        <a href="{{ route('student.fees.index') }}" class="btn btn-primary rounded-pill w-100 py-2 fw-bold shadow-sm">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Make Quick Payment
                        </a>
                    </div>
                    <div class="position-absolute end-0 bottom-0 h-100 w-50 bg-primary opacity-10" style="clip-path: polygon(100% 0, 0% 100%, 100% 100%);"></div>
                </div>

                <!-- Support Ticket -->
                <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-20 p-2 rounded-3 me-3">
                                <i class="bi bi-chat-heart-fill text-primary"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Instant Support</h6>
                        </div>
                        <p class="text-secondary small mb-3">Locked out? Or need a fee extension? Contact your center's administrative desk for prompt resolution.</p>
                        <a href="mailto:{{ auth()->user()->school->email ?? 'support@webvibeinfotech.in' }}" class="text-primary fw-bold small text-decoration-none">Reach administration <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Feed / Financial Ledger -->
        <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">Financial History Ledger</h5>
                <p class="text-muted small">Comprehensive record of all transactions</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive rounded-4 border overflow-hidden">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 small ps-4">DATE</th>
                                <th class="border-0 small">DESCRIPTION</th>
                                <th class="border-0 small text-end">DEBIT (DR)</th>
                                <th class="border-0 small text-end">CREDIT (CR)</th>
                                <th class="border-0 small pe-4 text-end">RUNNING BALANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $runningBalance = 0; @endphp
                            @forelse($ledger as $entry)
                                @php
                                    $dr = data_get($entry, 'dr', 0);
                                    $cr = data_get($entry, 'cr', 0);
                                    $runningBalance += $dr - $cr;
                                    $isNeg = $runningBalance > 0;
                                @endphp
                                <tr>
                                    <td class="ps-4 small fw-medium text-muted">{{ data_get($entry, 'date')->format('d M, Y') }}</td>
                                    <td>
                                        <div class="fw-bold small text-dark">{{ data_get($entry, 'description') }}</div>
                                        <div class="tiny text-muted text-uppercase" style="font-size: 0.6rem;">{{ data_get($entry, 'reference') }}</div>
                                    </td>
                                    <td class="text-end text-danger fw-bold small">
                                        {{ $dr > 0 ? '₹' . number_format($dr) : '—' }}
                                    </td>
                                    <td class="text-end text-success fw-bold small">
                                        {{ $cr > 0 ? '₹' . number_format($cr) : '—' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="badge {{ $isNeg ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} border-0 rounded-pill px-3">
                                            ₹{{ number_format(abs($runningBalance)) }} {{ $isNeg ? 'DR' : 'CR' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">No transactions recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pulse-blue {
            animation: pulse-blue 2s infinite;
        }

        @keyframes pulse-blue {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }

        .pulse-yellow {
            animation: pulse-yellow 2s infinite;
            display: inline-block;
        }

        @keyframes pulse-yellow {
            0% { transform: scale(0.9); opacity: 0.7; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.9); opacity: 0.7; }
        }

        .animate-wave {
            animation: wave 2.1s infinite;
            transform-origin: 75% 70%;
            display: inline-block;
        }

        @keyframes wave {
            0% { transform: rotate( 0.0deg) }
            10% { transform: rotate(14.0deg) }
            20% { transform: rotate(-8.0deg) }
            30% { transform: rotate(14.0deg) }
            40% { transform: rotate(-4.0deg) }
            50% { transform: rotate(10.0deg) }
            60% { transform: rotate( 0.0deg) }
            100% { transform: rotate( 0.0deg) }
        }

        .backdrop-blur {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
       
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush
