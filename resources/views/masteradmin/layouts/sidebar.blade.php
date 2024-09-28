<!-- Main Sidebar Container -->
@php($busadminRoutes = config('global.businessAdminURL'))

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('masteradmin.home') }}" class="brand-link">
      <img src="{{url('public/dist/img/logo.png')}}" alt="Profityo Logo" class="brand-image">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('masteradmin.home') }}" class="nav-link {{ request()->is($busadminRoutes.'/dashboard*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
          </li>
          
         
          <li class="nav-item {{ request()->is($busadminRoutes.'/profile*') 
                    ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon far fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-right right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item">
                <a href="{{ route('masteradmin.profile.edit') }}" class="nav-link {{ request()->is($busadminRoutes.'/profile*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profile</p>
                </a>
              </li>
            
              
              @if(isset($access['business_profile']) && $access['business_profile']) 
              <li class="nav-item">
                <a href="{{ route('masteradmin.business.edit') }}" class="nav-link {{ request()->is($busadminRoutes.'/business-profile*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Business Profile</p>
                </a>
              </li>
              @endif
              @if(isset($access['users']) && $access['users']) 
              <li class="nav-item">
                <a href="{{ route('masteradmin.userdetail.index') }}" class="nav-link {{ request()->is($busadminRoutes.'/userdetails*') || 
                             request()->is($busadminRoutes.'/usercreate*') || 
                             request()->is($busadminRoutes.'/useredit/*')  
                              ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              @endif
              @if(isset($access['roles']) && $access['roles'])  
              <li class="nav-item">
                <a href="{{ route('masteradmin.role.index') }}" class="nav-link {{ request()->is($busadminRoutes.'/user-role-details*') || 
                             request()->is($busadminRoutes.'/rolecreate*') || 
                             request()->is($busadminRoutes.'/roleedit/*')  ||
                             request()->is($busadminRoutes.'/userrole/*') 
                              ? 'active' : '' }} ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
              @endif
            
              <li class="nav-item">
                <a href="{{ route('masteradmin.masterlog.index') }}" class="nav-link {{ request()->is($busadminRoutes.'/logActivity*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Log Activity</p>
                </a>
              </li>
              
            </ul>
          </li>

          <li class="nav-item">
            <form id="logout-form" method="POST" action="{{ route('masteradmin.logout') }}" style="display: none;">
                @csrf
            </form>

            <a href="{{ route('masteradmin.logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
