<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <div class="navbar-nav">
            <li class="nav-item dropdown nav-profile">
                <a class="nav-link dropdown-toggle nav-profile-icon" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{Auth::user()->first_name[0] . Auth::user()->last_name[0]}}
                </a>

                <div class="dropdown-menu" aria-labelledby="profileDropdown">
                    <div class="dropdown-header d-flex flex-column align-items-center">
                        <div class="info text-center">
                            <p class="name font-weight-bold mb-0">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            <p class="email text-muted mb-3">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="dropdown-body">
                        <ul class="profile-nav p-0 pt-3">
                            <li class="nav-item">
                                <a href="{{ route('logout') }}" class="nav-link"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="link-icon" data-feather="log-out"></i><span class="link-title">{{ __('Logout') }}</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </div>
    </div>
</nav>
