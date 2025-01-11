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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.844 20H6.5C5.121 20 4 18.879 4 17.5C4 16.121 5.121 15 6.5 15H13.5C15.43 15 17 13.43 17 11.5C17 9.57 15.43 8 13.5 8H8.639C8.27175 8.71988 7.81697 9.39164 7.285 10H13.5C14.327 10 15 10.673 15 11.5C15 12.327 14.327 13 13.5 13H6.5C4.019 13 2 15.019 2 17.5C2 19.981 4.019 22 6.5 22H16.093C15.6023 21.3828 15.1833 20.7118 14.844 20ZM5 2C3.346 2 2 3.346 2 5C2 8.188 5 10 5 10C5 10 8 8.187 8 5C8 3.346 6.654 2 5 2ZM5 6.5C4.80295 6.49993 4.60785 6.46106 4.42582 6.38559C4.2438 6.31012 4.07842 6.19954 3.93913 6.06016C3.79984 5.92078 3.68937 5.75533 3.61403 5.57325C3.53868 5.39118 3.49993 5.19605 3.5 4.999C3.50007 4.80195 3.53894 4.60685 3.61441 4.42482C3.68988 4.2428 3.80046 4.07742 3.93984 3.93813C4.07922 3.79884 4.24467 3.68837 4.42675 3.61303C4.60882 3.53768 4.80395 3.49893 5.001 3.499C5.39896 3.49913 5.78056 3.65735 6.06187 3.93884C6.34317 4.22033 6.50113 4.60204 6.501 5C6.50087 5.39796 6.34265 5.77956 6.06116 6.06087C5.77967 6.34217 5.39796 6.50013 5 6.5Z" fill="#384150"/>
                                <path d="M19 14C17.346 14 16 15.346 16 17C16 20.188 19 22 19 22C19 22 22 20.187 22 17C22 15.346 20.654 14 19 14ZM19 18.5C18.803 18.4999 18.6078 18.4611 18.4258 18.3856C18.2438 18.3101 18.0784 18.1995 17.9391 18.0602C17.7998 17.9208 17.6894 17.7553 17.614 17.5733C17.5387 17.3912 17.4999 17.196 17.5 16.999C17.5001 16.802 17.5389 16.6068 17.6144 16.4248C17.6899 16.2428 17.8005 16.0774 17.9398 15.9381C18.0792 15.7988 18.2447 15.6884 18.4267 15.613C18.6088 15.5377 18.804 15.4989 19.001 15.499C19.399 15.4991 19.7806 15.6573 20.0619 15.9388C20.3432 16.2203 20.5011 16.602 20.501 17C20.5009 17.398 20.3427 17.7796 20.0612 18.0609C19.7797 18.3422 19.398 18.5001 19 18.5Z" fill="#384150"/>
                                </svg>
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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.5 9V4H20V9H13.5ZM4 12V4H10.5V12H4ZM13.5 20V12H20V20H13.5ZM4 20V15H10.5V20H4Z" fill="#384150"/>
                        </svg>

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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M6 21V19C6 17.9391 6.42143 16.9217 7.17157 16.1716C7.92172 15.4214 8.93913 15 10 15H14C15.0609 15 16.0783 15.4214 16.8284 16.1716C17.5786 16.9217 18 17.9391 18 19V21M8 7C8 8.06087 8.42143 9.07828 9.17157 9.82843C9.92172 10.5786 10.9391 11 12 11C13.0609 11 14.0783 10.5786 14.8284 9.82843C15.5786 9.07828 16 8.06087 16 7C16 5.93913 15.5786 4.92172 14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7Z" stroke="#384150" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15.9998 3V5.00016H18M15.9998 3L18 5.00016M15.9998 3H6.99984C6.73467 3 6.48035 3.10534 6.29285 3.29285C6.10534 3.48035 6 3.73467 6 3.99984V11.0002L11.4499 14.6304C11.6131 14.7382 11.8044 14.7956 12 14.7956C12.1956 14.7956 12.3869 14.7382 12.5501 14.6304L18 10.9997V5.00016" stroke="#384150" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12.5501 14.6305L19.4501 9.99995C19.6005 9.90085 19.7749 9.84422 19.9548 9.83605C20.1348 9.82788 20.3136 9.86848 20.4724 9.95354C20.6311 10.0386 20.764 10.165 20.8569 10.3193C20.9497 10.4737 20.9992 10.6502 21 10.8303V20.0003C21 20.2654 20.8947 20.5198 20.7072 20.7073C20.5197 20.8948 20.2653 21.0001 20.0002 21.0001H3.99985C3.73467 21.0001 3.48036 20.8948 3.29285 20.7073C3.10535 20.5198 3.00001 20.2654 3.00001 20.0003V10.8702C2.99356 10.6867 3.03773 10.5049 3.12771 10.3449C3.21768 10.1848 3.34997 10.0526 3.51009 9.96274C3.6702 9.87286 3.85197 9.82879 4.03547 9.83536C4.21897 9.84192 4.39712 9.89886 4.55041 9.99995L11.4499 14.5902C11.6092 14.7039 11.7984 14.7684 11.994 14.7754C12.1896 14.7825 12.383 14.7324 12.5501 14.6305Z" stroke="#384150" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.587 21.9V20.174C9.78904 19.9726 8.24626 19.2284 6.95864 17.9414C5.67103 16.6543 4.92682 15.1115 4.72603 13.313H3V11.587H4.72603C4.9274 9.78904 5.67189 8.24626 6.95951 6.95864C8.24712 5.67103 9.78962 4.92682 11.587 4.72603V3H13.313V4.72603C15.111 4.9274 16.6537 5.67189 17.9414 6.95951C19.229 8.24712 19.9732 9.78962 20.174 11.587H21.9V13.313H20.174C19.9726 15.111 19.2284 16.6537 17.9414 17.9414C16.6543 19.229 15.1115 19.9732 13.313 20.174V21.9H11.587ZM12.45 18.4911C14.1185 18.4911 15.5425 17.9014 16.7219 16.7219C17.9014 15.5425 18.4911 14.1185 18.4911 12.45C18.4911 10.7815 17.9014 9.35753 16.7219 8.17808C15.5425 6.99863 14.1185 6.4089 12.45 6.4089C10.7815 6.4089 9.35753 6.99863 8.17808 8.17808C6.99863 9.35753 6.4089 10.7815 6.4089 12.45C6.4089 14.1185 6.99863 15.5425 8.17808 16.7219C9.35753 17.9014 10.7815 18.4911 12.45 18.4911ZM12.45 15.9021C11.5007 15.9021 10.688 15.564 10.012 14.888C9.33596 14.212 8.99794 13.3993 8.99794 12.45C8.99794 11.5007 9.33596 10.688 10.012 10.012C10.688 9.33596 11.5007 8.99794 12.45 8.99794C13.3993 8.99794 14.212 9.33596 14.888 10.012C15.564 10.688 15.9021 11.5007 15.9021 12.45C15.9021 13.3993 15.564 14.212 14.888 14.888C14.212 15.564 13.3993 15.9021 12.45 15.9021ZM12.45 14.176C12.9247 14.176 13.3311 14.0072 13.6694 13.6694C14.0077 13.3317 14.1766 12.9252 14.176 12.45C14.1755 11.9748 14.0066 11.5686 13.6694 11.2314C13.3323 10.8943 12.9258 10.7251 12.45 10.724C11.9742 10.7228 11.568 10.892 11.2314 11.2314C10.8948 11.5709 10.7257 11.9771 10.724 12.45C10.7222 12.9229 10.8914 13.3294 11.2314 13.6694C11.5715 14.0095 11.9776 14.1783 12.45 14.176Z" fill="#384150"/>
</svg>

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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M3 21V19C3 17.9391 3.42143 16.9217 4.17157 16.1716C4.92172 15.4214 5.93913 15 7 15H11C12.0609 15 13.0783 15.4214 13.8284 16.1716C14.5786 16.9217 15 17.9391 15 19V21M16 3.13C16.8604 3.3503 17.623 3.8507 18.1676 4.55231C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88M21 21V19C20.9949 18.1172 20.6979 17.2608 20.1553 16.5644C19.6126 15.868 18.8548 15.3707 18 15.15M5 7C5 8.06087 5.42143 9.07828 6.17157 9.82843C6.92172 10.5786 7.93913 11 9 11C10.0609 11 11.0783 10.5786 11.8284 9.82843C12.5786 9.07828 13 8.06087 13 7C13 5.93913 12.5786 4.92172 11.8284 4.17157C11.0783 3.42143 10.0609 3 9 3C7.93913 3 6.92172 3.42143 6.17157 4.17157C5.42143 4.92172 5 5.93913 5 7Z" stroke="#384150" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M21 16.8252V21H16.5V18.75H14.25V16.5H12V14.6807C11.5664 14.9092 11.1123 15.082 10.6377 15.1992C10.1631 15.3164 9.67969 15.375 9.1875 15.375C8.61914 15.375 8.07129 15.3018 7.54395 15.1553C7.0166 15.0088 6.52441 14.8008 6.06738 14.5312C5.61035 14.2617 5.19434 13.9395 4.81934 13.5645C4.44434 13.1895 4.11914 12.7705 3.84375 12.3076C3.56836 11.8447 3.36035 11.3525 3.21973 10.8311C3.0791 10.3096 3.00586 9.76172 3 9.1875C3 8.61914 3.07324 8.07129 3.21973 7.54395C3.36621 7.0166 3.57422 6.52441 3.84375 6.06738C4.11328 5.61035 4.43555 5.19434 4.81055 4.81934C5.18555 4.44434 5.60449 4.11914 6.06738 3.84375C6.53027 3.56836 7.02246 3.36035 7.54395 3.21973C8.06543 3.0791 8.61328 3.00586 9.1875 3C9.75586 3 10.3037 3.07324 10.8311 3.21973C11.3584 3.36621 11.8506 3.57422 12.3076 3.84375C12.7646 4.11328 13.1807 4.43555 13.5557 4.81055C13.9307 5.18555 14.2559 5.60449 14.5312 6.06738C14.8066 6.53027 15.0146 7.02246 15.1553 7.54395C15.2959 8.06543 15.3691 8.61328 15.375 9.1875C15.375 9.48633 15.3516 9.78223 15.3047 10.0752C15.2578 10.3682 15.1904 10.6553 15.1025 10.9365L21 16.8252ZM19.875 17.2998L13.8105 11.2354C13.9395 10.9014 14.0449 10.5674 14.127 10.2334C14.209 9.89941 14.25 9.55078 14.25 9.1875C14.25 8.49023 14.1182 7.83398 13.8545 7.21875C13.5908 6.60352 13.2275 6.06738 12.7646 5.61035C12.3018 5.15332 11.7656 4.79297 11.1562 4.5293C10.5469 4.26562 9.89062 4.13086 9.1875 4.125C8.49023 4.125 7.83398 4.25684 7.21875 4.52051C6.60352 4.78418 6.06738 5.14746 5.61035 5.61035C5.15332 6.07324 4.79297 6.60938 4.5293 7.21875C4.26562 7.82812 4.13086 8.48438 4.125 9.1875C4.125 9.88477 4.25684 10.541 4.52051 11.1562C4.78418 11.7715 5.14746 12.3076 5.61035 12.7646C6.07324 13.2217 6.60938 13.582 7.21875 13.8457C7.82812 14.1094 8.48438 14.2441 9.1875 14.25C9.75586 14.25 10.3125 14.1533 10.8574 13.96C11.4023 13.7666 11.8975 13.4883 12.3428 13.125H13.125V15.375H15.375V17.625H17.625V19.875H19.875V17.2998ZM7.5 6.375C7.6582 6.375 7.80469 6.4043 7.93945 6.46289C8.07422 6.52148 8.19141 6.60059 8.29102 6.7002C8.39062 6.7998 8.47266 6.91992 8.53711 7.06055C8.60156 7.20117 8.63086 7.34766 8.625 7.5C8.625 7.6582 8.5957 7.80469 8.53711 7.93945C8.47852 8.07422 8.39941 8.19141 8.2998 8.29102C8.2002 8.39062 8.08008 8.47266 7.93945 8.53711C7.79883 8.60156 7.65234 8.63086 7.5 8.625C7.3418 8.625 7.19531 8.5957 7.06055 8.53711C6.92578 8.47852 6.80859 8.39941 6.70898 8.2998C6.60938 8.2002 6.52734 8.08008 6.46289 7.93945C6.39844 7.79883 6.36914 7.65234 6.375 7.5C6.375 7.3418 6.4043 7.19531 6.46289 7.06055C6.52148 6.92578 6.60059 6.80859 6.7002 6.70898C6.7998 6.60938 6.91992 6.52734 7.06055 6.46289C7.20117 6.39844 7.34766 6.36914 7.5 6.375Z" fill="#384150"/>
</svg>

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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M19.9 12.6599C19.7397 12.4774 19.6513 12.2428 19.6513 11.9999C19.6513 11.757 19.7397 11.5224 19.9 11.3399L21.18 9.89989C21.321 9.74256 21.4086 9.5446 21.4302 9.3344C21.4517 9.12421 21.4062 8.91258 21.3 8.72989L19.3 5.2699C19.1949 5.08742 19.0349 4.94277 18.8427 4.85658C18.6506 4.77039 18.4362 4.74705 18.23 4.7899L16.35 5.1699C16.1108 5.21932 15.8617 5.17948 15.6499 5.0579C15.438 4.93631 15.278 4.74138 15.2 4.5099L14.59 2.6799C14.5229 2.48127 14.3951 2.30876 14.2246 2.18674C14.0541 2.06471 13.8496 1.99935 13.64 1.9999H9.63999C9.42192 1.98851 9.20612 2.04882 9.02555 2.17161C8.84498 2.2944 8.70957 2.47291 8.63999 2.6799L8.07999 4.5099C8.00199 4.74138 7.84196 4.93631 7.6301 5.0579C7.41824 5.17948 7.1692 5.21932 6.92999 5.1699L4.99999 4.7899C4.80454 4.76228 4.60529 4.79312 4.42734 4.87853C4.24938 4.96395 4.10069 5.10012 3.99999 5.2699L1.99999 8.72989C1.89115 8.91054 1.84221 9.12098 1.86017 9.33112C1.87813 9.54126 1.96207 9.74034 2.09999 9.89989L3.36999 11.3399C3.53031 11.5224 3.61872 11.757 3.61872 11.9999C3.61872 12.2428 3.53031 12.4774 3.36999 12.6599L2.09999 14.0999C1.96207 14.2595 1.87813 14.4585 1.86017 14.6687C1.84221 14.8788 1.89115 15.0892 1.99999 15.2699L3.99999 18.7299C4.10509 18.9124 4.26511 19.057 4.45724 19.1432C4.64937 19.2294 4.86381 19.2527 5.06999 19.2099L6.94999 18.8299C7.1892 18.7805 7.43824 18.8203 7.6501 18.9419C7.86196 19.0635 8.02199 19.2584 8.09999 19.4899L8.70999 21.3199C8.77957 21.5269 8.91498 21.7054 9.09555 21.8282C9.27612 21.951 9.49192 22.0113 9.70999 21.9999H13.71C13.9196 22.0004 14.1241 21.9351 14.2946 21.8131C14.4651 21.691 14.5929 21.5185 14.66 21.3199L15.27 19.4899C15.348 19.2584 15.508 19.0635 15.7199 18.9419C15.9317 18.8203 16.1808 18.7805 16.42 18.8299L18.3 19.2099C18.5062 19.2527 18.7206 19.2294 18.9127 19.1432C19.1049 19.057 19.2649 18.9124 19.37 18.7299L21.37 15.2699C21.4762 15.0872 21.5217 14.8756 21.5002 14.6654C21.4786 14.4552 21.391 14.2572 21.25 14.0999L19.9 12.6599ZM18.41 13.9999L19.21 14.8999L17.93 17.1199L16.75 16.8799C16.0298 16.7327 15.2806 16.855 14.6446 17.2237C14.0086 17.5924 13.5301 18.1817 13.3 18.8799L12.92 19.9999H10.36L9.99999 18.8599C9.76984 18.1617 9.29137 17.5724 8.65539 17.2037C8.01942 16.835 7.27021 16.7127 6.54999 16.8599L5.36999 17.0999L4.06999 14.8899L4.86999 13.9899C5.36194 13.4399 5.63392 12.7278 5.63392 11.9899C5.63392 11.252 5.36194 10.5399 4.86999 9.98989L4.06999 9.0899L5.34999 6.88989L6.52999 7.1299C7.25021 7.27712 7.99942 7.15478 8.63539 6.78609C9.27137 6.41741 9.74984 5.82805 9.97999 5.1299L10.36 3.9999H12.92L13.3 5.13989C13.5301 5.83805 14.0086 6.42741 14.6446 6.79609C15.2806 7.16478 16.0298 7.28712 16.75 7.13989L17.93 6.8999L19.21 9.11989L18.41 10.0199C17.9236 10.5687 17.655 11.2766 17.655 12.0099C17.655 12.7432 17.9236 13.4511 18.41 13.9999ZM11.64 7.9999C10.8489 7.9999 10.0755 8.23449 9.41771 8.67402C8.75991 9.11354 8.24722 9.73826 7.94447 10.4692C7.64172 11.2001 7.56251 12.0043 7.71685 12.7803C7.87119 13.5562 8.25215 14.2689 8.81156 14.8283C9.37097 15.3877 10.0837 15.7687 10.8596 15.923C11.6356 16.0774 12.4398 15.9982 13.1707 15.6954C13.9016 15.3927 14.5263 14.88 14.9659 14.2222C15.4054 13.5644 15.64 12.791 15.64 11.9999C15.64 10.939 15.2186 9.92161 14.4684 9.17147C13.7183 8.42132 12.7009 7.9999 11.64 7.9999ZM11.64 13.9999C11.2444 13.9999 10.8577 13.8826 10.5288 13.6628C10.1999 13.4431 9.9436 13.1307 9.79223 12.7653C9.64085 12.3998 9.60125 11.9977 9.67842 11.6097C9.75559 11.2218 9.94607 10.8654 10.2258 10.5857C10.5055 10.306 10.8618 10.1155 11.2498 10.0383C11.6378 9.96115 12.0399 10.0008 12.4054 10.1521C12.7708 10.3035 13.0832 10.5599 13.3029 10.8888C13.5227 11.2177 13.64 11.6043 13.64 11.9999C13.64 12.5303 13.4293 13.039 13.0542 13.4141C12.6791 13.7892 12.1704 13.9999 11.64 13.9999Z" fill="#384150"/>
</svg>

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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.02301 5.5C5.4122 6.56898 4.18841 8.12823 3.53281 9.94691C2.87722 11.7656 2.82467 13.7471 3.38294 15.5979C3.94121 17.4488 5.08063 19.0707 6.63252 20.2236C8.18441 21.3765 10.0663 21.999 11.9995 21.999C13.9328 21.999 15.8146 21.3765 17.3665 20.2236C18.9184 19.0707 20.0578 17.4488 20.6161 15.5979C21.1744 13.7471 21.1218 11.7656 20.4662 9.94691C19.8106 8.12823 18.5868 6.56898 16.976 5.5M12 2V10" stroke="#384150" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>