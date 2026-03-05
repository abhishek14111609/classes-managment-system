@extends('layouts.app')

@section('title', 'Edit Fee')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Fee</h2>
            <a href="{{ route('school.fees.show', $fee) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Fee Details
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Student Info (read-only) --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                        style="width:48px;height:48px;font-size:18px;font-weight:700;">
                        {{ strtoupper(substr($fee->student->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $fee->student->user->name }}</h6>
                        <small class="text-muted">Roll No: {{ $fee->student->roll_number }}
                            &nbsp;|&nbsp; Batch: {{ $fee->student->batch->name ?? 'N/A' }}
                        </small>
                    </div>
                    <span class="ms-auto badge
                                @if($fee->status === 'paid') bg-success
                                @elseif($fee->status === 'partial') bg-warning text-dark
                                @elseif($fee->status === 'overdue') bg-danger
                                @else bg-secondary @endif
                                fs-6">{{ ucfirst($fee->status) }}</span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('school.fees.update', $fee) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Fee Type (read-only after creation) --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Fee Type</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ ucfirst(str_replace('_', '-', $fee->fee_type)) }}" readonly>
                            <div class="form-text text-muted">Fee type cannot be changed after creation.</div>
                        </div>

                        {{-- Session / Batch selection --}}
                        <div class="col-md-4 mb-3">
                            <label for="batch_id" class="form-label fw-semibold">Link to Session</label>
                            <select class="form-select @error('batch_id') is-invalid @enderror" id="batch_id"
                                name="batch_id">
                                <option value="">— General / No Specific Session —</option>
                                @foreach($fee->student->batches as $batch)
                                    <option value="{{ $batch->id }}" {{ old('batch_id', $fee->batch_id) == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small">Session this fee is associated with.</div>
                            @error('batch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Sport Level --}}
                        <div class="col-md-4 mb-3">
                            <label for="sport_level" class="form-label fw-semibold">Sports Level</label>
                            <select class="form-select @error('sport_level') is-invalid @enderror" id="sport_level"
                                name="sport_level">
                                <option value="">— Not applicable / General fee —</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->name }}" {{ old('sport_level', $fee->sport_level) === $level->name ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sport_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Due Date --}}
                        <div class="col-md-4 mb-3">
                            <label for="due_date" class="form-label fw-semibold">Due Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date"
                                name="due_date" value="{{ old('due_date', $fee->due_date->format('Y-m-d')) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Total Amount --}}
                        <div class="col-md-4 mb-3">
                            <label for="total_amount" class="form-label fw-semibold">Total Amount (₹) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('total_amount') is-invalid @enderror"
                                id="total_amount" name="total_amount" value="{{ old('total_amount', $fee->total_amount) }}"
                                step="0.01" min="0" required>
                            @error('total_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Discount --}}
                        <div class="col-md-4 mb-3">
                            <label for="discount" class="form-label fw-semibold">Discount (₹)</label>
                            <input type="number" class="form-control @error('discount') is-invalid @enderror" id="discount"
                                name="discount" value="{{ old('discount', $fee->discount) }}" step="0.01" min="0">
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Late Fee --}}
                        <div class="col-md-4 mb-3">
                            <label for="late_fee" class="form-label fw-semibold">Late Fee (₹)</label>
                            <input type="number" class="form-control @error('late_fee') is-invalid @enderror" id="late_fee"
                                name="late_fee" value="{{ old('late_fee', $fee->late_fee) }}" step="0.01" min="0">
                            @error('late_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Net Payable Preview --}}
                    <div class="alert alert-info d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>Net Payable Preview:</strong>
                            <span class="text-muted ms-2">(Total − Discount + Late Fee)</span>
                        </div>
                        <strong class="fs-5 text-primary" id="net_preview">
                            ₹{{ number_format($fee->total_amount + $fee->late_fee - $fee->discount, 2) }}
                        </strong>
                    </div>

                    {{-- Already paid warning --}}
                    @if($fee->paid_amount > 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Note:</strong> This fee already has ₹{{ number_format($fee->paid_amount, 2) }} paid.
                            Changing the total amount will recalculate the remaining balance.
                        </div>
                    @endif

                    {{-- Remarks --}}
                    <div class="mb-3">
                        <label for="remarks" class="form-label fw-semibold">Remarks / Notes</label>
                        <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks"
                            rows="3">{{ old('remarks', $fee->remarks) }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Fee
                        </button>
                        <a href="{{ route('school.fees.show', $fee) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function updatePreview() {
                const total = parseFloat(document.getElementById('total_amount').value) || 0;
                const discount = parseFloat(document.getElementById('discount').value) || 0;
                const lateFee = parseFloat(document.getElementById('late_fee').value) || 0;
                const net = Math.max(0, total - discount + lateFee);
                document.getElementById('net_preview').textContent =
                    '₹' + net.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
            ['total_amount', 'discount', 'late_fee'].forEach(id => {
                document.getElementById(id)?.addEventListener('input', updatePreview);
            });
        </script>
    @endpush
@endsection