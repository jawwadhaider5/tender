@inject('request', 'Illuminate\Http\Request')

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        @can('dashboard')
        <li class="nav-item {{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
            <a class="nav-link" href="/dashboard">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @endcan

        @canany(['client'])
        <!-- <li class="nav-item {{ $request->segment(1) == 'clients' && $request->segment(2) == null ? 'active' : '' }}">
            <a class="nav-link" href="/clients">
                <i class="menu-icon mdi mdi-view-list"></i>
                <span class="menu-title">Clients</span>
            </a>
        </li> -->
        <li class="nav-item {{ $request->segment(1) == 'clients-by-city' ? 'active' : '' }}">
            <a class="nav-link" href="/clients-by-city">
                <i class="menu-icon mdi mdi-city"></i>
                <span class="menu-title">Clients</span>
            </a>
        </li>
        @endcan
        @canany(['tender'])
        <li class="nav-item {{ $request->segment(1) == 'tenders' && $request->segment(2) == null ? 'active' : '' }}">
            <a class="nav-link" href="/tenders">
                <i class="menu-icon mdi mdi-view-list"></i>
                <span class="menu-title">Tenders</span>
            </a>
        </li>
        @endcan

        @canany(['future-client'])
        <!-- <li class="nav-item {{ $request->segment(1) == 'future-clients' && $request->segment(2) == null ? 'active' : '' }}">
            <a class="nav-link" href="/future-clients">
                <i class="menu-icon mdi mdi-view-list"></i>
                <span class="menu-title">Future Clients</span>
            </a>
        </li> -->
        <li class="nav-item {{ $request->segment(1) == 'future-clients-by-city' ? 'active' : '' }}">
            <a class="nav-link" href="/future-clients-by-city">
                <i class="menu-icon mdi mdi-city"></i>
                <span class="menu-title">Future Clients</span>
            </a>
        </li>
        @endcan
        @canany(['tender'])
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#calendar-menu" aria-expanded="false" aria-controls="calendar-menu">
                <i class="menu-icon mdi mdi-calendar"></i>
                <span class="menu-title">Calendar</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="calendar-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->segment(1) == 'calendar' && request()->segment(2) == null ? 'active' : '' }}">
                        <a class="nav-link" href="/calendar">All Events</a>
                    </li>
                    <li class="nav-item {{ request()->segment(1) == 'calendar' && request()->segment(2) == 'tenders' ? 'active' : '' }}">
                        <a class="nav-link" href="/calendar/tenders">Tenders</a>
                    </li>
                    <li class="nav-item {{ request()->segment(1) == 'calendar' && request()->segment(2) == 'future-clients' ? 'active' : '' }}">
                        <a class="nav-link" href="/calendar/future-clients">Future Clients</a>
                    </li>
                    <li class="nav-item {{ request()->segment(1) == 'calendar' && request()->segment(2) == 'clients' ? 'active' : '' }}">
                        <a class="nav-link" href="/calendar/clients">Clients</a>
                    </li>
                </ul>
            </div>
        </li>
        @endcan
        @canany(['advance option'])
        <li class="nav-item border-bottom border-white mt-2">
            <span class="p-3 menu-title text-white fw-bold">Advance Option</span>
        </li>
        @canany(['group'])
        <li class="nav-item {{ $request->segment(1) == 'groups' && $request->segment(2) == null ? 'active' : '' }}">
            <a class="nav-link" href="/groups">
                <i class="menu-icon mdi mdi-briefcase-check"></i>
                <span class="menu-title">Group</span>
            </a>
        </li>
        @endcan
        @canany(['position'])
        <li class="nav-item {{ $request->segment(1) == 'positions' && $request->segment(2) == null ? 'active' : '' }}">
            <a class="nav-link" href="/positions">
                <i class="menu-icon mdi mdi mdi-note-text"></i>
                <span class="menu-title">Position</span>
            </a>
        </li>
        @endcan
        @canany(['city'])
        <li class="nav-item {{ $request->segment(1) == 'cities' && $request->segment(2) == null ? 'active' : '' }}">
            <a class="nav-link" href="/cities">
                <i class="menu-icon mdi mdi-file"></i>
                <span class="menu-title">Cities</span>
            </a>
        </li>
        @endcan
        @canany(['tender-type'])
        <li class="nav-item {{ $request->segment(1) == 'tender-types' && $request->segment(2) == null ? 'active' : '' }}">
            <a class="nav-link" href="/tender-types">
                <i class="menu-icon mdi mdi-view-list"></i>
                <span class="menu-title">Tender Typs</span>
            </a>
        </li>
        @endcan

        @canany(['user-list', 'role-list'])
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-account-check"></i>
                <span class="menu-title">Users & Roles</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    @can('user-list')
                    <li class="nav-item {{ request()->segment(1) == 'users' ? 'active' : '' }}"> <a class="nav-link " href="{{ route('users.index') }}">Users</a></li>
                    @endcan
                    @can('role-list')
                    <li class="nav-item {{ request()->segment(1) == 'roles' ? 'active' : '' }}"> <a class="nav-link " href="{{ route('roles.index') }}">Roles</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        @endcan
        @endcan

        @canany(['tenders'])
        <li class="nav-item {{ $request->segment(1) == 'tenders' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tenders.index') }}">
                <i class="menu-icon mdi mdi-file-document"></i>
                <span class="menu-title">Tenders</span>
            </a>
        </li>
        @endcan
        @canany(['tenders-by-city'])
        <li class="nav-item {{ $request->segment(1) == 'tenders-by-city' ? 'active' : '' }}">
            <a class="nav-link" href="/tenders-by-city">
                <i class="menu-icon mdi mdi-city"></i>
                <span class="menu-title">Tenders by City</span>
            </a>
        </li>
        @endcan

    </ul>
</nav>
<!-- partial -->