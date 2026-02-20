@extends('layouts.app')

@section('title', 'Security Audit Ledger')
@section('hide_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Audit Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 fade-in">
            <div>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Security Audit Ledger</h2>
                <p class="text-muted mb-0">High-fidelity tracking of all institutional and platform-wide events.</p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <button class="btn btn-white border shadow-sm px-4">
                    <i class="bi bi-download me-2"></i> Export Logs
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Operational Events</h5>
                <div class="input-group w-auto">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0 ps-0 tiny" placeholder="Search events..." style="width: 200px;">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr class="tiny text-muted">
                                <th class="ps-4">TEMPORAL MARK</th>
                                <th>EXECUTING ENTITY</th>
                                <th>LOCALITY</th>
                                <th>TACTICAL ACTION</th>
                                <th>DESCRIPTION</th>
                                <th class="text-end pe-4">ORIGIN IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                @php
                                    $actionClass = match ($log->action) {
                                        'create' => 'bg-success text-success',
                                        'update' => 'bg-info text-info',
                                        'delete' => 'bg-danger text-danger',
                                        'login'  => 'bg-primary text-primary',
                                        default  => 'bg-secondary text-secondary'
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark small">{{ $log->created_at->format('d M, Y') }}</div>
                                        <div class="tiny text-muted">{{ $log->created_at->format('h:i:s A') }}</div>
                                    </td>
                                    <td>
                                        @if($log->user)
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold me-2 tiny" style="width: 24px; height: 24px; font-size: 0.6rem;">
                                                    {{ substr($log->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark small">{{ $log->user->name }}</div>
                                                    <div class="tiny text-muted">{{ $log->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted tiny fst-italic">System Automata</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->school)
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2 py-1 tiny">
                                                <i class="bi bi-building me-1"></i> {{ $log->school->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-dark bg-opacity-10 text-dark border border-dark border-opacity-10 px-2 py-1 tiny">
                                                <i class="bi bi-globe me-1"></i> Global Nexus
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $actionClass }} bg-opacity-10 border border-opacity-10 px-2 py-1 text-uppercase tiny" style="letter-spacing: 0.5px;">
                                            {{ $log->action }}
                                        </span>
                                        <div class="tiny text-muted mt-1">{{ ucfirst($log->module) }}</div>
                                    </td>
                                    <td>
                                        <p class="mb-0 small text-dark" style="max-width: 300px; line-height: 1.4;">{{ $log->description }}</p>
                                    </td>
                                    <td class="text-end pe-4">
                                        <code class="bg-light px-2 py-1 rounded tiny">{{ $log->ip_address }}</code>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-shield-lock d-block" style="font-size: 4rem;"></i></div>
                                        <h5 class="text-muted">Security Record Empty</h5>
                                        <p class="text-muted small">Operational logs will materialize here upon event execution.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($logs->hasPages())
                <div class="card-footer bg-white border-top-0 py-4">
                    {{ $logs->links() }}
                </div>
            @endif
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
        .tiny { font-size: 0.75rem; }
    </style>
@endsection