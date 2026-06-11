<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="{{ route('dashboard') }}">
            <img alt="#" src="{{ asset('assets/admin/images/logo/1.png') }}">
        </a>

        <span class="bg-light-primary toggle-semi-nav d-flex-center">
            <i class="ti ti-chevron-right"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">

            <!-- Main -->
            <li class="menu-title">
                <span>Main</span>
            </li>

            <li class="no-sub">
                <a href="{{ route('dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            @canany(['Role Show','Permission Show','Employee Show','Agent Show'])
            <!-- User Management -->
            <li class="menu-title">
                <span>User Management</span>
            </li>
            @can('Role Show')
            <li class="no-sub">
                <a href="{{ route('roles.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3l7 4v5c0 5-3.5 8-7 9c-3.5-1-7-4-7-9V7l7-4z"/>
                        <path d="M9 12l2 2l4-4"/>
                    </svg>
                    Role
                </a>
            </li>
            @endcan
            @can('Permission Show')
            <li class="no-sub">
                <a href="{{ route('permissions.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 7a4 4 0 1 0 -8 0v4h8V7z"/>
                        <path d="M12 15v2"/>
                        <path d="M6 11h12v8H6z"/>
                    </svg>
                    Permission
                </a>
            </li>
            @endcan
            @can('Employee Show')
            <li class="no-sub">
                <a href="{{ route('employees.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0 -4 -4H7a4 4 0 0 0 -4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0 -3 -3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Employee
                </a>
            </li>
            @endcan
            @can('Agent Show')
            <li class="no-sub">
                <a href="{{ route('agents.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                        <path d="M19 8l1 2l2 .3l-1.5 1.5l.4 2.2l-1.9-1l-1.9 1l.4-2.2L16 10.3l2-.3l1-2z"/>
                    </svg>
                    Agent
                </a>
            </li>
            @endcan
            @endcanany

            @canany(['Game Entry Show','Result Check'])
            <!-- Game Management -->
            <li class="menu-title">
                <span>Game Management</span>
            </li>

            @can('Game Entry Show')
            <li class="no-sub">
                <a href="{{ route('game-entry.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="8" width="16" height="8" rx="3"/>
                        <path d="M8 12h.01"/>
                        <path d="M12 12h.01"/>
                        <path d="M16 12h.01"/>
                    </svg>
                    Game Entry
                </a>
            </li>
            @endcan
            @can('Result Check')
            <li class="no-sub">
                <a href="{{ route('results.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 21h8"/>
                        <path d="M12 17v4"/>
                        <path d="M7 4h10v6a5 5 0 0 1 -10 0V4z"/>
                        <path d="M5 6H3a2 2 0 0 0 2 2"/>
                        <path d="M19 6h2a2 2 0 0 1 -2 2"/>
                    </svg>
                    Result Check
                </a>
            </li>
            @endcan
            @can('Patti Check')
            <li class="no-sub">
                <a href="{{ route('patti-check.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 21h8"/>
                        <path d="M12 17v4"/>
                        <path d="M7 4h10v6a5 5 0 0 1 -10 0V4z"/>
                        <path d="M5 6H3a2 2 0 0 0 2 2"/>
                        <path d="M19 6h2a2 2 0 0 1 -2 2"/>
                    </svg>
                    Patti Check
                </a>
            </li>
            @endcan
            @endcanany

            @canany(['Entry Report','Single Report','Patti Report'])
            <!-- Reports -->
            <li class="menu-title">
                <span>Reports</span>
            </li>
            @can('Entry Report')
            <li class="no-sub">
                <a href="{{ route('reports.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                        <path d="M17 21H7a2 2 0 0 1 -2 -2V5a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                        <path d="M9 17l2-2l2 2l4-4"/>
                    </svg>
                    Entry Report
                </a>
            </li>
            @endcan
            @can('Result Report')
            <li class="no-sub">
                <a href="{{ route('results.history') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                        <path d="M17 21H7a2 2 0 0 1 -2 -2V5a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                        <path d="M9 17l2-2l2 2l4-4"/>
                    </svg>
                    Result Report
                </a>
            </li>
            @endcan
            
            @can('Single Report')
            <li class="no-sub">
                <a href="{{ route('reports.single') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19h16"/>
                        <path d="M7 15v-6"/>
                        <path d="M12 15v-10"/>
                        <path d="M17 15v-3"/>
                    </svg>
                    Single Report
                </a>
            </li>
            @endcan
            @can('Patti Report')
            <li class="no-sub">
                <a href="{{ route('reports.patti') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19h16"/>
                        <path d="M7 15v-6"/>
                        <path d="M12 15v-10"/>
                        <path d="M17 15v-3"/>
                    </svg>
                    Patti Report
                </a>
            </li>
            @endcan
            @endcanany

            <!-- Account -->
            <li class="menu-title">
                <span>Account</span>
            </li>

            <li class="no-sub">
                <a href="{{ route('profile.edit') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                    </svg>
                    Profile
                </a>
            </li>

            <li class="no-sub">
                <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 8V6a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2v-2"/>
                        <path d="M3 12h13"/>
                        <path d="M7 16l-4 -4l4 -4"/>
                    </svg>
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>

</nav>