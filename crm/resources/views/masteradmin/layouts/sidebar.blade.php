<?php //dd($access); ?>
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               @if(
            (isset($access['view_trip']) && $access['view_trip'])  || 
            (isset($access['book_after_trip']) && $access['book_after_trip'])  || 
            (isset($access['follow_up']) && $access['follow_up']) || 
            (isset($access['task_details']) && $access['task_details'])  || 
            (isset($access['task_category']) && $access['task_category'])  
            )

                <li class="nav-item 
                            {{ request()->is($busadminRoutes . '/trip*') ||
    request()->is($busadminRoutes . '/booked_trips*') ||
    request()->is($busadminRoutes . '/follow_up_trips*') ||
    request()->is($busadminRoutes . '/task-details*') ||
    request()->is($busadminRoutes . '/task-category*') ||
    request()->is($busadminRoutes . '/view-trip*')

    ? 'menu-open' : '' }}">
    
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Trips
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                  
                    <ul class="nav nav-treeview">
                        <!-- Workflow -->
                        @if (isset($access['view_trip']) && $access['view_trip'])
                        <li class="nav-item">
                            <a  
                                href="{{ route('trip.index') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/trip*') || request()->is($busadminRoutes . '/view-trip*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pre Booked Trips</p>
                            </a>
                        </li>
                    @endif
                    @if (isset($access['book_after_trip']) && $access['book_after_trip'])
                        <!-- Booked Trips -->
                        <li class="nav-item">
                            <a href="{{ route('masteradmin.trip.booked_after') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/booked_trips*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Booked Trips (After Booked)</p>
                            </a>
                        </li>
                        @endif
                        @if (isset($access['follow_up']) && $access['follow_up'])
                        <!-- Trip Follow Up -->
                        <li class="nav-item">
                            <a href="{{ route('masteradmin.trip.follow_up_trip_details') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/follow_up_trips*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Trip Follow Up (After Travel)</p>
                            </a>
                        </li>
                        @endif
                        @if (isset($access['task_details']) && $access['task_details'])
                        <!-- Tasks -->
                        <li class="nav-item">
                            <a href="{{ route('masteradmin.task.all') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/task-details*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tasks</p>
                            </a>
                        </li>
                        @endif
                        @if (isset($access['task_category']) && $access['task_category'])
                        <!-- Tasks Category -->
                        <li class="nav-item">
                            <a href="{{ route('task-category.index') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/task-category*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tasks Category</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('masteradmin.home') }}"
                        class="nav-link {{ request()->is($busadminRoutes . '/dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Analytics</p>
                    </a>
                </li>


                @if(
            (isset($access['list_traveler']) && $access['list_traveler'])  || 
            (isset($access['add_traveler']) && $access['add_traveler'])  
            )
                <li class="nav-item {{ request()->is($busadminRoutes . '/travelers*') ||
    request()->is($busadminRoutes . '/travelers-create*') ||
    request()->is($busadminRoutes . '/view-travelers*')
    ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is($busadminRoutes . '/travelers*') ||
    request()->is($busadminRoutes . '/travelers-create*')
    ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Travelers
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- View All Travelers -->
                        @if (isset($access['list_traveler']) && $access['list_traveler'])
                                            <li class="nav-item">
                                                <a href="{{ route('masteradmin.travelers.travelersDetails') }}" class="nav-link {{ request()->is($busadminRoutes . '/travelers*') &&
                            !request()->is($busadminRoutes . '/travelers-create*') || request()->is($busadminRoutes . '/view-travelers*')
                            ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>View All Travelers</p>
                                                </a>
                                            </li>
                        @endif

                        <!-- Add Traveler -->
                        @if (isset($access['add_traveler']) && $access['add_traveler'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.travelers.create') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/travelers-create*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Traveler</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif          
                
                

                @if(
            (isset($access['add_email_template']) && $access['add_email_template'])  || 
            (isset($access['email_category']) && $access['email_category'])  ||
            (isset($access['view_email_template']) && $access['view_email_template'])  
            )
                <li class="nav-item {{ request()->is($busadminRoutes . '/email-templates*') ||
    request()->is($busadminRoutes . '/email-create*') ||
    request()->is($busadminRoutes . '/email') ||
    request()->is($busadminRoutes . '/emaildetail*') ||
    request()->is($busadminRoutes . '/email_category*') ||
    request()->is($busadminRoutes . '/emailtemplate*')
    ? 'menu-open' : '' }}">
                    <a href="{{ route('masteradmin.home') }}" class="nav-link {{ request()->is($busadminRoutes . '/email-templates*') ||
    request()->is($busadminRoutes . '/email-create*') ||
    request()->is($busadminRoutes . '/email') ||
    request()->is($busadminRoutes . '/emaildetail*') ||
    request()->is($busadminRoutes . '/email_category*')

    ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Email Templates
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Add Email Template -->
                        @if (isset($access['add_email_template']) && $access['add_email_template'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.emailtemplate.create') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/email-create*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Email Template</p>
                                </a>
                            </li>
                        @endif

                        <!-- View All Email Templates -->
                        @if (isset($access['view_email_template']) && $access['view_email_template'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.emailtemplate.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/email') || request()->is($busadminRoutes . '/emailtemplate') ? 'active' : '' }}">

                                    <i class="far fa-circle nav-icon"></i>
                                    <p>View All Email Templates</p>
                                </a>
                            </li>
                        @endif

                        <!-- Email Template -->
                        @if (isset($access['view_email_template']) && $access['view_email_template'])
                            <li class="nav-item">
                                <a href="{{ route('masteradmin.emailtemplate.EmailTemplate') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/emaildetail*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Email Template</p>
                                </a>
                            </li>
                        @endif

                        <!-- Add Category -->
                        @if (isset($access['email_category']) && $access['email_category'])
                            <li class="nav-item">
                                <a href="{{ route('email_category.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/email_category*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Category</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif     


                @if(
            (isset($access['list_library']) && $access['list_library'])  || 
            (isset($access['add_library']) && $access['add_library'])  ||
            (isset($access['list_library_cat']) && $access['list_library_cat'])  
            )
                <li class="nav-item {{ request()->is($busadminRoutes . '/library*') ||
    request()->is($busadminRoutes . '/library_category*')
    ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is($busadminRoutes . '/library*') ||
    request()->is($busadminRoutes . '/library_category*')
    ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Library
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Library -->
                        @if (isset($access['list_library']) && $access['list_library'])
                                            <li class="nav-item">
                                                <a href="{{ route('library.index') }}" class="nav-link {{ request()->is($busadminRoutes . '/library') ||
                            request()->is($busadminRoutes . '/library/view*')
                            ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Library</p>
                                                </a>
                                            </li>
                        @endif

                        <!-- Add Library Item -->
                        @if (isset($access['add_library']) && $access['add_library'])
                            <li class="nav-item">
                                <a href="{{ route('library.create') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/library/create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Library Item</p>
                                </a>
                            </li>
                        @endif

                        <!-- Add Category -->
                        @if (isset($access['list_library_cat']) && $access['list_library_cat'])
                            <li class="nav-item">
                                <a href="{{ route('library_category.index') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/library_category*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Category</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif   
                
                
                @if(
            (isset($access['view_user']) && $access['view_user'])  || 
            (isset($access['add_user']) && $access['add_user'])  
            )

                          
                <li class="nav-item {{ request()->is($busadminRoutes . '/agency*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is($busadminRoutes . '/agency*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Agency Users
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- View All Users -->
                        @if (isset($access['view_user']) && $access['view_user'])
                                            <li class="nav-item">
                                                <a href="{{ route('agency.index') }}" class="nav-link {{ request()->is($busadminRoutes . '/agency') ||
                            request()->is($busadminRoutes . '/agency/view*')
                            ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>View All Users</p>
                                                </a>
                                            </li>
                        @endif

                        <!-- Add User -->
                        @if (isset($access['add_user']) && $access['add_user'])
                            <li class="nav-item">
                                <a href="{{ route('agency.create') }}"
                                    class="nav-link {{ request()->is($busadminRoutes . '/agency/create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add User</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif


                @if (isset($access['view_role']) && $access['view_role'])
                <li class="nav-item {{ request()->is($busadminRoutes . '/user-role-details*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is($busadminRoutes . '/user-role-details*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-key"></i>
                        <p>
                            User Roles
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (isset($access['view_role']) && $access['view_role'])
                                            <li class="nav-item">
                                                <a href="{{ route('user-role-details.index') }}" class="nav-link {{ request()->is($busadminRoutes . '/user-role-details') ||
                            request()->is($busadminRoutes . '/user-role-details/*')
                            ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>View All Agencies Role</p>
                                                </a>
                                            </li>
                        @endif
                    </ul>
                </li>
                @endif


                <!-- <li class="nav-item {{ request()->is($busadminRoutes . '/h*') ? 'menu-open' : '' }}">
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
                            <a href="{{ route('user-role-details.index') }}" class="nav-link {{ request()->is($busadminRoutes . '/h*') ||
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
 -->
                @if (isset($access['edit_profile']) && $access['edit_profile'])

                <li
                    class="nav-item {{ request()->is($busadminRoutes . '/settings*') || request()->is($busadminRoutes . '/profile*') || request()->is($busadminRoutes . '/logActivity*') || request()->is($busadminRoutes . '/agencies-profile') || request()->is($busadminRoutes . '/mail-settings') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is($busadminRoutes . '/settings*') || request()->is($busadminRoutes . '/profile*') || request()->is($busadminRoutes . '/logActivity*') || request()->is($busadminRoutes . '/agencies-profile') || request()->is($busadminRoutes . '/mail-settings') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
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

                        <li class="nav-item">
                            <a href="{{ route('masteradmin.profile.agencyedits') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/agencies-profile') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Agency Profile</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('masteradmin.mailsetting') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/mail-settings') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mail Configration</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('masteradmin.masterlog.index') }}"
                                class="nav-link {{ request()->is($busadminRoutes . '/logActivity*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Log Activity</p>
                            </a>
                        </li>

                    </ul>
                </li>
                @endif                             

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