<!-- Main Sidebar Container -->
@php($busadminRoutes = config('global.businessAdminURL'))

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('masteradmin.home') }}" class="brand-link">
        <img src="{{ url('public/dist/img/logo.png') }}" alt="Trip Tracker Logo" class="brand-image">
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
                        class="nav-link {{ request()->is($busadminRoutes . '/dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                <li
                    class="nav-item {{ request()->is($busadminRoutes . '/profile*') ? 'menu-open' : '' }}">
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
                                    class="nav-link {{ request()->is($busadminRoutes . '/profile*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Profile</p>
                                </a>
                            </li>
                        @endif

                        @if (isset($access['view_role']) && $access['view_role'])
                            <li class="nav-item">
                                <a href="{{ route('user-role-details.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/userdetails*') ||
                                    request()->is($busadminRoutes . '/usercreate*') ||
                                    request()->is($busadminRoutes . '/useredit/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>User Role</p>
                                </a>
                            </li>
                        @endif

                        @if (isset($access['book_trip']) && $access['book_trip'])
                            <li class="nav-item">
                                <a href="{{ route('trip.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/userdetails*') ||
                                    request()->is($busadminRoutes . '/usercreate*') ||
                                    request()->is($busadminRoutes . '/useredit/*')
                                        ? 'active'
                                        : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Workflow</p>
                                </a>
                            </li>
                        @endif


                        <li class="nav-item">
                            <a href="{{ route('masteradmin.masterlog.index') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/logActivity*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Log Activity</p>
                            </a>
                        </li>

                    </ul>
                </li>



                <!-- edited by ravi -->

                <li
                    class="nav-item {{ request()->is($busadminRoutes . '/profile*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fas fa-plus"></i>
                        <p>
                            library
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (isset($access['edit_profile']) && $access['edit_profile'])
                            <li class="nav-item">
                                <a href="{{ route('library.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/profile*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add library Item</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>


                <li
                    class="nav-item {{ request()->is($busadminRoutes . '/profile*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fas fa-user"></i>
                        <p>
                            Agency Users
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (isset($access['edit_profile']) && $access['edit_profile'])
                            <li class="nav-item">
                                <a href="{{ route('agency.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/profile*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add library Item</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <!-- edited by ravi -->





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
