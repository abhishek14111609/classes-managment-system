@extends('layouts.app')

@section('title', 'Levels Management')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2 border-bottom">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Levels Registry</h3>
                <p class="text-muted small mb-0">Manage training/academic levels across the institution.</p>
            </div>
            <a href="{{ route('school.levels.create') }}"
                class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold small grow">
                <i class="bi bi-plus-lg me-2"></i> Add Level
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase tiny fw-bold text-muted ps-4 py-3">Level Name</th>
                                <th class="text-uppercase tiny fw-bold text-muted py-3">Description</th>
                                <th class="text-uppercase tiny fw-bold text-muted py-3 text-center">Status</th>
                                <th class="text-uppercase tiny fw-bold text-muted pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($levels as $level)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 rounded-circle text-info d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-bar-chart-steps fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $level->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted small">
                                        {{ Str::limit($level->description, 50) ?? 'N/A' }}
                                    </td>
                                    <td class="py-3 text-center">
                                        @if ($level->is_active)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1 fw-bold tiny">Active</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3 py-1 fw-bold tiny">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-circle shadow-none border" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                <li>
                                                    <a class="dropdown-item fw-medium small py-2" href="{{ route('school.levels.edit', $level) }}">
                                                        <i class="bi bi-pencil-square text-warning me-2"></i> Edit Level
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('school.levels.destroy', $level) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirm permanently deleting this level?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item fw-medium small py-2 text-danger">
                                                            <i class="bi bi-trash3 text-danger me-2"></i> Delete Level
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inboxes mb-3 fs-1 d-block text-secondary opacity-50"></i>
                                            <h6 class="fw-bold mb-1">No Levels Configured</h6>
                                            <p class="small mb-3">Add categories like Basic, Advanced to categorize batches.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($levels->hasPages())
                <div class="card-footer bg-white border-top p-3">
                    {{ $levels->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .text-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .tiny {
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }
        .grow:hover {
            transform: scale(1.05);
            transition: all 0.2s;
        }
        .table > :not(caption) > * > * {
            border-bottom-color: rgba(0,0,0,0.05);
        }
        .dropdown-item:hover {
            background-color: rgba(0,0,0,0.03);
            border-radius: 4px;
        }
    </style>
@endsection
