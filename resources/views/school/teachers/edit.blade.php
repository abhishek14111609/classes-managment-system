@extends('layouts.app')

@section('title', 'Edit Teacher')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Teacher: {{ $teacher->user->name }}</h2>
        <a href="{{ route('school.teachers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('school.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <h5 class="mb-3">Personal Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $teacher->user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $teacher->user->email) }}" required>
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
                                   id="phone" name="phone" value="{{ old('phone', $teacher->user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            <small class="text-muted">Leave blank to keep current password</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Professional Information</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee ID</label>
                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                   id="employee_id" name="employee_id" value="{{ old('employee_id', $teacher->employee_id) }}">
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="joining_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('joining_date') is-invalid @enderror"
                                   id="joining_date" name="joining_date" value="{{ old('joining_date', $teacher->joining_date->format('Y-m-d')) }}" required>
                            @error('joining_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control @error('salary') is-invalid @enderror"
                                   id="salary" name="salary" value="{{ old('salary', $teacher->salary) }}" step="0.01">
                            @error('salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="qualification" class="form-label">Qualification</label>
                            <input type="text" class="form-control @error('qualification') is-invalid @enderror"
                                   id="qualification" name="qualification" value="{{ old('qualification', $teacher->qualification) }}">
                            @error('qualification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control @error('specialization') is-invalid @enderror"
                                   id="specialization" name="specialization" value="{{ old('specialization', $teacher->specialization) }}">
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">Profile Photo</label>
                    <div class="mb-2">
                        @if($teacher->user->avatar)
                            <img src="{{ asset('storage/' . $teacher->user->avatar) }}" alt="Avatar" class="img-thumbnail" width="100">
                        @endif
                    </div>
                    <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                           id="avatar" name="avatar" accept="image/*">
                    @error('avatar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="batches" class="form-label">Assign Batches</label>
                    <select class="form-select @error('batches') is-invalid @enderror" id="batches" name="batches[]" multiple size="5">
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ in_array($batch->id, old('batches', $teacher->batches->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $batch->name }} ({{ $batch->class->name }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple batches</small>
                    @error('batches')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $teacher->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Teacher
                    </button>
                    <a href="{{ route('school.teachers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
