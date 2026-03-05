@extends('layouts.app')

@section('title', 'Institutional Billing Framework')
@section('sidebar') @include('school.sidebar') @endsection

@php
    $categoryMap = [
        'tuition' => ['label' => 'Academic Tuition', 'icon' => 'bi-journal-check', 'color' => 'bg-primary'],
        'sports' => ['label' => 'Athletics & Sports', 'icon' => 'bi-trophy-fill', 'color' => 'bg-success'],
        'transport' => ['label' => 'Logistics & Transit', 'icon' => 'bi-bus-front-fill', 'color' => 'bg-warning text-dark'],
        'exam' => ['label' => 'Institutional Exams', 'icon' => 'bi-file-earmark-text-fill', 'color' => 'bg-danger'],
        'library' => ['label' => 'Resource Access', 'icon' => 'bi-book-fill', 'color' => 'bg-info text-dark'],
        'other' => ['label' => 'General Ancillary', 'icon' => 'bi-tags-fill', 'color' => 'bg-secondary'],
    ];
    $durationMap = [
        'monthly' => ['label' => 'Cycle: Monthly', 'color' => 'bg-light text-primary border'],
        'quarterly' => ['label' => 'Cycle: Quarterly', 'color' => 'bg-light text-purple border'],
        'half_yearly' => ['label' => 'Cycle: Semi-Annual', 'color' => 'bg-light text-indigo border'],
        'annual' => ['label' => 'Cycle: Annual Term', 'color' => 'bg-light text-dark border'],
        'one_time' => ['label' => 'Cycle: Single Ledger', 'color' => 'bg-light text-secondary border'],
    ];
@endphp

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
        <div>
            <h3 class="fw-bold mb-1 text-gradient">Fee Templates</h3>
            <p class="text-muted small mb-0">Standardize your institutional billing logic and revenue streams.</p>
        </div>
        <a href="{{ route('school.fee-plans.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
            <i class="bi bi-shield-plus me-2"></i> Construct Fee Plan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($plans->isEmpty())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <div class="opacity-25 mb-3"><i class="bi bi-wallet-fill" style="font-size: 5rem;"></i></div>
                <h5 class="text-muted fw-bold">No Billing Artifacts Identified</h5>
                <p class="text-muted small mb-4">
                    Establish standardized billing templates like "Term Tuition" or "Sports Membership".<br>
                    Automate student ledger entries with pre-defined financial cycles.
                </p>
                <a href="{{ route('school.fee-plans.create') }}" class="btn btn-primary rounded-pill px-5 shadow-sm">
                    Initialize Billing System
                </a>
            </div>
        </div>
    @else
        <div class="row g-4 mb-4">
            @foreach($plans as $plan)
                @php
                    $cat = $categoryMap[$plan->fee_type] ?? ['label' => ucfirst($plan->fee_type), 'icon' => 'bi-tag-fill', 'color' => 'bg-secondary'];
                    $dur = $plan->duration ? ($durationMap[$plan->duration] ?? ['label' => ucfirst($plan->duration), 'color' => 'bg-light text-secondary border']) : null;
                @endphp
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all overflow-hidden {{ !$plan->is_active ? 'opacity-75 grayscale' : '' }}">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-4 text-primary">
                                    <i class="bi {{ $cat['icon'] }} fs-4"></i>
                                </div>
                                <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-1 tiny fw-bold shadow-sm">
                                    {{ $plan->is_active ? 'ACTIVE PLAN' : 'INACTIVE' }}
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">{{ $plan->name }}</h5>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge {{ $cat['color'] }} rounded-pill px-3 py-1 tiny fw-bold">{{ $cat['label'] }}</span>
                                @if($dur)
                                    <span class="badge {{ $dur['color'] }} rounded-pill px-3 py-1 tiny fw-bold">{{ $dur['label'] }}</span>
                                @endif
                                @if($plan->sport_level)
                                    <span class="badge bg-purple-soft text-purple rounded-pill px-3 py-1 tiny fw-bold shadow-none">
                                        <i class="bi bi-award me-1"></i> {{ ucfirst($plan->sport_level) }} Tier
                                    </span>
                                @endif
                                @if($plan->course)
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1 tiny fw-bold shadow-none">
                                        <i class="bi bi-book-half me-1"></i> {{ $plan->course->name }}
                                    </span>
                                @endif
                                @if($plan->batch)
                                    <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1 tiny fw-bold shadow-none">
                                        <i class="bi bi-collection-fill me-1"></i> {{ $plan->batch->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <div class="d-flex align-items-baseline gap-1">
                                    <span class="h2 fw-bold text-primary mb-0">₹{{ number_format($plan->amount, 0) }}</span>
                                    <span class="text-muted small fw-bold">/ Cycle</span>
                                </div>
                                @if($plan->late_fee_per_day > 0)
                                    <div class="mt-1 d-flex align-items-center text-danger tiny fw-bold">
                                        <i class="bi bi-clock-history me-1"></i> Late Penalty: ₹{{ number_format($plan->late_fee_per_day, 0) }} / day
                                    </div>
                                @endif
                            </div>

                            @if($plan->description)
                                <p class="text-muted tiny mb-4 line-clamp-2" style="min-height: 2.5rem;">{{ $plan->description }}</p>
                            @else
                                <div class="mb-4 text-muted tiny font-italic opacity-50" style="min-height: 2.5rem;">No institutional guidelines provided for this plan.</div>
                            @endif

                            <div class="d-flex gap-2 pt-3 border-top mt-auto">
                                <a href="{{ route('school.fees.create', ['plan' => $plan->id]) }}" class="btn btn-primary grow rounded-pill py-2 small fw-bold shadow-sm border-0">
                                    <i class="bi bi-node-plus-fill me-1"></i> Issue to Student
                                </a>
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    <a href="{{ route('school.fee-plans.edit', $plan) }}" class="btn btn-light border-0 px-3" title="Edit Template">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </a>
                                    @if($plan->fees_count > 0)
                                        <button type="button" class="btn btn-light border-0 px-3 opacity-25" disabled title="Plan in Use: {{ $plan->fees_count }} Ledger Links">
                                            <i class="bi bi-trash3 opacity-50"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('school.fee-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Archive this billing artifact?')" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light border-0 px-3" title="Decommission Plan">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $plans->links() }}
        </div>
    @endif
</div>

<style>
.text-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hover-lift:hover { transform: translateY(-3px); }
.bg-purple-soft { background-color: rgba(124, 58, 237, 0.1); color: #7c3aed; }
.grayscale { filter: grayscale(1); }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.tiny { font-size: 0.7rem; }
</style>
@endsection