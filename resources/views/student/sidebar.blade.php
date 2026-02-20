<li>
    <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('student.profile') }}" class="{{ request()->routeIs('student.profile') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> <span>My Profile</span>
    </a>
</li>
<li>
    <a href="{{ route('student.timetable') }}" class="{{ request()->routeIs('student.timetable') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i> <span>Timetable</span>
    </a>
</li>
<li>
    <a href="{{ route('student.attendance.index') }}"
        class="{{ request()->routeIs('student.attendance.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> <span>Attendance</span>
    </a>
</li>
<li>
    <a href="{{ route('student.fees.index') }}" class="{{ request()->routeIs('student.fees.*') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i> <span>Fees & Payments</span>
    </a>
</li>
<li>
    <a href="{{ route('student.events.index') }}" class="{{ request()->routeIs('student.events.*') ? 'active' : '' }}">
        <i class="bi bi-trophy"></i> <span>Sports & Events</span>
    </a>
</li>
<li>
    <a href="{{ route('student.resources') }}" class="{{ request()->routeIs('student.resources') ? 'active' : '' }}">
        <i class="bi bi-journal-bookmark"></i> <span>Resources</span>
    </a>
</li>

<li class="mt-4 pt-3 border-top border-secondary border-opacity-10">
    <div class="px-3 mb-2 small text-muted text-uppercase fw-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">
        Account</div>
</li>
<li>
    <a href="{{ route('student.settings') }}" class="{{ request()->routeIs('student.settings') ? 'active' : '' }}">
        <i class="bi bi-gear"></i> <span>Settings</span>
    </a>
</li>
<li>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
    </a>
</li>

<li class="mt-auto pt-5">
    <div class="card bg-primary bg-opacity-10 border-0 rounded-4 mx-2">
        <div class="card-body p-3">
            <h6 class="text-primary fw-bold mb-1 small">Need Help?</h6>
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Contact your school admin for any issues.</p>
        </div>
    </div>
</li>