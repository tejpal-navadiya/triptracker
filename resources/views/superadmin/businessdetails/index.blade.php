<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profityo | View All Business</title>
  @include('layouts.headerlink')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    @include('layouts.navigation')
    @include('layouts.sidebar')


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
              <h1 class="m-0">Company</h1>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Company</li>
              </ol>
            </div><!-- /.col -->
            
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <!-- Main content -->
      <section class="content px-10">
        <div class="container-fluid">
          @if(Session::has('businessdetails-add'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ Session::get('businessdetails-add') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @php
              Session::forget('businessdetails-add');
            @endphp
          @endif
          @if(Session::has('businessdetails-delete'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ Session::get('businessdetails-delete') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @php
              Session::forget('businessdetails-delete');
            @endphp
          @endif

          <!-- Main row -->
          <div class="card px-20">
            <div class="card-body1">
              <div class="col-md-12 table-responsive pad_table">
                <table id="example1" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Company Name</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <!-- <th>Membership Plan</th> -->
                      <th>Total Users</th>
                      <th>Status</th>
                      <!-- <th>Created Date</th> -->
                      <th>Last Updated Date</th>
                      <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php //dd($businessdetails); ?>
                    @if (count($MasterUser) > 0)
                        @foreach ($MasterUser as $value)
                          <tr>
                            <td>{{ $value->user_business_name }}</td>
                            <td>{{ $value->user_first_name }}</td>
                            <td>{{ $value->user_email }}</td>
                            <td>{{ $value->user_phone }}</td>
                            <!-- <td>{{ $value->plan ? $value->plan->sp_name : 'No Plan' }}</td> -->
                            <td>{{ $value->totalUserCount  }}</td>
                            <td>  
                              @if ($value->user_status == 1)
                              <span class="status_btn converted_status"> Active </span>
                              @else
                              <span class="status_btn overdue_status">Inactive</span>
                               @endif
                           </td>
                      <!-- <td>{{ $value->user_status }}</td> -->
                            <!-- <td>{{ $value->created_at }}</td> -->
                            <td>{{ $value->updated_at }}</td>
                            <td>
                              <ul class="navbar-nav ml-auto float-sm-right">
                                <li class="nav-item dropdown d-flex align-items-center">
                                  <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                    <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                  </a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('businessdetails.show', $value->id) }}" class="dropdown-item">
                                      <i class="fas fa-regular fa-eye mr-2"></i> View
                                    </a>
                                    <!-- <a href="edit-business.html" class="dropdown-item">
                                      <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                    </a> -->
                                    <!-- <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deletebusiness">
                                      <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                    </a> -->
                                  </div>
                                </li>
                              </ul>
                           </td>
                          </tr>
                   
                        @endforeach
                      @else
                    <tr>
                      <th>No Data found</th>
                    </tr>
                  @endif
                  </tbody>
                </table>
              </div>
            </div><!-- /.card-body -->
          </div><!-- /.card-->
          <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    
  </div>
  <!-- ./wrapper -->

  @include('layouts.footerlink')

</body>

</html>