<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trip Tracker | Add Agency</title>
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
                            <h1 class="m-0">Agency List</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">Agency</li>
                                <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Agency List</a>
                                </li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="float-sm-right">
                                    <a href="{{ route('businessdetails.agencyadd') }}" id="createNew"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add Agency</button></a>
                            </ol>
                        </div>

                    </div><!-- /.row -->

                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content px-10">
                <div class="container-fluid">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @php
                            Session::forget('success');
                        @endphp
                    @endif
                    @if (Session::has('businessdetails-delete'))
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
                    
                        @if(Session::has('link-success'))
                <p class="text-success" > {{ Session::get('link-success') }}</p>
                @endif
                @if(Session::has('link-error'))
                <p class="text-danger" > {{ Session::get('link-error') }}</p>
                @endif

                   

                    <!-- Main row -->
                    <div class="card px-20">
                        <div class="card-body1">
                            <div class="col-md-12 table-responsive pad_table">
                                <table id="agencyList" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Agency Name</th>
                                            <th>Name</th>
                                            <th>Email Address</th>
                                            <th>Subscription Plan</th>
                                            <!-- <th>Membership Plan</th> -->
                                            <!-- <th>IATA or CLIA Number</th> -->
                                            <!--<th>IATA or CLIA Number</th>-->
                                            <th>User id</th>
                                            <th>Total Users</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php //dd($businessdetails);
                                        ?>
                                        @if (count($MasterUser) > 0)
                                            @foreach ($MasterUser as $value)
                                                <tr>
                                                    <td>{{ $value->users_agencies_name }}</td>
                                                    <td>{{ $value->users_first_name . ' ' . $value->users_last_name }}
                                                    </td>
                                                    <td>{{ $value->users_email }}</td>
                                                    <td>{{ $value->plan ? $value->plan->sp_name : 'No Plan' }}</td>
                                                    <!-- <td>{{ $value->user_iata_clia_number ?? '' }}</td> -->
                                                    <!--<td>{{ $value->users_iata_number ?? '' }}</td>-->
                                                    <td>{{ $value->buss_unique_id ?? '' }}
                                                    <td>{{ $value->totalUserCount ?? 0 }}</td>

                                                    {{-- <td>
                                                        @if ($value->user_status == 1)
                                                            <span class="status_btn converted_status"> Active </span>
                                                        @else
                                                            <span class="status_btn overdue_status">Inactive</span>
                                                        @endif
                                                    </td> --}}
                                                    {{-- <!-- <td>{{ $value->user_status }}</td> --> --}}
                                                     <td>{{ $value->created_at }}</td>
                                                    {{-- <td>{{ $value->updated_at }}</td> --}}
                                                    <td>
                                                        <ul class="navbar-nav ml-auto float-sm-right">
                                                            <li class="nav-item dropdown d-flex align-items-center">
                                                                <a class="nav-link user_nav" data-toggle="dropdown"
                                                                    href="#">
                                                                    <span class="action_btn"><i
                                                                            class="fas fa-solid fa-chevron-down"></i></span>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a href="{{ route('businessdetails.show', $value->id) }}"
                                                                        class="dropdown-item">
                                                                        <i class="fas fa-regular fa-eye mr-2"></i> View
                                                                    </a>
                                                                    <!-- <a href="edit-business.html" class="dropdown-item">
                                      <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                    </a> -->
                                                                    <!-- <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deletebusiness">
                                      <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                    </a> -->


                                                                    <a href="{{ route('businessdetails.edit', $value->id) }}"
                                                                        class="dropdown-item">
                                                                        <i class="fas fa-regular fa-edit mr-2"></i> Edit
                                                                    </a>
                                                                    
                                                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deletebusiness-{{ $value->id }}">
                                                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                                                    </a>


                                                                </div>



                                                            </li>
                                                        </ul>
                                                        <div class="modal fade"
                                                            id="deletebusiness-{{ $value->id }}" tabindex="-1"
                                                            role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-sm modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">

                                                                    <form id="delete-plan-form"
                                                                        action="{{ route('businessdetails.destroy', ['id' => $value->id , "user_id" => $value->buss_unique_id]) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE') <!-- Spoofing DELETE method -->

                                                                        <div class="modal-body  pad-1 text-center">
                                                                            <i class="fas fa-solid fa-trash delete_icon"></i>
                                                                            <p class="company_business_name px-10"> <b>Delete
                                                                            Agency</b></p>
                                                                            <p class="company_details_text">Are You Sure You
                                                                                Want to Delete This Agency?</p>
                                                                            <button type="button" class="add_btn px-15"
                                                                                data-dismiss="modal">Cancel</button>
                                                                            <button type="submit"
                                                                                class="delete_btn px-15">Delete</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
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

<script>
$(document).ready(function() {
    $('#agencyList').DataTable({
        order: [[6, 'desc']],  // Sort by 'Created Date' column (7th column, index 6)
    });
});
</script>

</body>

</html>
