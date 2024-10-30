<!-- Main Sidebar Container -->
@php($busadminRoutes = config('global.businessAdminURL'))

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('masteradmin.home') }}" class="brand-link">
        <img src="{{ url('public/dist/img/logo.png') }}" alt="Trip Tracker Logo" class="brand-image">
        <img src="{{ url('public/dist/img/small-logo.png') }}" alt="Trip Tracker Logo" class="brand-image-small">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('masteradmin.home') }}"
                        class="nav-link {{ request()->is($busadminRoutes . '/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Trips
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if (isset($access['book_trip']) && $access['book_trip'])
                            <li class="nav-item {{ request()->is($busadminRoutes.'/trip*')
                    ? 'menu-open' : '' }}" >
                                <a href="{{ route('trip.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/trip*') ||
                                    request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Workflow</p>
                                </a>
                            </li>
                        @endif


                        @if (isset($access['view_role']) && $access['view_role'])
                            <li class="nav-item">
                                <a href="#"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Booked Trips (After Booked)</p>
                                </a>
                            </li>
                        @endif
                        @if (isset($access['view_role']) && $access['view_role'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.trip.booked_after') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Booked Trips (After Booked)</p>
                                </a>
                            </li>
                            
                        @endif

                        @if (isset($access['view_role']) && $access['view_role'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.task.all') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tasks</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Travelers
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if (isset($access['book_trip']) && $access['book_trip'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.travelers.travelersDetails') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View All Travelers</p>
                                </a>
                            </li>
                        @endif

                        @if (isset($access['book_trip']) && $access['book_trip'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.travelers.create') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Traveler</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('masteradmin.home') }}"
                        class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Email Templates
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (isset($access['edit_profile']) && $access['edit_profile'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.emailtemplate.create') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Email Template</p>
                                </a>
                            </li>
                        @endif

                        @if (isset($access['view_role']) && $access['view_role'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.emailtemplate.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/*') ||
                                    request()->is($busadminRoutes . '//*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View All Email Templates</p>
                                </a>
                            </li>
                        @endif
                        @if (isset($access['view_role']) && $access['view_role'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.emailtemplate.EmailTemplate') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/*') ||
                                    request()->is($busadminRoutes . '//*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Email Template</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Libraries
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if (isset($access['book_trip']) && $access['book_trip'])
                            <li class="nav-item">
                                <a href="{{ route('library.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View All Libraries
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (isset($access['book_trip']) && $access['book_trip'])
                            <li class="nav-item">
                                <a href="{{ route('library.create') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Library
                                    </p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Agency Users
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if (isset($access['book_trip']) && $access['book_trip'])
                            <li class="nav-item">
                                <a href="{{ route('agency.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View All Users</p>
                                </a>
                            </li>
                        @endif

                        @if (isset($access['book_trip']) && $access['book_trip'])
                            <li class="nav-item">
                                <a href="{{ route('agency.create') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add User</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <li class="nav-item {{ request()->is($busadminRoutes . '/h*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fas fa-key"></i>
                        <p>
                            User Roles
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                    @if (isset($access['view_role']) && $access['view_role'])
                            <li class="nav-item">
                                <a href="{{ route('user-role-details.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View All Agencies Role</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>


                <li class="nav-item {{ request()->is($busadminRoutes . '/h*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fas fa-cog"></i>
                        <p>
                            Settings
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (isset($access['edit_profile']) && $access['edit_profile'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.profile.edit') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Profile</p>
                                </a>
                            </li>
                        @endif
                        {{-- 
                        @if (isset($access['view_role']) && $access['view_role'])
                            <li class="nav-item">
                                <a href="{{ route('user-role-details.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h*') ||
                                    request()->is($busadminRoutes . '/h/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>User Role</p>
                                </a>
                            </li>
                        @endif --}}



                        <li class="nav-item">
                            <a href="{{ route('masteradmin.masterlog.index') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/logActivity*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Log Activity</p>
                            </a>
                        </li>

                    </ul>
                </li>



                <li class="nav-item">
                    <form id="logout-form" method="POST" action="{{ route('masteradmin.logout') }}"
                        style="display: none;">
                        @csrf
                    </form>

                    <a href="{{ route('masteradmin.logout') }}" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
