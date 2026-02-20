@extends('layouts.app')

@section('title', 'Renewal Tracking & Lifecycle')
@section('hide_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Revenue Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 fade-in">
            <div>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Renewal Tracking</h2>
                <p class="text-muted mb-0">Financial records and institutional subscription lifecycle monitoring.</p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <div class="bg-white border rounded-pill px-4 py-2 shadow-sm">
                    <span class="text-muted small fw-bold">TOTAL REVENUE:</span>
                    <span
                        class="text-primary fw-bold ms-2">₹{{ number_format($subscriptions->sum('amount_paid'), 2) }}</span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Transaction Ledger</h5>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-pill px-3 border" data-bs-toggle="dropdown">
                        <i class="bi bi-filter me-1"></i> Period: All Time
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr class="tiny text-muted">
                                <th class="ps-4">INSTITUTION</th>
                                <th>SUBSCRIPTION TIER</th>
                                <th>ACCOUNTING</th>
                                <th>TEMPORAL SPAN</th>
                                <th>STATUS</th>
                                <th class="text-end pe-4">DOCUMENTATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $sub)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3"
                                                style="width: 40px; height: 40px;">
                                                {{ substr($sub->school->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $sub->school->name }}</h6>
                                                <small class="text-muted">{{ $sub->school->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-indigo bg-opacity-10 text-indigo border border-indigo border-opacity-10 px-3 py-1 rounded-pill small">
                                            {{ $sub->plan->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">₹{{ number_format($sub->amount_paid, 2) }}</div>
                                        <div class="tiny text-muted text-uppercase">{{ $sub->payment_method ?? 'Manual' }}</div>
                                    </td>
                                    <td>
                                        <div class="small text-dark fw-medium">{{ $sub->start_date->format('d M, Y') }}</div>
                                        <div class="small text-{{ $sub->isExpired() ? 'danger' : 'muted' }} fw-bold">
                                            → {{ $sub->end_date->format('d M, Y') }}
                                            @if($sub->isExpired()) [EXPIRED] @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $sub->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $sub->status === 'active' ? 'success' : 'secondary' }} px-3 py-1 rounded-pill">
                                            {{ ucfirst($sub->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($sub->invoice_number)
                                            <a href="{{ route('admin.subscriptions.download', $sub) }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold"
                                                title="Generate Invoice">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> INV
                                            </a>
                                        @else
                                            <span class="text-muted small fst-italic">No Invoice</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-credit-card-2-front d-block"
                                                style="font-size: 4rem;"></i></div>
                                        <h5 class="text-muted">No Subscription History</h5>
                                        <p class="text-muted small">Subscription records will materialize here upon activation.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($subscriptions->hasPages())
                <div class="card-footer bg-white border-top-0 py-4">
                    {{ $subscriptions->links() }}
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
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bg-indigo {
            background-color: #4f46e5;
        }

        .text-indigo {
            color: #4f46e5;
        }

        .border-indigo {
            border-color: #4f46e5;
        }
    </style>
@endsection