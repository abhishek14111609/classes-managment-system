@extends('layouts.app')

@section('title', 'Record Kit Sale')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 pb-2">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">Record New Sale</h3>
                <p class="text-muted small mb-0">Sell kits or equipment to students and generate an instant invoice.</p>
            </div>
            <a href="{{ route('school.inventory.sales.index') }}"
                class="btn btn-white rounded-pill px-4 shadow-sm border d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Back to Sales
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('school.inventory.sales.store') }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                <!-- Student Selection -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Select
                                        Student</label>
                                    <select name="student_id"
                                        class="form-select rounded-pill px-4 py-2 border-light bg-light" required
                                        id="studentSelect">
                                        <option value="">Search Student...</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->user->name }} (ID:
                                                {{ $student->student_id }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Item Selection -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Select
                                        Kit/Item</label>
                                    <select name="item_id" class="form-select rounded-pill px-4 py-2 border-light bg-light"
                                        required id="itemSelect">
                                        <option value="">Select Item...</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" data-price="{{ $item->price }}"
                                                data-stock="{{ $item->stock_quantity }}">
                                                {{ $item->name }} — ₹{{ number_format($item->price, 2) }}
                                                ({{ $item->stock_quantity }} in stock)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Quantity</label>
                                    <input type="number" name="quantity" id="quantityInput"
                                        class="form-control rounded-pill px-4 py-2 border-light bg-light" value="1" min="1"
                                        required>
                                </div>

                                <!-- Payment Status -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Payment
                                        Status</label>
                                    <select name="payment_status"
                                        class="form-select rounded-pill px-4 py-2 border-light bg-light">
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                        <option value="partial">Partial</option>
                                    </select>
                                </div>

                                <!-- Calculation Summary -->
                                <div class="col-md-4">
                                    <div
                                        class="p-3 bg-primary bg-opacity-10 rounded-4 h-100 d-flex flex-column justify-content-center align-items-center border border-primary border-opacity-10">
                                        <span class="text-primary small fw-bold text-uppercase mb-1">Total Amount</span>
                                        <h3 class="fw-bold mb-0 text-primary" id="totalDisplay">₹0.00</h3>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-5">
                                    <button type="submit"
                                        class="btn btn-primary rounded-pill px-5 py-3 border-0 shadow-sm w-100 fw-bold d-flex align-items-center justify-content-center">
                                        <i class="bi bi-receipt-cutoff me-2"></i> Complete Sale & Generate Invoice
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#studentSelect').select2({
                    placeholder: "Search Student...",
                    allowClear: true,
                    width: '100%'
                });

                $('#itemSelect').select2({
                    placeholder: "Select Item...",
                    allowClear: true,
                    width: '100%'
                });

                function calculateTotal() {
                    const selected = $('#itemSelect').find(':selected');
                    const price = selected.data('price') || 0;
                    const quantity = $('#quantityInput').val() || 0;
                    const total = price * quantity;
                    $('#totalDisplay').text('₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 }));
                }

                $('#itemSelect, #quantityInput').on('change input', calculateTotal);
            });
        </script>
        <style>
            .select2-container--default .select2-selection--single {
                border-radius: 50rem;
                height: 42px;
                background-color: #f8fafc;
                border-color: #f1f5f9;
                display: flex;
                align-items: center;
                padding: 0 15px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                top: 8px;
            }

            .text-gradient {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .bg-light {
                background-color: #f8fafc !important;
            }
        </style>
    @endpush
@endsection