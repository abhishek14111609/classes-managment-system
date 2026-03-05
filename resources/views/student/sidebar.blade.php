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
            <h6 class="fw-bold mb-0 text-white">{{ $isSport ? 'Athlete Portal' : 'Student Portal' }}</h6>
            <small class="text-white opacity-75 tiny">
                @if($isSport) Sports Academy @else School @endif
            </small>
        </div>
    </div>
</div>

<ul class="nav flex-column sidebar-menu">
    <li class="nav-label tiny text-white opacity-75 mb-2" style="padding-left: 15px;">Main</li>
    <li>
        <a href="{{ route('student.dashboard') }}"
            class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">
        {{ $isSport ? 'Training & Games' : 'Academic' }}</li>
    <li>
        <a href="{{ route('student.profile') }}" class="{{ request()->routeIs('student.profile') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> <span>My Portfolio</span>
        </a>
    </li>
    <li>
        <a href="{{ route('student.timetable') }}"
            class="{{ request()->routeIs('student.timetable') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> <span>{{ $label['timetable'] }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('student.attendance.index') }}"
            class="{{ request()->routeIs('student.attendance.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> <span>{{ $label['attendance'] }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('student.events.index') }}"
            class="{{ request()->routeIs('student.events.*') ? 'active' : '' }}">
            <i class="bi bi-trophy"></i> <span>{{ $label['events'] }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('student.resources') }}"
            class="{{ request()->routeIs('student.resources') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i> <span>{{ $label['materials'] }}</span>
        </a>
    </li>

    <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">Finance</li>
    <li>
        <a href="{{ route('student.fees.index') }}" class="{{ request()->routeIs('student.fees.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> <span>{{ $label['fees'] }}</span>
        </a>
    </li>

    <li class="nav-label tiny text-white opacity-75 mt-4 mb-2" style="padding-left: 15px;">Account</li>
    <li>
        <a href="{{ route('student.settings') }}" class="{{ request()->routeIs('student.settings') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> <span>Settings</span>
        </a>
    </li>
    <li>
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('global-logout-form').submit();"
            class="text-danger opacity-75">
            <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
        </a>
    </li>
</ul>

<li class="mt-auto pt-5">
    <div class="card bg-primary bg-opacity-10 border-0 rounded-4 mx-2">
        <div class="card-body p-3">
            <h6 class="text-primary fw-bold mb-1 small">Need Help?</h6>
            <p class="text-secondary small mb-0" style="font-size: 0.75rem;">
                Contact your @if($isSport) coach @else school admin @endif for any issues.
            </p>
        </div>
    </div>
</li>