@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Add New Athlete' : 'Add New Student')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ auth()->user()->school->institute_type === 'sport' ? 'Add New Athlete' : 'Add New Student' }}
        </h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('school.students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h5 class="mb-3">Personal Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Athlete Name' : 'Student Name' }}
                                *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username *</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">
                        {{ auth()->user()->school->institute_type === 'sport' ? 'Registration Information' : 'Academic Information' }}
                    </h5>

                    <div class="row align-items-center mb-4">
                        <div class="col-md-6 text-primary">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-trophy-fill me-2"></i>Sport Enrollments & Fees</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm fw-bold"
                                onclick="addEnrollmentRow()">
                                <i class="bi bi-plus-circle-fill me-1"></i> Enroll in Another Sport
                            </button>
                        </div>
                    </div>

                    <div id="enrollment_rows_container">
                        <!-- Dynamic rows injected here -->
                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Joining Date *</label>
                            <input type="date" name="admission_date" class="form-control rounded-3"
                                value="{{ old('admission_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Registration ID / Jersey Number</label>
                            <input type="text" name="roll_number" class="form-control rounded-3"
                                value="{{ old('roll_number') }}" placeholder="Auto-generated">
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Birth Date</label>
                            <input type="date" name="birth_date"
                                class="form-control @error('birth_date') is-invalid @enderror"
                                value="{{ old('birth_date') }}">
                            @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="previous_school"
                                class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Current School/Institute' : 'Previous School' }}</label>
                            <input type="text" name="previous_school"
                                class="form-control @error('previous_school') is-invalid @enderror"
                                value="{{ old('previous_school') }}">
                            @error('previous_school')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Parent Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent Name</label>
                            <input type="text" name="parent_name"
                                class="form-control @error('parent_name') is-invalid @enderror"
                                value="{{ old('parent_name') }}">
                            @error('parent_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent Phone</label>
                            <input type="text" name="parent_phone"
                                class="form-control @error('parent_phone') is-invalid @enderror"
                                value="{{ old('parent_phone') }}">
                            @error('parent_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                            rows="3">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label
                            class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? 'Athlete Photo' : 'Student Photo' }}</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror"
                            accept="image/*">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit"
                            class="btn btn-primary">{{ auth()->user()->school->institute_type === 'sport' ? 'Register Athlete' : 'Create Student' }}</button>
                        <a href="{{ route('school.students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const allCourses = @json($courses);
        const allBatches = @json($batches);
        const allFeePlans = @json($feePlans);
        let rowCount = 0;

        function addEnrollmentRow() {
            const container = document.getElementById('enrollment_rows_container');
            const rowId = `row_${rowCount++}`;

            const rowHtml = `
                <div class="card border-0 shadow-sm rounded-4 mb-3 p-3 enrollment-card animate-fade-in" id="${rowId}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">1. Select Course</label>
                            <select onchange="populateBatches('${rowId}', this.value)" class="form-select rounded-pill bg-light border-0 shadow-none px-3">
                                <option value="">— Select Course —</option>
                                ${allCourses.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">2. Select Sport/Session</label>
                            <select name="batch_ids[]" class="form-select rounded-pill bg-light border-0 shadow-none px-3 batch-select" data-row="${rowId}">
                                <option value="">— Select Batch —</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">3. Assign Fees (Collect one or more)</label>
                            <div class="fee-checkboxes d-flex flex-wrap gap-2 border rounded-4 p-2 bg-white" style="min-height: 40px; max-height: 120px; overflow-y: auto;">
                                <!-- Checkboxes injected here -->
                                <small class="text-muted fst-italic w-100 text-center py-1">Select a sport first...</small>
                            </div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-soft-danger rounded-circle p-2" onclick="removeRow('${rowId}')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', rowHtml);
        }

        function populateBatches(rowId, courseId) {
            const row = document.getElementById(rowId);
            const batchSelect = row.querySelector('.batch-select');
            const filteredBatches = allBatches.filter(b => b.class && b.class.course_id == courseId);

            batchSelect.innerHTML = '<option value="">— Select Batch —</option>';
            filteredBatches.forEach(b => {
                batchSelect.innerHTML += `<option value="${b.id}">${b.name}</option>`;
            });

            // Add listener for batch change to show fees
            batchSelect.onchange = (e) => populateFees(rowId, e.target.value);
        }

        function populateFees(rowId, batchId) {
        const row = document.getElementById(rowId);
        const feeContainer = row.querySelector('.fee-checkboxes');
        
        if (!batchId) {
            feeContainer.innerHTML = '<small class="text-muted fst-italic w-100 text-center py-1">Select a sport first...</small>';
            return;
        }

        const selectedBatch = allBatches.find(b => b.id == batchId);
        const courseId = selectedBatch ? selectedBatch.class.course_id : null;

        // Filter Fee Plans that match either:
        // 1. THIS specific batch
        // 2. THIS course (but no specific batch)
        // 3. Generic plans (no course/batch)
        const relevantPlans = allFeePlans.filter(p => {
            if (p.batch_id == batchId) return true;
            if (p.course_id == courseId && !p.batch_id) return true;
            if (!p.course_id && !p.batch_id) return true;
            return false;
        });

        if (relevantPlans.length === 0) {
            feeContainer.innerHTML = '<small class="text-danger small w-100 text-center py-1">No fee plans found for this sport.</small>';
            return;
        }

        feeContainer.innerHTML = relevantPlans.map(p => `
            <div class="form-check form-check-inline m-0">
                <input class="form-check-input" type="checkbox" name="batch_fees[${batchId}][]" value="${p.id}" id="fee_${rowId}_${p.id}">
                <label class="form-check-label small" for="fee_${rowId}_${p.id}">${p.name} (₹${p.amount})</label>
            </div>
        `).join('');
    }

        function removeRow(rowId) {
            const row = document.getElementById(rowId);
            row.classList.add('animate-fade-out');
            setTimeout(() => row.remove(), 300);
        }

        document.addEventListener('DOMContentLoaded', function () {
            addEnrollmentRow(); // Add first row by default
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(10px);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-fade-out {
            animation: fadeOut 0.3s ease-out;
        }

        .btn-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: none;
        }

        .btn-soft-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .enrollment-card {
            border-left: 4px solid #0d6efd !important;
        }
    </style>
@endsection