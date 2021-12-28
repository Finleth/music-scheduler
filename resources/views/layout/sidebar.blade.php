<nav class="sidebar">
    <div class="sidebar-header">
        <a href="/" class="sidebar-brand">
            Music<span>Scheduler</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Schedule</li>
                <li class="nav-item {{ active_class(['schedule']) }}">
                    <a href="{{ route('schedule-list') }}" class="nav-link">
                        <i class="link-icon" data-feather="calendar"></i>
                        <span class="link-title">Schedule</span>
                    </a>
                </li>
                <li class="nav-item {{ active_class(['schedule-event-types']) }}">
                    <a href="{{ route('schedule-event-types-list') }}" class="nav-link">
                        <i class="link-icon" data-feather="clipboard"></i>
                        <span class="link-title">Event Types</span>
                    </a>
                </li>
            </li>

            <li class="nav-item nav-category">Users</li>
                <li class="nav-item {{ active_class(['musicians']) }}">
                    <a href="{{ route('musicians-list') }}" class="nav-link">
                        <i class="link-icon" data-feather="user"></i>
                        <span class="link-title">Musicians</span>
                    </a>
                </li>
            </li>
        </ul>
    </div>
</nav>
