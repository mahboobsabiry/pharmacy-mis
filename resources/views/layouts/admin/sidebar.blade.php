<div class="main-sidebar main-sidebar-sticky side-menu">
    <div class="sidemenu-logo">
        <a class="main-logo" href="{{ route('index') }}" target="_blank">
            <span class="text-capitalize">BCHS</span>
            <img src="{{ asset('assets/images/logo.jpg') }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="header-brand-img icon-logo" alt="logo">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="header-brand-img desktop-logo theme-logo" alt="logo">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="header-brand-img icon-logo theme-logo" alt="logo">
        </a>
    </div>

    <div class="main-sidebar-body">
        <ul class="nav">
            <!-- Dashboard -->
            <li class="nav-label">@lang('admin.dashboard.dashboard')</li>
            <li class="nav-item {{ request()->url() == route('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fe fe-airplay"></i><span class="sidemenu-label">@lang('admin.dashboard.dashboard')</span>
                </a>
            </li>

            <!-- Pages -->
            <li class="nav-label">@lang('admin.dashboard.pages')</li>

            <!-- Positions -->
            @can('organization_mgmt')
                <li class="nav-item {{ request()->is('admin/positions') ||
                    request()->is('admin/positions/*') ||
                    request()->is('admin/appointment-positions') ||
                    request()->is('admin/empty-positions') ||
                    request()->is('admin/inactive-positions') ? 'active show' : '' }}">

                    <a class="nav-link with-sub" href="javascript:void(0)">
                        <i class="fe fe-life-buoy"></i>
                        <span class="sidemenu-label">@lang('admin.sidebar.positions')</span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>

                    <ul class="nav-sub">
                        <!-- Positions -->
                        <li class="nav-sub-item {{ request()->is('admin/positions') ||
                        request()->is('admin/positions/*') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.positions.index') }}">
                                {<span class="small text-sm-center tx-danger">M</span>}
                                @lang('pages.positions.allPositions')
                                ({{ count(\App\Models\Position::all()) }})
                            </a>
                        </li>

                        <!-- Appointment Positions -->
                        <li class="nav-sub-item {{ request()->is('admin/appointment-positions') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.positions.appointment') }}">
                                @lang('pages.positions.appointmentPositions')
                                    <?php $appointment = \App\Models\Position::all()->sum('num_of_pos') - \App\Models\Employee::all()->count(); ?>
                                ({{ \App\Models\Position::all()->sum('num_of_pos') - $appointment }})
                            </a>
                        </li>

                        <!-- Empty Positions -->
                        <li class="nav-sub-item {{ request()->is('admin/empty-positions') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.positions.empty') }}">
                                @lang('pages.positions.emptyPositions')
                                ({{ \App\Models\Position::all()->sum('num_of_pos') - \App\Models\Employee::all()->count() }})
                            </a>
                        </li>

                        <!-- Inactive Positions -->
                        <li class="nav-sub-item {{ request()->is('admin/inactive-positions') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.positions.inactive') }}">
                                @lang('pages.positions.inactivePositions')
                                ({{ \App\Models\Position::all()->where('status', 0)->count() }})
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <!--/==/ End of Position -->

            <!-- Employees -->
            @can('employee_mgmt')
                <li class="nav-item {{ request()->is('admin/employees') ||
                    request()->is('admin/employees/*') ||
                    request()->is('admin/main-employees') ||
                    request()->is('admin/on-duty-employees') ? 'active show' : '' }}">

                    <a class="nav-link with-sub" href="javascript:void(0)">
                        <i class="fa fa-user-tie"></i>
                        <span class="sidemenu-label">@lang('admin.sidebar.employees')</span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>

                    <ul class="nav-sub">
                        <!-- All Employees -->
                        <li class="nav-sub-item {{ request()->is('admin/employees') ||
                        request()->is('admin/employees/*') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.employees.index') }}">
                                {<span class="small text-sm-center tx-danger">M</span>}
                                @lang('admin.sidebar.employees')
                                ({{ count(\App\Models\Employee::all()) }})
                            </a>
                        </li>

                        <!-- Main Position Employees -->
                        <li class="nav-sub-item {{ request()->is('admin/main-employees') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.employees.main') }}">
                                @lang('pages.employees.mainPosition')
                                ({{ \App\Models\Employee::all()->where('on_duty', 0)->count() }})
                            </a>
                        </li>

                        <!-- On Duty Employees -->
                        <li class="nav-sub-item {{ request()->is('admin/on-duty-employees') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.employees.on_duty') }}">
                                @lang('pages.employees.onDuty')
                                ({{ \App\Models\Employee::all()->where('on_duty', 1)->count() }})
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <!--/==/ End of Position -->

            <!-- Applications -->
            <li class="nav-label">@lang('admin.sidebar.applications')</li>

            <!-- Settings -->
            @can('setting_mgmt')
                <li class="nav-item {{ request()->url() == route('admin.settings.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.settings.index') }}">
                        <i class="fe fe-settings"></i><span class="sidemenu-label">@lang('admin.sidebar.settings')</span>
                    </a>
                </li>
            @endcan

            <!-- User Management -->
            @can('user_mgmt')
                <li class="nav-item {{ request()->is('admin/permissions') ||
                    request()->is('admin/permissions/*') ||
                    request()->is('admin/roles') ||
                    request()->is('admin/roles/*') ||
                    request()->is('admin/users') ||
                    request()->is('admin/users/*') ||
                    request()->is('admin/active-users') ||
                    request()->is('admin/inactive-users') ? 'active show' : '' }}">
                    <a class="nav-link with-sub" href="javascript:void(0)">
                        <i class="fe fe-users"></i>
                        <span class="sidemenu-label">@lang('admin.sidebar.userManagement')</span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>

                    <ul class="nav-sub">
                        <!-- Permissions -->
                        <li class="nav-sub-item {{ request()->is('admin/permissions') ||
                            request()->is('admin/permissions/*') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.permissions.index') }}">@lang('admin.sidebar.permissions')</a>
                        </li>

                        <!-- Roles -->
                        <li class="nav-sub-item {{ request()->is('admin/roles') ||
                            request()->is('admin/roles/*') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.roles.index') }}">@lang('admin.sidebar.roles')</a>
                        </li>

                        <!-- Users -->
                        <li class="nav-sub-item {{ request()->is('admin/users') ||
                            request()->is('admin/users/*') ||
                            request()->is('admin/active-users') ||
                            request()->is('admin/inactive-users') ? 'active' : '' }}">
                            <a class="nav-sub-link" href="{{ route('admin.users.index') }}">@lang('admin.sidebar.users')</a>
                        </li>
                    </ul>
                </li>
            @endcan
            <!--/==/ End of User Management -->
        </ul>
    </div>
</div>
