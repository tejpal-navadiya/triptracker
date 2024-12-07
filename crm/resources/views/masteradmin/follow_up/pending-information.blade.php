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
        <!-- <input type="hidden" name="inProcessTrips" id="inProcessTrips" value="inProcessTrips" > -->
        <!-- Main row -->
        <div id="filter_data">
            <div class="card px-20">
                <div class="card-body1">
                    <div class="col-md-12 table-responsive pad_table">
                        <table id="pendingDataTable" class="table table-hover text-nowrap data-table">
                            <thead>
                                <tr>
                                    <th>Trip Name</th>
                                    <th>Agent Name</th>
                                    <th>Traveler Name</th>
                                    <th>Start Date</th>
                                    <!-- <th>Trip Due Days</th> -->
                                    <th class="sorting_disabled">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($trip as $value)
                                <tr>
                                    <td>{{ $value->tr_name ?? '' }}</td>
                                    <td>{{ $value->users_first_name ?? ''}} {{$value->users_last_name ?? '' }}</td>
                                    <td>{{ $value->tr_traveler_name ?? ''}}</td>
                                    <td>{{ \Carbon\Carbon::parse($value->tr_start_date ?? '')->format('M d, Y') }}</td>
                                    <td>
                                        <?php 
                                        if (isset($value->trip_status) && $value->trip_status->tr_status_name == 'In Process') {
                                            $buttonColor = '#F6A96D';
                                        } else {
                                            $buttonColor = '';
                                        }

                                        // Check if trip_status is not null and then access tr_status_name
                                        $statusName = $value->trip_status->tr_status_name ?? ''; 

                                        echo '<button type="button" class="btn text-white" style="background-color: ' . $buttonColor . ';">' . $statusName . '</button>';
                                        ?>
                                    </td>

                                </tr>
                            @endforeach

                              
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card-->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
