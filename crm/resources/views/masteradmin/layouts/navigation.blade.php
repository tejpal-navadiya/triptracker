@php($busadminRoute = config('global.businessAdminURL'))
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
       
    </ul>
    &nbsp;&nbsp;
    <span class="">  {{ Auth::guard('masteradmins')->user()->users_first_name }}
    {{ Auth::guard('masteradmins')->user()->users_last_name }} 
    ({{ Auth::guard('masteradmins')->user()->role_id == 0 ? 'Master Admin' : 'Agency User' }})
</span>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                <!-- <img src="{{ url('public/dist/img/user2-160x160.jpg') }}" class="elevation-2 user_img" alt="User Image"> -->
                <?php //dd(Auth::guard('masteradmins')->user());
                ?>
                @if (Auth::guard('masteradmins')->user()->users_image)
                    <img src="{{ route('agencys.access', ['filename' => Auth::guard('masteradmins')->user()->users_image]) }}"
                        class="elevation-2 user_img" target="_blank">
                @else
                    <img src="{{ url('public/dist/img/user2-160x160.jpg') }}" class="elevation-2 user_img"
                        alt="User Image">
                @endif
                <span class="d-block dropdown-toggle">{{ Auth::guard('masteradmins')->user()->users_first_name }}
                    {{ Auth::guard('masteradmins')->user()->users_last_name }} </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <x-dropdown-link :href="route('masteradmin.profile.edit')">
                    <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
                </x-dropdown-link>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('masteradmin.logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('masteradmin.logout')"
                        onclick="event.preventDefault();
                                this.closest('form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
                <!-- <a href="login.html" class="dropdown-item">
            <i class="nav-icon fas fa-sign-out-alt mr-2"></i> Log Out
          </a> -->
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
