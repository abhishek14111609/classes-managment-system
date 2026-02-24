@extends('layouts.app')

@section('title', 'Institutional Profile - ' . $school->name)
@section('hide_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Back Navigation & Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 fade-in">
            <div class="mb-3 mb-md-0">
                <a href="{{ route('admin.schools.index') }}" class="btn btn-link text-decoration-none p-0 mb-2 text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Back to School Registry
                </a>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">{{ $school->name }}</h2>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill small">ID: #{{ $school->id }}</span>
                    <span class="badge bg-{{ $school->status === 'active' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $school->status === 'active' ? 'success' : 'danger' }} px-3 py-1 rounded-pill small">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> {{ ucfirst($school->status) }}
                    </span>
                </div>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-white border shadow-sm px-4">
                    <i class="bi bi-pencil-square me-2"></i> Edit Institution
                </a>
                <form action="{{ route('admin.schools.toggle-status', $school) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-{{ $school->status === 'active' ? 'warning' : 'success' }} px-4 shadow-sm">
                        <i class="bi bi-toggle-{{ $school->status === 'active' ? 'on' : 'off' }} me-2"></i>
                        {{ $school->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Strategic Overview Cards -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 text-primary">
                                <i class="bi bi-credit-card-2-back-fill fs-4"></i>
                            </div>
                        </div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Current Subscription</h6>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $school->activeSubscription ? $school->activeSubscription->plan->name : 'No Active Plan' }}
                        </h4>
                    </div>
                    @if($school->subscription_expires_at)
                        <div class="bg-light px-4 py-2 border-top">
                            <small class="text-muted">Expires: <span class="fw-bold text-dark">{{ $school->subscription_expires_at->format('M d, Y') }}</span></small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-4 text-success">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                        </div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Estimated Load</h6>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $school->users_count ?? 0 }} Registered Users
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded-4 text-info">
                                <i class="bi bi-wallet2 fs-4"></i>
                            </div>
                        </div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Contribution</h6>
                        <h4 class="fw-bold text-dark mb-0">
                            ₹{{ number_format($school->subscriptions->sum('amount_paid'), 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-4 text-warning">
                                <i class="bi bi-calendar-event fs-4"></i>
                            </div>
                        </div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Membership Since</h6>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $school->created_at->format('M Y') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Institutional Details -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <h5 class="fw-bold text-dark mb-0">Institutional Intelligence</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4">
                                    <label class="tiny text-muted d-block mb-1">Principal Email Address</label>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-envelope-at text-primary me-2"></i>
                                        <span class="fw-bold">{{ $school->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4">
                                    <label class="tiny text-muted d-block mb-1">Institutional Line</label>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-telephone-outbound text-primary me-2"></i>
                                        <span class="fw-bold">{{ $school->phone ?? 'Not Configured' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4">
                                    <label class="tiny text-muted d-block mb-1">Type of Institute</label>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building text-primary me-2"></i>
                                        <span class="fw-bold">{{ ucfirst($school->institute_type) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4">
                                    <label class="tiny text-muted d-block mb-1">Physical Headquarters</label>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                        <span class="fw-bold text-dark">{{ $school->address ?? 'Address unconfirmed.' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h6 class="fw-bold text-dark mb-3">System Configuration Summary</h6>
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle">
                                    <tbody>
                                        <tr>
                                            <td class="ps-0 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                                        <i class="bi bi-shield-check text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-bold">Multi-Tenant Isolation</p>
                                                        <small class="text-muted">Schools are logically separated in the database.</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Enabled</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-indigo bg-opacity-10 p-2 rounded-3 me-3">
                                                        <i class="bi bi-fingerprint text-indigo"></i>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-bold">Domain Binding</p>
                                                        <small class="text-muted">Unique sub-domain identifier mapped to registry.</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0">
                                                <span class="text-muted small fw-bold">{{ Str::slug($school->name) }}.webvibe.in</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription History -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0">Subscription Lifecycle</h5>
                        <button class="btn btn-sm btn-outline-primary border-0" data-bs-toggle="modal" data-bs-target="#extendModal{{ $school->id }}">
                            <i class="bi bi-plus-lg me-1"></i> Manual Renewal
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr class="tiny text-muted">
                                        <th class="ps-4">INVOICE #</th>
                                        <th>PLAN</th>
                                        <th>AMOUNT</th>
                                        <th>PERIOD</th>
                                        <th class="text-end pe-4">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($school->subscriptions as $sub)
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">{{ $sub->invoice_number }}</td>
                                            <td>{{ $sub->plan->name }}</td>
                                            <td>₹{{ number_format($sub->amount_paid, 2) }}</td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $sub->start_date->format('d M') }} - {{ $sub->end_date->format('d M, Y') }}
                                                </small>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Paid</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <p class="text-muted mb-0">No historical subscription data found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Intelligence -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-flex mb-3">
                            <i class="bi bi-mortarboard-fill fs-1 text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Administrative Node</h5>
                        <p class="text-muted small mb-4">Initial administrator account assigned to this node.</p>
                        
                        @php $admin = $school->schoolAdmin; @endphp
                        @if($admin)
                            <div class="p-3 border rounded-4 text-start">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold me-2" style="width: 32px; height: 32px;">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                    <div class="fw-bold text-dark">{{ $admin->name }}</div>
                                </div>
                                <div class="text-muted small"><i class="bi bi-envelope-fill me-2"></i>{{ $admin->email }}</div>
                            </div>
                        @else
                            <div class="alert alert-warning border-0 small mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i> No administrator account found.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 opacity-75">Usage Monitoring</h6>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Student Quota</small>
                                <small class="fw-bold">{{ $school->students_count ?? 0 }} / {{ $school->activeSubscription->plan->student_limit ?? '∞' }}</small>
                            </div>
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1);">
                                @php 
                                    $studentLimit = $school->activeSubscription->plan->student_limit ?? 1;
                                    $studentPercent = ($school->students_count ?? 0) / $studentLimit * 100;
                                @endphp
                                <div class="progress-bar bg-primary" style="width: {{ $studentPercent }}%"></div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Staff Nodes</small>
                                <small class="fw-bold">{{ $school->teachers_count ?? 0 }} / {{ $school->activeSubscription->plan->batch_limit ?? '∞' }}</small>
                            </div>
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1);">
                                @php 
                                    $batchLimit = $school->activeSubscription->plan->batch_limit ?? 1;
                                    $batchPercent = ($school->teachers_count ?? 0) / $batchLimit * 100;
                                @endphp
                                <div class="progress-bar bg-info" style="width: {{ $batchPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Extend Subscription Modal (Repeated from Index for UX continuity) -->
    <div class="modal fade" id="extendModal{{ $school->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.schools.extend-subscription', $school) }}" method="POST">
                @csrf
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Resource Extension</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <p class="text-muted mb-4">Append operational days to <span class="fw-bold text-primary">{{ $school->name }}</span>.</p>
                        <div class="form-floating mb-3">
                            <input type="number" name="days" class="form-control bg-light border-0" id="extensionDays" value="30" required>
                            <label for="extensionDays">Days to Extend</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">Abort</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">Execute Extension</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeIn {
            to { opacity: 1; transform: translateY(0); }
        }
        .bg-indigo { background-color: #6610f2; }
        .text-indigo { color: #6610f2; }
    </style>
@endsection
