<div class="sidebar-brand-wrapper mb-4">
    <div class="sidebar-brand d-flex align-items-center">
        <div class="brand-logo bg-white p-2 rounded-3 me-2 shadow-sm d-flex align-items-center justify-content-center"
            style="width: 40px; height: 40px; overflow: hidden;">
            @if(auth()->user()->school && auth()->user()->school->logo)
                <img src="{{ asset('storage/' . auth()->user()->school->logo) }}" alt="Logo" class="img-fluid"
                    style="max-height: 100%; object-fit: contain;">
            @else
                <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
            @endif
        </div>
        <div>
            <h6 class="fw-bold mb-0 text-white">{{ $label['teacher_portal'] }}</h6>
            <small class="text-white opacity-75 tiny">
                @if($isSport) Sports Academy @else School @endif
            </small>
        </div>
    </div>
</div>

<ul class="nav flex-column sidebar-nav">
    <li class="nav-label tiny text-white opacity-75 mb-2" style="padding-left: 15px;">Main</li>

    <li class="nav-item">
        <a href="{{ route('teacher.dashboard') }}"
            class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">
        {{ $label['section_academic'] }}
    </li>

    <li class="nav-item">
        <a href="{{ route('teacher.attendance.index') }}"
            class="nav-link {{ request()->routeIs('teacher.attendance.*') ? 'active' : '' }}">
            <i class="bi bi-calendar2-check-fill"></i>
            <span>{{ $label['attendance'] }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('teacher.batches.index') }}"
            class="nav-link {{ request()->routeIs('teacher.batches.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>My {{ $label['batches'] }} & {{ $label['students'] }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('teacher.materials.index') }}"
            class="nav-link {{ request()->routeIs('teacher.materials.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            <span>{{ $label['materials'] }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('teacher.events.index') }}"
            class="nav-link {{ request()->routeIs('teacher.events.*') ? 'active' : '' }}">
            <i class="bi bi-trophy-fill"></i>
            <span>{{ $label['events'] }}</span>
        </a>
    </li>

    <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">Account</li>

    <li class="nav-item">
        <a href="{{ route('teacher.profile') }}"
            class="nav-link {{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> <span>My Profile</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('teacher.settings') }}"
            class="nav-link {{ request()->routeIs('teacher.settings') ? 'active' : '' }}">
            <i class="bi bi-gear-wide-connected"></i> <span>Settings</span>
        </a>
    </li>

    <li class="nav-item mt-2">
        <a href="{{ route('logout') }}" class="nav-link text-danger opacity-75"
            onclick="event.preventDefault(); document.getElementById('global-logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
        </a>
    </li>
</ul>

<div class="sidebar-footer mt-5 p-3 mx-2 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10">
    <div class="d-flex align-items-center">
        <div class="shrink-0 bg-white bg-opacity-20 p-2 rounded-3 me-2">
            <i class="bi bi-headset text-white"></i>
        </div>
        <div>
            <p class="mb-0 tiny fw-bold text-white">Need Help?</p>
            <p class="mb-0 text-white opacity-75" style="font-size: 0.6rem;">Contact Admin</p>
        </div>
    </div>
</div>