@extends('layouts.app')

@php
    $isSport = auth()->user()->school && auth()->user()->school->institute_type === 'sport';
@endphp

@section('title', 'Class Details - ' . $class->name)

@section('sidebar')
    @include('school.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Class: {{ $class->name }}</h2>
                <span class="badge bg-{{ $class->type === 'academic' ? 'info' : 'success' }}">
                    {{ ucfirst($class->type) }}
                </span>
            </div>
            <div>
                <a href="{{ route('school.classes.edit', $class) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Class
                </a>
                <a href="{{ route('school.classes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            @foreach($class->batches as $batch)
                <div class="col-md-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Batch: {{ $batch->name }} ({{ $batch->start_time?->format('H:i') }} -
                                {{ $batch->end_time?->format('H:i') }})
                            </h5>
                            <span class="badge bg-primary">
                                {{ $batch->students->count() }}
                                @if($isSport) Athletes @else Students @endif
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Role</th>
                                            <th>Name</th>
                                            <th>Login/Email Credentials</th>
                                            <th>Status</th>
                                            <th class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Teachers for this batch --}}
                                        @foreach($batch->teachers as $teacher)
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="badge rounded-pill bg-warning text-dark">
                                                        @if($isSport) Coach @else Teacher @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $teacher->user->name }}</div>
                                                    <small class="text-muted">ID: {{ $teacher->employee_id }}</small>
                                                </td>
                                                <td>
                                                    <code>{{ $teacher->user->email }}</code>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $teacher->is_active ? 'success' : 'secondary' }} rounded-circle p-1"
                                                        title="{{ $teacher->is_active ? 'Active' : 'Inactive' }}">
                                                        <span class="visually-hidden">Status</span>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('school.teachers.show', $teacher) }}"
                                                        class="btn btn-sm btn-outline-primary">View Profile</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- Students for this batch --}}
                                        @foreach($batch->students as $student)
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="badge rounded-pill bg-info text-dark">
                                                        @if($isSport) Athlete @else Student @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $student->user->name }}</div>
                                                    <small class="text-muted">Roll: {{ $student->roll_number }}</small>
                                                </td>
                                                <td>
                                                    <code>{{ $student->user->email }}</code>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $student->is_active ? 'success' : 'secondary' }} rounded-circle p-1"
                                                        title="{{ $student->is_active ? 'Active' : 'Inactive' }}">
                                                        <span class="visually-hidden">Status</span>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('school.students.show', $student) }}"
                                                        class="btn btn-sm btn-outline-primary">View Profile</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if($batch->teachers->isEmpty() && $batch->students->isEmpty())
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    No users assigned to this batch yet.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($class->batches->isEmpty())
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm">
                        No batches created for this class yet. <a href="{{ route('school.batches.create') }}"
                            class="fw-bold">Create a batch</a> to start adding students and teachers.
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection