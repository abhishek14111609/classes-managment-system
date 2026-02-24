@extends('layouts.app')

@section('title', 'Schools Management')
@section('hide_header', true)

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 fade-in">
        <div>
            <h2 class="fw-bold fs-2 mb-1 text-main font-heading">Schools Management</h2>
            <p class="text-muted mb-0">Manage all registered institutions and subscriptions.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.schools.create') }}" class="btn btn-primary shadow-sm px-4">
                <i class="bi bi-plus-lg me-2"></i> Add New School
            </a>
        </div>
    </div>

    <!-- Stats Summary (Optional, can be removed if specific to this view) -->
    <div class="row g-4 mb-4 fade-in" style="animation-delay: 0.1s;">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $schools->total() }}</h5>
                        <small class="text-muted">Total Schools</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add more summary cards here if needed -->
    </div>

    <div class="card border-0 shadow-sm fade-in" style="animation-delay: 0.2s;">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-main mb-0">Registered Schools</h5>
            <div class="input-group w-auto">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control bg-light border-start-0 ps-0" placeholder="Search schools..." style="max-width: 200px;">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">School Name</th>
                            <th>Metrics</th>
                            <th>Contact Info</th>
                            <th>Status</th>
                            <th>Subscription</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            {{ substr($school->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $school->name }}</h6>
                                            <small class="text-muted">ID: #{{ $school->id }} &bull; {{ ucfirst($school->institute_type) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark small"><i class="bi bi-mortarboard-fill me-2 text-primary"></i><strong>{{ $school->students_count }}</strong> Students</span>
                                        <span class="text-dark small mt-1"><i class="bi bi-briefcase-fill me-2 text-info"></i><strong>{{ $school->teachers_count }}</strong> Faculty</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark small"><i class="bi bi-envelope me-2 text-muted"></i>{{ $school->email }}</span>
                                        <span class="text-muted small mt-1"><i class="bi bi-telephone me-2 text-muted"></i>{{ $school->phone ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($school->status === 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle-fill me-1"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">
                                            <i class="bi bi-x-circle-fill me-1"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($school->subscription_expires_at)
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium {{ $school->subscription_expires_at->isPast() ? 'text-danger' : 'text-dark' }}">
                                                {{ $school->subscription_expires_at->format('M d, Y') }}
                                            </span>
                                            @if($school->subscription_expires_at->isPast())
                                                <small class="text-danger fw-bold">Expired</small>
                                            @else
                                                <small class="text-muted">{{ $school->subscription_expires_at->diffForHumans() }}</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">No subscription</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.schools.show', $school) }}" class="btn btn-light btn-sm text-primary hover-shadow" data-bs-toggle="tooltip" title="View Dossier">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-light btn-sm text-dark hover-shadow mx-1" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-light btn-sm text-info hover-shadow me-1" data-bs-toggle="modal" data-bs-target="#extendModal{{ $school->id }}" title="Extend Plan">
                                            <i class="bi bi-clock-history"></i>
                                        </button>
                                        @if($school->activeSubscription && $school->activeSubscription->invoice_number)
                                            <a href="{{ route('admin.subscriptions.download', $school->activeSubscription) }}" class="btn btn-light btn-sm text-success hover-shadow me-1" title="Download Invoice">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.schools.toggle-status', $school) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-light btn-sm {{ $school->status === 'active' ? 'text-warning' : 'text-success' }} hover-shadow" title="Toggle Status">
                                                <i class="bi bi-toggle-{{ $school->status === 'active' ? 'on' : 'off' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Extend Subscription Modal -->
                            <div class="modal fade" id="extendModal{{ $school->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="{{ route('admin.schools.extend-subscription', $school) }}" method="POST">
                                        @csrf
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold">Extend Subscription</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body pt-2">
                                                <p class="text-muted mb-4">Adding days to <span class="fw-bold text-primary">{{ $school->name }}</span></p>
                                                <div class="form-floating mb-3">
                                                    <input type="number" name="days" class="form-control bg-light border-0" id="floatingInput" value="30" required>
                                                    <label for="floatingInput">Days to Extend</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0">
                                                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary fw-bold px-4">Confirm Extension</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light p-3 rounded-circle mb-3">
                                            <i class="bi bi-search text-muted fs-2"></i>
                                        </div>
                                        <h5 class="text-muted">No schools found</h5>
                                        <p class="text-muted small mb-0">Try adding a new school to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($schools->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $schools->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .avatar-initial {
        font-size: 1.1rem;
        transition: all 0.2s;
    }
    .hover-shadow:hover {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(79, 70, 229, 0.02);
    }
    .fade-in {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(10px);
    }
    @keyframes fadeIn {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection