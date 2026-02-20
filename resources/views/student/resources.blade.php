@extends('layouts.app')

@section('title', 'Learning Resources')

@section('sidebar')
    @include('student.sidebar')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            {{-- Document Categories --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-4 text-uppercase small text-muted">Categories</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary text-start px-3 py-2">
                                <i class="bi bi-folder2-open me-2"></i> All Files
                            </button>
                            <button class="btn btn-light text-start px-3 py-2 border">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Exam Papers
                            </button>
                            <button class="btn btn-light text-start px-3 py-2 border">
                                <i class="bi bi-file-earmark-word text-primary me-2"></i> Study Guides
                            </button>
                            <button class="btn btn-light text-start px-3 py-2 border">
                                <i class="bi bi-card-image text-success me-2"></i> ID Cards
                            </button>
                        </div>

                        <div class="mt-5 pt-4 border-top">
                            <h6 class="fw-bold mb-3 small text-muted text-uppercase">Storage</h6>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%"></div>
                            </div>
                            <small class="text-muted">1.2 GB of 5 GB used</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- File Explorer --}}
            <div class="col-md-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Library Files</h5>
                        <div class="input-group w-auto">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control bg-light border-start-0 ps-0"
                                placeholder="Search resources...">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @php
                                $files = [
                                    ['name' => 'Spring Semester Syllabus 2026.pdf', 'size' => '2.4 MB', 'icon' => 'pdf', 'color' => 'danger'],
                                    ['name' => 'Campus Map & Guidelines.jpg', 'size' => '5.1 MB', 'icon' => 'image', 'color' => 'success'],
                                    ['name' => 'Mathematics Practice Set - 04.docx', 'size' => '840 KB', 'icon' => 'word', 'color' => 'primary'],
                                    ['name' => 'Annual Sports Day Registration.pdf', 'size' => '1.1 MB', 'icon' => 'pdf', 'color' => 'danger'],
                                    ['name' => 'Fee Structure - Academic Year.pdf', 'size' => '320 KB', 'icon' => 'pdf', 'color' => 'danger'],
                                    ['name' => 'Uniform Guidelines.docx', 'size' => '1.2 MB', 'icon' => 'word', 'color' => 'primary'],
                                ];
                            @endphp

                            @foreach($files as $file)
                                <div class="col-md-6 col-lg-4">
                                    <div class="border rounded-4 p-3 hover-lift hover-shadow h-100 d-flex flex-column bg-white">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="p-3 rounded-4 bg-{{ $file['color'] }} bg-opacity-10 me-3">
                                                @if($file['icon'] == 'pdf')
                                                    <i class="bi bi-file-earmark-pdf fs-3 text-{{ $file['color'] }}"></i>
                                                @elseif($file['icon'] == 'word')
                                                    <i class="bi bi-file-earmark-word fs-3 text-{{ $file['color'] }}"></i>
                                                @else
                                                    <i class="bi bi-file-earmark-image fs-3 text-{{ $file['color'] }}"></i>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden">
                                                <h6 class="mb-0 text-truncate fw-bold">{{ $file['name'] }}</h6>
                                                <small class="text-muted">{{ $file['size'] }}</small>
                                            </div>
                                        </div>
                                        <div class="mt-auto pt-2 d-flex gap-2">
                                            <button class="btn btn-light grow py-2 rounded-3">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-primary grow py-2 rounded-3">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-info border-0 rounded-4 mt-5 p-4 d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Requesting Documents?</h6>
                                <p class="mb-0 small">If you need specific documents like official transcripts or bona fide
                                    certificates, please visit the administrative office or use the "Settings" page to
                                    request an update.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection