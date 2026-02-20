@extends('layouts.app')

@section('title', 'Edit Course: ' . $course->name)

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Course') }}: {{ $course->name }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('school.courses.update', $course) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Course Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name', $course->name) }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Course Code') }}</label>
                            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                                name="code" value="{{ old('code', $course->code) }}">
                            @error('code')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                name="description" rows="3">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('school.courses.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">{{ __('Update Course') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection