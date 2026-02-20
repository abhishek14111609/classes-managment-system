@extends('layouts.app')

@section('title', 'Subscription Architecture')
@section('hide_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Strategic Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 fade-in">
            <div>
                <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Subscription Architecture</h2>
                <p class="text-muted mb-0">Define and manage commercial tiers for institutional access.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('admin.plans.create') }}" class="btn btn-primary shadow-sm px-4">
                    <i class="bi bi-plus-lg me-2"></i> Construct New Plan
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse($plans as $plan)
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 transition-all hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-4 text-primary">
                                    <i class="bi bi-rocket-takeoff-fill fs-3"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3">
                                        <li><a class="dropdown-item" href="{{ route('admin.plans.edit', $plan) }}"><i
                                                    class="bi bi-pencil me-2"></i> Edit Plan</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST"
                                                onsubmit="return confirm('Are you sure? This may affect existing subscriptions.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i
                                                        class="bi bi-trash me-2"></i> Decommission</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="fw-bold text-dark mb-1">{{ $plan->name }}</h4>
                                <p class="text-muted small mb-0">{{ Str::limit($plan->description, 100) }}</p>
                            </div>

                            <div class="d-flex align-items-end gap-1 mb-4">
                                <h2 class="fw-bold mb-0">₹{{ number_format($plan->price, 0) }}</h2>
                                <span class="text-muted small mb-1">/ {{ $plan->duration_days }} Days</span>
                            </div>

                            <div class="space-y-3">
                                <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people-fill text-primary me-2"></i>
                                        <span class="small fw-semibold">Student Quota</span>
                                    </div>
                                    <span class="badge bg-white text-dark border">{{ $plan->student_limit }} Limit</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3 mt-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-briefcase-fill text-info me-2"></i>
                                        <span class="small fw-semibold">Faculty Nodes</span>
                                    </div>
                                    <span class="badge bg-white text-dark border">{{ $plan->batch_limit }} Slots</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 p-4 pt-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <span
                                    class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $plan->is_active ? 'success' : 'secondary' }} rounded-pill px-3 py-2">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                    {{ $plan->is_active ? 'Market Active' : 'Internal Preview' }}
                                </span>
                                <a href="{{ route('admin.plans.edit', $plan) }}"
                                    class="btn btn-sm btn-link text-decoration-none fw-bold">Modify Specifications</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                        <i class="bi bi-tags-fill display-1 text-muted opacity-25"></i>
                        <h4 class="text-muted mt-3">No Plans Architected</h4>
                        <p class="text-muted small">Begin by constructing your first commercial tier.</p>
                        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary mt-3">Construct Plan</a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($plans->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $plans->links() }}
            </div>
        @endif
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
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
        }

        .space-y-3>*+* {
            margin-top: 0.75rem;
        }
    </style>
@endsection