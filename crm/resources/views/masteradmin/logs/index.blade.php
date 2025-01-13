@extends('masteradmin.layouts.app')
<title>Log Activity | Trip Tracker</title>
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center">
          <div class="col">
          <div class="d-flex">    
            <h1 class="m-0">{{ __("Log Activity") }}</h1>
            <ol class="breadcrumb ml-auto">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
              <li class="breadcrumb-item active">Log Activity</li>
            </ol>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content px-10">
        <div class="container-fluid">
          
          <!-- Main row -->
          <div class="card px-20">
            <div class="card-body1">
              <div class="col-md-12 table-responsive pad_table">
                <table id="allLogDataTable" class="table table-hover text-nowrap">
                <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Url</th>
                                <th>Method</th>
                                <th>Ip</th>
                                <th>Browser</th>
                                <th>User Name</th>
                                <th>Date Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($logs) > 0)
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->subject }}</td>
                                        <td>{{ $log->url }}</td>
                                        <td>{{ $log->method }}</td>
                                        <td>{{ $log->ip }}</td>
                                        <td>{{ $log->agent }}</td>
                                        <td>{{ $log->users_first_name }} {{ $log->users_last_name }}</td>
                                        <td>{{ $log->created_at }}</td>
                                    </tr>
                                @endforeach    
                            @else
                                <tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No records found</td></tr>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
      $(document).ready(function() {
 $('#allLogDataTable').dataTable({
            order: [[1, 'asc']]
            });
          });
</script>
@endsection
