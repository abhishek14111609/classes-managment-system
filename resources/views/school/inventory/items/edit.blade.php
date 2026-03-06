@extends('layouts.app')

@section('title', 'Edit Inventory Item')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Edit Item</h3>
                <p class="text-muted small mb-0">Update kit details, pricing, or adjust current stock.</p>
            </div>
            <a href="{{ route('school.inventory.items.index') }}"
                class="btn btn-white rounded-pill px-4 shadow-sm border d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Back to List
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('school.inventory.items.update', $item) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Item Name -->
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Item Name / Kit
                                        Name</label>
                                    <input type="text" name="name"
                                        class="form-control rounded-pill px-4 py-2 border-light bg-light"
                                        value="{{ $item->name }}" required>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Category</label>
                                    <input type="text" name="category"
                                        class="form-control rounded-pill px-4 py-2 border-light bg-light"
                                        value="{{ $item->category }}">
                                </div>

                                <!-- Price -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Selling Price
                                        (₹)</label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text rounded-start-pill border-light bg-light px-3">₹</span>
                                        <input type="number" step="0.01" name="price"
                                            class="form-control rounded-end-pill px-4 py-2 border-light bg-light"
                                            value="{{ $item->price }}" required>
                                    </div>
                                </div>

                                <!-- Stock Qty -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Current Stock
                                        Quantity</label>
                                    <input type="number" name="stock_quantity"
                                        class="form-control rounded-pill px-4 py-2 border-light bg-light"
                                        value="{{ $item->stock_quantity }}" required>
                                </div>

                                <!-- Alert Qty -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Low Stock Alert
                                        Quantity</label>
                                    <input type="number" name="alert_quantity"
                                        class="form-control rounded-pill px-4 py-2 border-light bg-light"
                                        value="{{ $item->alert_quantity }}" required>
                                </div>

                                <hr class="my-4 opacity-10">
                                <h6 class="fw-bold mb-3">Differentiation (Optional)</h6>

                                <!-- Course -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Target
                                        Sport/Course</label>
                                    <select name="course_id"
                                        class="form-select rounded-pill px-4 py-2 border-light bg-light">
                                        <option value="">Applied to All</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ $item->course_id == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Level -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Target
                                        Level</label>
                                    <select name="level_id"
                                        class="form-select rounded-pill px-4 py-2 border-light bg-light">
                                        <option value="">Applied to All</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}" {{ $item->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="col-md-12 mt-4">
                                    <div class="form-check form-switch p-0 d-flex align-items-center">
                                        <input class="form-check-input ms-0 me-3" type="checkbox" name="status" value="1" {{ $item->status ? 'checked' : '' }} id="statusSwitch"
                                            style="width: 2.5rem; height: 1.25rem;">
                                        <label class="form-check-label fw-bold text-muted fw-bold small text-uppercase"
                                            for="statusSwitch">Item is Active and for sale</label>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-5">
                                    <button type="submit"
                                        class="btn btn-primary rounded-pill px-5 py-3 border-0 shadow-sm w-100 fw-bold">
                                        Update Inventory Item
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-light {
            background-color: #f8fafc !important;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #fff !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }
    </style>
@endsection