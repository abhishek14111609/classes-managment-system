@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('hide_header', true)
@section('custom_sidebar_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 fade-in">
            <div>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Dashboard Overview</h2>
                <p class="text-muted mb-0">Welcome back, here's what's happening today.</p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-3">
                <span class="badge bg-white border px-3 py-2 text-muted shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-calendar3"></i> {{ date('F j, Y') }}
                </span>
                <a href="{{ route('admin.dashboard.export') }}" class="btn btn-primary shadow-sm" target="_blank">
                    <i class="bi bi-download"></i> Export Report
                </a>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-5 fade-in" style="animation-delay: 0.1s;">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card primary">
                    <div class="position-relative z-2">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-circle p-2 d-flex justify-content-center align-items-center"
                                style="background-color: rgba(255, 255, 255, 0.2); width: 48px; height: 48px;">
                                <i class="bi bi-building fs-4 text-white"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-20 text-white border-0 fw-bold">Total</span>
                        </div>
                        <h3 class="mb-1 display-6 fw-bold text-white">{{ $stats['total_schools'] }}</h3>
                        <p class="text-white opacity-75 small mb-0 fw-medium">Registered Schools</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card success">
                    <div class="position-relative z-2">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-circle p-2 d-flex justify-content-center align-items-center"
                                style="background-color: rgba(255, 255, 255, 0.2); width: 48px; height: 48px;">
                                <i class="bi bi-check-circle fs-4 text-white"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-20 text-white border-0 fw-bold">Active</span>
                        </div>
                        <h3 class="mb-1 display-6 fw-bold text-white">{{ $stats['active_schools'] }}</h3>
                        <p class="text-white opacity-75 small mb-0 fw-medium">Currently Operating</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card danger">
                    <div class="position-relative z-2">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-circle p-2 d-flex justify-content-center align-items-center"
                                style="background-color: rgba(255, 255, 255, 0.2); width: 48px; height: 48px;">
                                <i class="bi bi-exclamation-triangle fs-4 text-white"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-20 text-white border-0 fw-bold">Action Needed</span>
                        </div>
                        <h3 class="mb-1 display-6 fw-bold text-white">{{ $stats['expired_schools'] }}</h3>
                        <p class="text-white opacity-75 small mb-0 fw-medium">Expired Subscriptions</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card info">
                    <div class="position-relative z-2">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-circle p-2 d-flex justify-content-center align-items-center"
                                style="background-color: rgba(255, 255, 255, 0.2); width: 48px; height: 48px;">
                                <i class="bi bi-wallet2 fs-4 text-white"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-20 text-white border-0 fw-bold">Revenue</span>
                        </div>
                        <h3 class="mb-1 display-6 fw-bold text-white">₹{{ number_format($stats['total_revenue'], 2) }}</h3>
                        <p class="text-white opacity-75 small mb-0 fw-medium">Total Earnings</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations Snapshot -->
        <div class="row g-4 mb-4 fade-in" style="animation-delay: 0.15s;">
            <div class="col-xl-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-muted text-uppercase small mb-1">Active Subscriptions</p>
                                <h4 class="fw-bold mb-1">{{ $stats['active_subscriptions'] }}</h4>
                                <p class="small text-muted mb-0">{{ $stats['expiring_soon_count'] }} expiring within 7 days
                                </p>
                            </div>
                            <span
                                class="badge bg-success-subtle text-success fw-semibold">₹{{ number_format($stats['monthly_revenue'], 0) }}
                                this month</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.subscriptions.index') }}"
                                class="btn btn-sm btn-outline-primary">Renewals</a>
                            <a href="{{ route('admin.schools.index') }}" class="btn btn-sm btn-light border">Manage
                                Schools</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-muted text-uppercase small mb-1">Plan Catalog</p>
                                <h4 class="fw-bold mb-1">{{ $stats['plan_count'] }} Plans</h4>
                                <p class="small text-muted mb-0">Keeps schools on the right tier</p>
                            </div>
                            <span class="badge bg-primary-subtle text-primary fw-semibold">Templates</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.plans.index') }}" class="btn btn-sm btn-outline-primary">Plan
                                Library</a>
                            <a href="{{ route('admin.plans.create') }}" class="btn btn-sm btn-primary">New Plan</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-muted text-uppercase small mb-1">User Accounts</p>
                                <h4 class="fw-bold mb-1">{{ $stats['users_total'] }}</h4>
                                <p class="small text-muted mb-0">{{ $stats['users_active'] }} active /
                                    {{ $stats['users_total'] - $stats['users_active'] }} paused</p>
                            </div>
                            <span class="badge bg-dark-subtle text-dark fw-semibold">{{ $stats['logs_today'] }} logs
                                today</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">User
                                Control</a>
                            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-light border">Audit
                                Trail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Administration Modules -->
        <div class="card border-0 shadow-sm fade-in" style="animation-delay: 0.2s;">
            <div class="card-header bg-white border-0 pt-4 pb-1">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-main mb-1">Main Admin Panel</h5>
                        <small class="text-muted">All super admin levers in one place</small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-dark border">{{ $stats['total_schools'] }} Schools</span>
                        <span class="badge bg-light text-dark border">{{ $stats['plan_count'] }} Plans</span>
                        <span class="badge bg-light text-dark border">{{ $stats['users_total'] }} Users</span>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 px-4">
                <div class="row g-3">
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-tile h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="small text-uppercase text-muted mb-1">Schools</p>
                                    <h5 class="fw-bold mb-0">{{ $stats['total_schools'] }}</h5>
                                    <p class="tiny text-muted mb-0">{{ $stats['active_schools'] }} active /
                                        {{ $stats['inactive_schools'] }} inactive</p>
                                </div>
                                <span class="badge bg-primary-subtle text-primary">Registry</span>
                            </div>
                            <p class="small text-muted mb-3">Create, suspend, and renew institutions.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.schools.index') }}"
                                    class="btn btn-sm btn-outline-primary">Manage</a>
                                <a href="{{ route('admin.schools.create') }}" class="btn btn-sm btn-primary">Add</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="admin-tile h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="small text-uppercase text-muted mb-1">Plans</p>
                                    <h5 class="fw-bold mb-0">{{ $stats['plan_count'] }}</h5>
                                    <p class="tiny text-muted mb-0">Keep price tiers consistent</p>
                                </div>
                                <span class="badge bg-success-subtle text-success">Pricing</span>
                            </div>
                            <p class="small text-muted mb-3">Define limits and billing cadence.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.plans.index') }}" class="btn btn-sm btn-outline-primary">Plan
                                    List</a>
                                <a href="{{ route('admin.plans.create') }}" class="btn btn-sm btn-success">New Plan</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="admin-tile h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="small text-uppercase text-muted mb-1">Subscriptions</p>
                                    <h5 class="fw-bold mb-0">{{ $stats['active_subscriptions'] }}</h5>
                                    <p class="tiny text-muted mb-0">Revenue:
                                        ₹{{ number_format($stats['total_revenue'], 0) }}</p>
                                </div>
                                <span class="badge bg-warning-subtle text-dark">Billing</span>
                            </div>
                            <p class="small text-muted mb-3">Track invoices and renewal windows.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.subscriptions.index') }}"
                                    class="btn btn-sm btn-outline-primary">Subscriptions</a>
                                @if ($stats['recent_subscriptions']->isNotEmpty())
                                    <a href="{{ route('admin.subscriptions.download', ['subscription' => $stats['recent_subscriptions']->first()->id]) }}"
                                        class="btn btn-sm btn-light border">Latest Invoice</a>
                                @else
                                    <button class="btn btn-sm btn-light border disabled" type="button">Latest
                                        Invoice</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="admin-tile h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="small text-uppercase text-muted mb-1">Users & Roles</p>
                                    <h5 class="fw-bold mb-0">{{ $stats['users_total'] }}</h5>
                                    <p class="tiny text-muted mb-0">{{ $stats['users_active'] }} active accounts</p>
                                </div>
                                <span class="badge bg-dark-subtle text-dark">Security</span>
                            </div>
                            <p class="small text-muted mb-3">Activate, pause, and update privileges.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">User
                                    Control</a>
                                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-secondary">Audit
                                    Logs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Section -->
        <div class="row g-4 mb-4 fade-in" style="animation-delay: 0.2s;">
            <!-- Expiring Soon -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div
                        class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-main mb-1">Expiring Schools</h5>
                            <small class="text-muted">Renewals needed within 15 days</small>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 rounded-pill">
                            {{ $stats['expiring_soon_count'] }} Critical
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @if ($stats['expiring_soon']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light bg-opacity-50">
                                        <tr>
                                            <th class="ps-4 border-0 py-3 small text-muted">SCHOOL</th>
                                            <th class="border-0 py-3 small text-muted">DUE ON</th>
                                            <th class="text-end pe-4 border-0 py-3 small text-muted">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stats['expiring_soon'] as $school)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold text-dark">{{ $school->name }}</div>
                                                    <small class="text-muted">{{ $school->email }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $school->subscription_expires_at->isPast() ? 'danger' : 'warning-subtle text-dark' }} px-2 py-1">
                                                        {{ $school->subscription_expires_at->format('d M, Y') }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('admin.schools.show', $school) }}"
                                                        class="btn btn-sm btn-outline-primary border-0">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-shield-check display-4 text-success opacity-25"></i>
                                <p class="text-muted mt-3">All schools have active subscriptions.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div
                        class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-main mb-1">Recent Subscriptions</h5>
                            <small class="text-muted">Latest revenue transactions</small>
                        </div>
                        <h5 class="text-success fw-bold mb-0">₹{{ number_format($stats['monthly_revenue'], 2) }} <small
                                class="text-muted fw-normal" style="font-size: 0.6em;">/mo</small></h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-4 border-0 py-3 small text-muted">SCHOOL</th>
                                        <th class="border-0 py-3 small text-muted">AMOUNT</th>
                                        <th class="text-end pe-4 border-0 py-3 small text-muted">PLAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stats['recent_subscriptions'] as $sub)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold">{{ $sub->school->name }}</div>
                                                <small class="text-muted">{{ $sub->invoice_date->format('d M') }}</small>
                                            </td>
                                            <td><span
                                                    class="fw-bold text-success">₹{{ number_format($sub->amount_paid, 2) }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary">{{ $sub->plan->name }}</span>
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

        <!-- System Activity -->
        <div class="row g-4 mb-4 fade-in" style="animation-delay: 0.25s;">
            <div class="col-lg-8">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-main mb-1">Latest Security & Operations Logs</h5>
                            <small class="text-muted">Live footprint of recent changes</small>
                        </div>
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-primary">View
                            All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-4 border-0 small text-muted">Event</th>
                                        <th class="border-0 small text-muted">User</th>
                                        <th class="border-0 small text-muted">Target</th>
                                        <th class="text-end pe-4 border-0 small text-muted">When</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['latest_logs'] as $log)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold text-capitalize">{{ $log->action }}
                                                    {{ $log->module }}</div>
                                                <small
                                                    class="text-muted">{{ $log->description ?? 'No description' }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $log->user->name ?? 'System' }}</div>
                                                <small
                                                    class="text-muted">{{ $log->user->roles->pluck('name')->implode(', ') ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $log->school->name ?? 'Global' }}</div>
                                                <small class="text-muted">{{ $log->ip_address ?? '-' }}</small>
                                            </td>
                                            <td class="text-end pe-4"><span
                                                    class="badge bg-light text-dark">{{ $log->created_at->diffForHumans() }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No recent activity
                                                recorded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-main mb-1">Newest Accounts</h5>
                            <small class="text-muted">Quick access to fresh signups</small>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">All Users</a>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($stats['recent_users'] as $user)
                                <li class="list-group-item d-flex align-items-start justify-content-between">
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted d-block">{{ $user->email }}</small>
                                        <small
                                            class="text-muted">{{ $user->roles->pluck('name')->implode(', ') ?: 'Unassigned' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span
                                            class="badge {{ $user->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">{{ $user->is_active ? 'Active' : 'Paused' }}</span>
                                        <div class="tiny text-muted mt-1">
                                            {{ optional($user->school)->name ?? 'Super Admin' }}</div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">No users created recently.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm fade-in" style="animation-delay: 0.3s;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h5 class="fw-bold text-main mb-1">Quick Actions</h5>
                        <p class="text-muted small mb-0">Common tasks you might want to perform.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.schools.create') }}"
                            class="btn btn-light text-primary fw-bold px-4 hover-lift border">
                            <i class="bi bi-plus-lg me-2"></i> Add School
                        </a>
                        <a href="{{ route('admin.plans.create') }}"
                            class="btn btn-light text-success fw-bold px-4 hover-lift border">
                            <i class="bi bi-file-earmark-plus me-2"></i> Create Plan
                        </a>
                        <a href="{{ route('admin.schools.index') }}" class="btn btn-primary px-4 hover-lift">
                            <i class="bi bi-list-ul me-2"></i> Manage Schools
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hover-lift {
            transition: transform 0.2s;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }

        .admin-tile {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 16px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .admin-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }
    </style>
@endsection
