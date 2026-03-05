@extends('layouts.app')

@php
    $isSport = auth()->user()->school && auth()->user()->school->institute_type === 'sport';
@endphp

@section('title', $isSport ? 'Edit Athlete' : 'Edit Student')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            @if($isSport) Edit Athlete: @else Edit Student: @endif
            {{ $student->user->name }}
        </h2>
        <a href="{{ route('school.students.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('school.students.update', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="mb-3">Personal Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $student->user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $student->user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $student->user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   id="username" name="username" value="{{ old('username', $student->user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

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
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="admission_date" class="form-label">Admission Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('admission_date') is-invalid @enderror"
                                   id="admission_date" name="admission_date" value="{{ old('admission_date', $student->admission_date->format('Y-m-d')) }}" required>
                            @error('admission_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="roll_number" class="form-label">Roll Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('roll_number') is-invalid @enderror"
                                   id="roll_number" name="roll_number" value="{{ old('roll_number', $student->roll_number) }}" required>
                            @error('roll_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Birth Date</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                   id="birth_date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Parent Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="parent_name" class="form-label">Parent Name</label>
                            <input type="text" class="form-control @error('parent_name') is-invalid @enderror"
                                   id="parent_name" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}">
                            @error('parent_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="parent_phone" class="form-label">Parent Phone</label>
                            <input type="text" class="form-control @error('parent_phone') is-invalid @enderror"
                                   id="parent_phone" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}">
                            @error('parent_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                              id="address" name="address" rows="2">{{ old('address', $student->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">
                        @if($isSport) Athlete Photo @else Student Photo @endif
                    </label>
                    @if($student->photo)
                        <div class="mb-2">
                            <img src="{{ Storage::url($student->photo) }}" alt="{{ $student->user->name }}" width="100" class="rounded">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                           id="photo" name="photo" accept="image/*">
                    <small class="text-muted">Leave empty to keep current photo</small>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $student->user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">
                        <i class="bi bi-check-circle-fill me-2"></i> Update Athlete Profile
                    </button>
                    <a href="{{ route('school.students.index') }}" class="btn btn-light px-4 rounded-pill border">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const allCourses = @json($courses);
        const allBatches = @json($batches);
        const allFeePlans = @json($feePlans);
        let rowCount = 0;

        function addEnrollmentRow(preSelectedBatchId = null) {
            const container = document.getElementById('enrollment_rows_container');
            const rowId = `row_${rowCount++}`;

            let preSelectedCourseId = "";
            if (preSelectedBatchId) {
                const batch = allBatches.find(b => b.id == preSelectedBatchId);
                if (batch && batch.class) preSelectedCourseId = batch.class.course_id;
            }

            const rowHtml = `
                <div class="card border-0 shadow-sm rounded-4 mb-3 p-3 enrollment-card animate-fade-in" id="${rowId}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">1. Course</label>
                            <select onchange="populateBatches('${rowId}', this.value)" class="form-select rounded-pill bg-light border-0 shadow-none px-3">
                                <option value="">— Select Course —</option>
                                ${allCourses.map(c => `<option value="${c.id}" ${c.id == preSelectedCourseId ? 'selected' : ''}>${c.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">2. Sport/Session</label>
                            <select name="batch_ids[]" class="form-select rounded-pill bg-light border-0 shadow-none px-3 batch-select" data-row="${rowId}">
                                <option value="">— Select Batch —</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">3. Assign Fees (Optional for existing)</label>
                            <div class="fee-checkboxes d-flex flex-wrap gap-2 border rounded-4 p-2 bg-white" style="min-height: 48px; max-height: 120px; overflow-y: auto;">
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
            
            if (preSelectedCourseId) {
                populateBatches(rowId, preSelectedCourseId, preSelectedBatchId);
            }
        }

        function populateBatches(rowId, courseId, preSelectedBatchId = null) {
            const row = document.getElementById(rowId);
            const batchSelect = row.querySelector('.batch-select');
            const filteredBatches = allBatches.filter(b => b.class && b.class.course_id == courseId);

            batchSelect.innerHTML = '<option value="">— Select Batch —</option>';
            filteredBatches.forEach(b => {
                batchSelect.innerHTML += `<option value="${b.id}" ${b.id == preSelectedBatchId ? 'selected' : ''}>${b.name}</option>`;
            });

            batchSelect.onchange = (e) => populateFees(rowId, e.target.value);
            
            if (preSelectedBatchId) {
                populateFees(rowId, preSelectedBatchId);
            }
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

            const relevantPlans = allFeePlans.filter(p => {
                if (p.batch_id == batchId) return true;
                if (p.course_id == courseId && !p.batch_id) return true;
                if (!p.course_id && !p.batch_id) return true;
                return false;
            });

            if (relevantPlans.length === 0) {
                feeContainer.innerHTML = '<small class="text-muted small w-100 text-center py-1">No additional fee plans available.</small>';
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
            const container = document.getElementById('enrollment_rows_container');
            if (container.children.length > 1 || confirm('Remove this athlete from this sport?')) {
                const row = document.getElementById(rowId);
                row.classList.add('animate-fade-out');
                setTimeout(() => row.remove(), 300);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const currentBatches = @json($student->batches->pluck('id'));
            if (currentBatches.length > 0) {
                currentBatches.forEach(bid => addEnrollmentRow(bid));
            } else {
                addEnrollmentRow();
            }
        });
    </script>

    <style>
        .enrollment-card { border-left: 4px solid #0d6efd !important; transition: all 0.3s ease; }
        .enrollment-card:hover { transform: translateX(5px); }
        .btn-soft-danger { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; border: none; }
        .btn-soft-danger:hover { background-color: #dc3545; color: white; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeOut { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(10px); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .animate-fade-out { animation: fadeOut 0.3s ease-out; }
    </style>
</div>
@endsection
