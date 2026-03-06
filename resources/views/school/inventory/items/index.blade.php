@extends('layouts.app')

@section('title', 'Kit & Stock Management')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Inventory & Stock</h3>
                <p class="text-muted small mb-0">Manage kit stocks, pricing, and distribution for students.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('school.inventory.items.create') }}"
                    class="btn btn-primary rounded-pill px-4 shadow-sm border-0 d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i> Add New Item
                </a>
                <a href="{{ route('school.inventory.sales.index') }}"
                    class="btn btn-outline-primary rounded-pill px-4 shadow-sm d-flex align-items-center">
                    <i class="bi bi-cart-check me-2"></i> View Sales
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase fw-bold">
                                <th class="ps-4 py-3 border-0">Item Name</th>
                                <th class="py-3 border-0">Category</th>
                                <th class="py-3 border-0">Price</th>
                                <th class="py-3 border-0">Stock Level</th>
                                <th class="py-3 border-0">Differentiation</th>
                                <th class="py-3 border-0 text-center">Status</th>
                                <th class="pe-4 py-3 border-0 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr class="hover-lift transition-all">
                                    <td class="ps-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-3">
                                                <i class="bi bi-box-seam-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $item->name }}</div>
                                                <small class="text-muted tiny">ID:
                                                    #SKU-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <span class="badge bg-soft-info px-3 py-2 rounded-pill small">
                                            {{ $item->category ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="border-0">
                                        <div class="fw-bold text-dark">₹{{ number_format($item->price, 2) }}</div>
                                    </td>
                                    <td class="border-0">
                                        @php
                                            $stockPercent = $item->stock_quantity > 0 ? min(100, ($item->stock_quantity / 50) * 100) : 0;
                                            $stockColor = $item->stock_quantity <= $item->alert_quantity ? 'danger' : 'success';
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 fw-bold text-{{ $stockColor }}">{{ $item->stock_quantity }} units
                                            </div>
                                            @if($item->stock_quantity <= $item->alert_quantity)
                                                <span class="badge bg-danger p-1 rounded-circle" title="Low Stock"><i
                                                        class="bi bi-exclamation-triangle-fill tiny"></i></span>
                                            @endif
                                        </div>
                                        <div class="progress rounded-pill mt-1" style="height: 4px; width: 100px;">
                                            <div class="progress-bar bg-{{ $stockColor }}" style="width: {{ $stockPercent }}%">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        @if($item->course)
                                            <span class="badge bg-light text-dark border tiny">{{ $item->course->name }}</span>
                                        @endif
                                        @if($item->level)
                                            <span class="badge bg-light text-dark border tiny">{{ $item->level->name }}</span>
                                        @endif
                                        @if(!$item->course && !$item->level)
                                            <small class="text-muted">Universal</small>
                                        @endif
                                    </td>
                                    <td class="border-0 text-center">
                                        <span class="badge bg-{{ $item->status ? 'success' : 'secondary' }} rounded-pill">
                                            {{ $item->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="pe-4 border-0 text-end">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('school.inventory.sales.create', ['item_id' => $item->id]) }}"
                                                class="btn btn-sm btn-white border-0" title="Sell Item">
                                                <i class="bi bi-cart-plus-fill text-success"></i>
                                            </a>
                                            <a href="{{ route('school.inventory.items.edit', $item) }}"
                                                class="btn btn-sm btn-white border-0" title="Edit Item">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-white border-0"
                                                onclick="if(confirm('Delete this item?')) document.getElementById('delete-form-{{ $item->id }}').submit();"
                                                title="Delete Item">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('school.inventory.items.destroy', $item) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-box-seam" style="font-size: 4rem;"></i>
                                        </div>
                                        <h5 class="text-muted">No inventory items found.</h5>
                                        <a href="{{ route('school.inventory.items.create') }}"
                                            class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Add Your First Item</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .text-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-white {
            background-color: #fff;
        }

        .btn-white:hover {
            background-color: #f8f9fa;
        }

        .tiny {
            font-size: 0.75rem;
        }
    </style>
@endsection