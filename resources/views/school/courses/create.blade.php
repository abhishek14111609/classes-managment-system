@extends('layouts.app')

@section('title', auth()->user()->school->institute_type === 'sport' ? 'Create Sports Program' : 'Create Course')

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ auth()->user()->school->institute_type === 'sport' ? __('Create New Sports Program') : __('Create New Course') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('school.courses.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name"
                                class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? __('Program Name') : __('Course Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autofocus
                                placeholder="{{ auth()->user()->school->institute_type === 'sport' ? 'e.g. Cricket Academy' : 'e.g. High School Science Stream' }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code"
                                class="form-label">{{ auth()->user()->school->institute_type === 'sport' ? __('Program Code') : __('Course Code') }}
                                <small class="text-muted">(Optional)</small></label>
                            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                                name="code" value="{{ old('code') }}"
                                placeholder="{{ auth()->user()->school->institute_type === 'sport' ? 'e.g. CRK-PRO' : 'e.g. HS-SCI' }}">
                            @error('code')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                        </div>

                        <button type="submit"
                            class="btn btn-primary">{{ auth()->user()->school->institute_type === 'sport' ? __('Create Program') : __('Create Course') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection