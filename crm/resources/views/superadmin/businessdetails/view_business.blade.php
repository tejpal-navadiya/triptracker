<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trip Tracker | Company Detail</title>
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
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col">
                        <div class="d-flex"> 
                            <h1 class="m-0">Agency Details</h1>
                            <ol class="breadcrumb ml-auto">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">Agency Details</li>
                            </ol>
                            </div>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <!-- <ol class="breadcrumb float-sm-right">
              <a href="#" data-toggle="modal" data-target="#deletebusiness"><button class="add_btn_br"><i class="fas fa-solid fa-trash mr-2"></i>Delete</button></a>
              <a href="edit-business.html"><button class="add_btn_br"><i class="fas fa-solid fa-pen-to-square mr-2"></i>Edit</button></a>
            </ol> -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content px-10">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                @if(Session::has('link-success'))
                <p class="text-success" > {{ Session::get('link-success') }}</p>
                @endif
                @if(Session::has('link-error'))
                <p class="text-danger" > {{ Session::get('link-error') }}</p>
                @endif

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
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Agency Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <table class="table estimate_detail_table">
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Agency Name :</strong></td>
                                                        <td>{{ $userdetailss->users_agencies_name ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Host Of Franchise Name :</strong></td>
                                                        <td>{{ $userdetailss->users_franchise_name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Consortia Name :</strong></td>
                                                        <td>{{ $userdetailss->users_consortia_name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Name :</strong></td>
                                                        <td>{{ $userdetailss->users_first_name ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Personal Email :</strong></td>
                                                        <td>{{ $userdetailss->users_personal_email ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Business Phone :</strong></td>
                                                        <td>{{ $userdetailss->users_business_phone ?? ''}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-auto">
                                            <table class="table estimate_detail_table">
                                                <tbod>
                                                    
                                                    <tr>
                                                        <td><strong>IATA or CLIA Number :</strong></td>
                                                        <td>{{ $userdetailss->users_iata_clia_number ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Personal CLIA Number :</strong></td>
                                                        <td>{{ $userdetailss->users_clia_number ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Personal IATA Number :</strong></td>
                                                        <td>{{ $userdetailss->users_iata_number ?? '' }}</td>
                                                    </tr>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td><strong>Subscription Plan :</strong></td>
                                                        <td>{{ $user->plan ? $user->plan->sp_name : 'No Plan' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Address :</strong></td>
                                                        <td>
                                                            @if($userdetailss)
                                                                {{ $userdetailss->city_name ?? '' }}{{ $userdetailss->city_name && ($userdetailss->state_name || $userdetailss->country_name || $userdetailss->users_zip) ? ', ' : '' }}
                                                                {{ $userdetailss->state_name ?? '' }}{{ $userdetailss->state_name && ($userdetailss->country_name || $userdetailss->users_zip) ? ', ' : '' }}
                                                                {{ $userdetailss->country_name ?? '' }}{{ $userdetailss->country_name && $userdetailss->users_zip ? ' ' : '' }}
                                                                {{ $userdetailss->users_zip ?? '' }}
                                                            @else
                                                                <!-- Handle the case when $userdetailss is null -->
                                                            @endif
                                                        </td>

                                                      
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Business Email Address :</strong></td>
                                                        <td>{{ $userdetailss->users_email ?? ''}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-auto">
                                    <h3 class="card-title">Agency User</h3>
                                </div>
                                <!-- <div class="col-auto">
                                    <ol class="float-sm-right">
                                        <a href="{{ route('businessdetails.agencycreate', $user_id ) }}" id="createNew"><button class="add_btn"><i
                                        class="fas fa-plus add_plus_icon"></i>Agency User</button></a>
                                    </ol>
                                </div> -->
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body1">
                            <div class="col-md-12 table-responsive pad_table">
                                <table id="agencyUserList" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Personal Email Address</th>
                                            <th>Business Email Address</th>
                                            <th>Phone Number</th>
                                            <th>User Role</th>
                                            <th>Last Updated</th>
                                            <th>User Id</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <!-- <th class="sorting_disabled text-right" data-orderable="false">Actions</th> -->
                                        </tr>
                                    </thead>
                                    <!-- <input type="hidden" name='u_id' id="u_id" value="{{ $user->id }}"> -->
                                    <tbody>
                                        @foreach ($udetail as $detail)
                                            <tr>
                                                <td>{{ $detail->users_first_name ?? '' }}
                                                    {{ $detail->users_last_name ?? '' }}</td>

                                                <td>{{ $detail->users_email ?? '' }}</td>
                                                <td>{{ $detail->user_work_email ?? '' }}</td>
                                                <td>{{ $detail->users_phone ?? $detail->user_emergency_phone_number }}</td>
                                                <td>{{ $detail->role_name }}</td>
                                                <td>{{ $detail->updated_at }}</td>
                                                <td>{{ $detail->user_id }}</td>
                                                <td>
                                                    @if ($detail->users_status == 1)
                                                        <span class="status_btn converted_status"> Active </span>
                                                    @else
                                                        <span class="status_btn overdue_status">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <a data-toggle="modal" data-target="#deleteuser-{{ $detail->users_id }}"><i
                                                            class="fas fa-solid fa-trash delete_icon_grid"></i></a>
                                                </td>
                                                <div class="modal fade" id="deleteuser-{{ $detail->users_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                        <form id="delete-plan-form"
                                                        action="{{ route('superagency.destroy', ['id' => $detail->users_id, 'user_id' => $detail->user_id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                                <div class="modal-body pad-1 text-center">
                                                                    <i class="fas fa-solid fa-trash delete_icon"></i>
                                                                    <p class="company_business_name px-10"><b>Delete User</b></p>
                                                                    <p class="company_details_text px-10">Are You Sure You Want to Delete This User?</p>
                                                                    <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="delete_btn px-15">Delete</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
        <div class="modal fade" id="deletebusiness" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body pad-1 text-center">
                        <i class="fas fa-solid fa-trash delete_icon"></i>
                        <p class="company_business_name px-10"><b>Delete Business</b></p>
                        <p class="company_details_text px-10">Are You Sure You Want to Delete This Business?</p>
                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="delete_btn px-15">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- ./wrapper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
$(document).ready(function() {
    $('#agencyUserList').DataTable({
        dom: '<"mb-3"<l<fB>>>rt<"row"<i><p>>',
            buttons: [
                {
                    text: '<i class="fas fa-plus"></i> Agency User',
                    action: function (e, dt, node, config) {
                        window.location.href = "{{ route('businessdetails.agencycreate', $user_id) }}";
                    },
                    className: 'add_btn'
                }
            ],
    });
});
</script>



<style type="text/css">
#agencyUserList_length{ float: left; width: 50%; }

#agencyUserList_filter{ float: left; width: 30%; }
</style>
    @include('layouts.footerlink')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        document.getElementById('status-text').addEventListener('click', function() {
            const userId = this.getAttribute('data-id');

            fetch(`{{ url('/admin/business-detail') }}/${userId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const statusText = document.getElementById('status-text');

                        // Update the status text and class based on the new status
                        if (data.new_status == 1) {
                            statusText.innerHTML = '<span class="converted_status">Active</span>';
                        } else {
                            statusText.innerHTML = '<span class="overdue_status">Inactive</span>';
                        }
                    }
                });
        });
    </script>

</body>

</html>
