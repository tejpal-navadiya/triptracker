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
        
        <!-- Main row -->
        <div id="filter_data">
            <div class="card px-20">
                <div class="card-body1">
                    <div class="col-md-12 table-responsive pad_table">
                        <table id="completedDatatable" class="table table-hover text-nowrap data-table">
                            <thead>
                                <tr>
                                    <th>Trip Name</th>
                                    <th>Agent Name</th>
                                    <th>Traveler Name</th>
                                    <th>Start to End Date</th>
                                    <th>Trip After Complete Days</th>
                                    <th class="sorting_disabled">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($tripCompleted as $comvalue)
                                <tr>
                                    <td>{{ $comvalue->tr_name ?? '' }}</td>
                                    <td>{{ $comvalue->users_first_name ?? ''}} {{$comvalue->users_last_name ?? '' }}</td>
                                    <td>{{ $comvalue->tr_traveler_name ?? ''}}</td>
                                    <td>{{ \Carbon\Carbon::parse($comvalue->tr_start_date ?? '')->format('M d, Y') }} - {{ \Carbon\Carbon::parse($comvalue->tr_end_date ?? '')->format('M d, Y') }}</td>
                                    <td>
                                    <?php 
                                     $currentDate = \Carbon\Carbon::now()->startOfDay(); 
                                     $endDate = $comvalue->tr_end_date ?? '0';
                                 
                                     // Check if end date exists
                                     if (!$endDate) {
                                        //  echo '0';
                                     }
                                 
                                     // Parse the end date and ensure it's in the correct format
                                     try {
                                         $endDateParsed = \Carbon\Carbon::createFromFormat('m/d/Y', $endDate)->startOfDay();
                                     } catch (\Exception $e) {
                                        //  echo '0'; // Return empty if the date format is invalid
                                     }
                                 
                                     // Calculate days since completion (positive if in the past, 0 if today, negative if future)
                                     $daysSinceCompletion = $endDateParsed->lt($currentDate) 
                                         ? $endDateParsed->diffInDays($currentDate) 
                                         : 0;
                                 
                                     echo $daysSinceCompletion.' days';
                                    ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (isset($comvalue->trip_status) && $comvalue->trip_status->tr_status_name == 'Trip Completed') {
                                            $buttonColor = '#F6A96D';
                                        } else {
                                            $buttonColor = '';
                                        }

                                        // Check if trip_status is not null and then access tr_status_name
                                        $statusName = $comvalue->trip_status->tr_status_name ?? ''; 

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //datatable list
        var allTable = $('#completedDatatable').DataTable();
        allTable.destroy();
        function reloadcompleted()
        {
             allTable = $('#completedDatatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('masteradmin.trip.follow_up_complete_trip') }}",
                        type: 'GET',
                        data: function(d) {
                            d.trip_agent = $('#trip_agent').val(); 
                            d.trip_traveler = $('#trip_traveler').val(); 
                            d._token = '{{ csrf_token() }}';
                        }
                    },
                    columns: [
                        { data: 'tr_name', name: 'tr_name' },
                        { data: 'agent_name', name: 'agent_name' },
                        { data: 'tr_traveler_name', name: 'tr_traveler_name' },
                        { data: 'trip_date', name: 'trip_date' },
                        { data: 'complete_days', name: 'complete_days' },
                        { data: 'task_status_name', name: 'task_status_name', orderable: false, searchable: false }, // Button column

                    ]
                });
        }
        //list
     

        $('#trip_agent, #trip_traveler').on('change', function() {
            // allTable.draw();
            reloadcompleted();
        });

        $('.filter-text').on('click', function() {
            $('#trip_agent').val('').trigger('change'); 
            $('#trip_traveler').val('').trigger('change');
            reloadcompleted();
        });

    });
</script>


