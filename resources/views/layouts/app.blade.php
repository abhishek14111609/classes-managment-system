<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @auth
            @php
                $branding = config('app.name', 'Management System');
                if (auth()->user()->isSuperAdmin()) {
                    $branding = 'System Admin';
                } elseif (auth()->user()->isStudent() && auth()->user()->student && auth()->user()->student->batch && auth()->user()->student->batch->class) {
                    $branding = auth()->user()->student->batch->class->name;
                } elseif (auth()->user()->school) {
                    $branding = auth()->user()->school->name;
                }
            @endphp
            @yield('title') | {{ $branding }}
        @else
            @yield('title', config('app.name', 'Management System'))
        @endauth
    </title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom Modern UI -->
    <link rel="stylesheet" href="{{ asset('css/modern-ui.css') }}">

    <style>
        :root {
            scroll-behavior: smooth;
        }

        .sidebar {
            overflow-y: auto;
            scroll-behavior: smooth;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        @if(!View::hasSection('custom_sidebar_header'))
            <div class="sidebar-header">
                <h4>
                    @if(auth()->check() && auth()->user()->school && auth()->user()->school->logo)
                        <img src="{{ asset('storage/' . auth()->user()->school->logo) }}" alt="Logo"
                            class="img-fluid rounded me-2" style="max-height: 28px; width: auto;">
                    @else
                        <i class="bi bi-mortarboard-fill text-primary"></i>
                    @endif
                    @auth
                        @php
                            $branding = config('app.name', 'Management System');
                            if (auth()->user()->isSuperAdmin()) {
                                $branding = 'System Admin';
                            } elseif (auth()->user()->isStudent() && auth()->user()->student && auth()->user()->student->batch && auth()->user()->student->batch->class) {
                                $branding = auth()->user()->student->batch->class->name;
                            } elseif (auth()->user()->school) {
                                $branding = auth()->user()->school->name;
                            }
                        @endphp
                        <span>{{ Str::limit($branding, 15) }}</span>
                    @else
                        <span>{{ config('app.name', 'IMS') }}</span>
                    @endauth
                </h4>
                <button class="mobile-toggle d-lg-none ms-auto text-white" onclick="toggleSidebar()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @auth
            <div class="px-4 py-3 border-bottom border-secondary border-opacity-10">
                <div class="d-flex align-items-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="rounded-circle me-3"
                            style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3"
                            style="width: 40px; height: 40px;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <h6 class="text-white mb-0 text-truncate" style="font-size: 0.9rem;">{{ auth()->user()->name }}</h6>
                        <small class="text-white opacity-75 text-truncate d-block"
                            style="font-size: 0.75rem;">{{ auth()->user()->email }}</small>
                    </div>
                </div>
            </div>
        @endauth

        <ul class="sidebar-menu mt-3">
            @yield('sidebar')
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Navbar for Mobile -->
        <div class="d-flex justify-content-between align-items-center mb-4 d-lg-none">
            <button class="btn btn-light mobile-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h5 class="m-0 text-dark fw-bold">@yield('title')</h5>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('profile.edit') }}" class="btn btn-icon btn-light rounded-circle shadow-sm">
                    <i class="bi bi-person text-secondary"></i>
                </a>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('global-logout-form').submit();"
                    class="btn btn-icon btn-danger rounded-circle shadow-sm">
                    <i class="bi bi-box-arrow-right text-white"></i>
                </a>
            </div>
        </div>

        <form id="global-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <!-- Desktop Header -->
        @unless(View::hasSection('hide_header'))
            <div class="d-none d-lg-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
                <div>
                    <h2 class="fw-bold text-dark m-0">@yield('title')</h2>
                    <p class="text-muted mb-0">Welcome back to your dashboard</p>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-light bg-white border shadow-sm">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); if(confirm('Are you sure you want to logout?')) document.getElementById('global-logout-form').submit();"
                        class="btn btn-danger text-white shadow-sm fw-bold">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </div>
            </div>
        @endunless

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 bg-success-subtle text-success"
                role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 bg-danger-subtle text-danger"
                role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Content Injection -->
        @yield('content')

        <!-- Minimalist Company Footer -->
        <footer class="footer mt-5 pb-4 text-center animate-fade-in-up delay-200">
            <div class="container-fluid">
                <div class="row pt-4 border-top border-light">
                    <div class="col-12">
                        <p class="text-muted small mb-1 opacity-75">
                            Developed & Maintained by
                            <a href="https://webvibeinfotech.in" target="_blank"
                                class="text-primary fw-bold text-decoration-none">
                                Webvibe Infotech
                            </a>
                        </p>
                        <div class="tiny text-muted opacity-50">
                            &copy; {{ date('Y') }} All Rights Reserved. | v2.4.0
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Loader
        window.addEventListener('load', function () {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }
        });

        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
        }

        /**
         * Institutional Navigation Persistence System
         * Ensures temporal and spatial continuity across page cycles.
         */
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const currentPath = window.location.pathname;

            // 1. Sidebar Scroll Persistence
            if (sidebar) {
                const savedSidebarScroll = localStorage.getItem('sidebar_scroll_pos');
                if (savedSidebarScroll) {
                    sidebar.scrollTop = savedSidebarScroll;
                }

                // Attach scroll listeners to all sidebar links
                sidebar.querySelectorAll('.nav-link, a').forEach(link => {
                    link.addEventListener('click', () => {
                        localStorage.setItem('sidebar_scroll_pos', sidebar.scrollTop);
                    });
                });
            }

            // 2. Main Viewport Persistence (for same-route interactions e.g. form submissions)
            const savedWindowScroll = sessionStorage.getItem('window_scroll_' + currentPath);
            if (savedWindowScroll) {
                window.scrollTo({
                    top: parseInt(savedWindowScroll),
                    behavior: 'instant'
                });
                sessionStorage.removeItem('window_scroll_' + currentPath);
            }

            // Capture scroll before leave
            window.addEventListener('beforeunload', () => {
                if (sidebar) {
                    localStorage.setItem('sidebar_scroll_pos', sidebar.scrollTop);
                }
                sessionStorage.setItem('window_scroll_' + currentPath, window.scrollY);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>