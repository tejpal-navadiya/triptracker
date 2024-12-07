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

                                        // Initialize $endDateParsed with a default value
                                        $endDateParsed = null;

                                        // Check if end date exists
                                        if ($endDate && $endDate !== '0') {
                                            // Parse the end date and ensure it's in the correct format
                                            try {
                                                $endDateParsed = \Carbon\Carbon::createFromFormat('m/d/Y', $endDate)->startOfDay();
                                            } catch (\Exception $e) {
                                                $endDateParsed = null; // Ensure $endDateParsed is always defined
                                            }
                                        }

                                        // Check if $endDateParsed is not null before proceeding
                                        if ($endDateParsed && $endDateParsed->lt($currentDate)) {
                                            $daysSinceCompletion = $endDateParsed->diffInDays($currentDate);
                                        } else {
                                            $daysSinceCompletion = 0; // Default to 0 days if no valid end date
                                        }

                                        echo $daysSinceCompletion . ' days';

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
