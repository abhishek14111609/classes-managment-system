@extends('layouts.app')

@section('title', 'Edit Batch')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Batch</h2>
            <a href="{{ route('school.batches.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('school.batches.update', $batch) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Batch Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $batch->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                <select class="form-select @error('class_id') is-invalid @enderror" id="class_id"
                                    name="class_id" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $batch->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ ucfirst($class->type) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time <span
                                        class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                    id="start_time" name="start_time" value="{{ old('start_time', $batch->start_time) }}"
                                    required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                    id="end_time" name="end_time" value="{{ old('end_time', $batch->end_time) }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                    id="capacity" name="capacity" value="{{ old('capacity', $batch->capacity) }}" min="1"
                                    required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="teacher_ids" class="form-label">Assign Teachers</label>
                        <select class="form-select @error('teacher_ids') is-invalid @enderror" id="teacher_ids"
                            name="teacher_ids[]" multiple size="5">
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ in_array($teacher->id, old('teacher_ids', $batch->teachers->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $teacher->user->name }} ({{ $teacher->employee_id }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple teachers</small>
                        @error('teacher_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Batch
                        </button>
                        <a href="{{ route('school.batches.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection