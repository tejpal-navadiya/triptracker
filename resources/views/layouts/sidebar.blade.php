
@php($adminRoute = config('global.superAdminURL'))
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="{{url('public/dist/img/logo.png')}}" alt="Profityo Logo" class="brand-image">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item ">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is($adminRoute.'/dashboard*') || request()->is($adminRoute.'/profile*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Analytics</p>
            </a>
          </li>

          <li class="nav-item ">
            <a href="{{ route('businessdetails.index') }}" class="nav-link {{ request()->is($adminRoute.'/businessdetails*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-regular fa-building"></i>
              <p>Business</p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="{{ route('plans.index') }}" class="nav-link {{ request()->is($adminRoute.'/plans*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-trophy"></i>
                <p>Subscription Plans </p>
            </a>
          </li>
          
          <li class="nav-item ">
            <a href="{{ route('adminlog.index') }}" class="nav-link {{ request()->is($adminRoute.'/logActivity*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-trophy"></i>
                <p>Log Activity</p>
            </a>
          </li>
        
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>