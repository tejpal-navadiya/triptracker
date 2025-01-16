<section class="content">
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
            <div class="card ">
                <div class="card-body1">
                    <div class="col-md-12 table-responsive pad_table">
                        <table id="oneYearDatatable" class="table table-hover text-nowrap data-table">
                            <thead>
                                <tr>
                                    <th>Trip Name</th>
                                    <th>Agent Name</th>
                                    <th>Traveler Name</th>
                                    <th>Trip Number</th>
                                    <th>Start to End Date</th>
                                    <th>Trip After Complete Days</th>
                                    <th class="sorting_disabled">Status</th>
                                    <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($oneYearFollowUp as $yearvalue)
                                <tr>
                                    <td>{{ $yearvalue->tr_name ?? '' }}</td>
                                    <td>{{ $yearvalue->users_first_name ?? ''}} {{$yearvalue->users_last_name ?? '' }}</td>
                                    <td>{{ $yearvalue->trtm_first_name ?? ''}}</td>
                                    <td>{{ $yearvalue->tr_number ?? ''}}</td>
                                    <td>{{ \Carbon\Carbon::parse($yearvalue->tr_start_date ?? '')->format('M d, Y') }} - {{ \Carbon\Carbon::parse($yearvalue->tr_end_date ?? '')->format('M d, Y') }}</td>
                                    <td>
                                    <?php 

                                        $currentDate = \Carbon\Carbon::now()->startOfDay(); // Get today's date at midnight
                                        $endDate = $yearvalue->tr_end_date ?? '0'; // End date from your data

                                        // Initialize $endDateParsed with a default value
                                        $endDateParsed = null;

                                        // Check if end date exists
                                        if ($endDate && $endDate !== '0') {
                                            // Parse the end date and ensure it's in the correct format
                                            try {
                                                // Parse the end date from the given format (m/d/Y)
                                                $endDateParsed = \Carbon\Carbon::createFromFormat('m/d/Y', $endDate)->startOfDay();
                                            } catch (\Exception $e) {
                                                $endDateParsed = null; // Ensure $endDateParsed is always defined
                                            }
                                        }

                                        // Check if $endDateParsed is not null before proceeding
                                        if ($endDateParsed) {
                                            // Check if the end date is in the past or future
                                            if ($endDateParsed->lt($currentDate)) {
                                                // If end date is in the past, calculate how many days ago it was
                                                $daysSinceCompletion = $endDateParsed->diffInDays($currentDate);
                                                echo $daysSinceCompletion . ' days'; // This is how many days since the completion
                                            } else {
                                                // If end date is in the future, calculate how many days until the end date
                                                $daysUntilCompletion = $currentDate->diffInDays($endDateParsed);
                                                echo $daysUntilCompletion . ' days'; // This is how many days until completion
                                            }
                                        } else {
                                            echo 'No valid end date'; // If no valid end date is available
                                        }

                                        ?>

                                    </td>
                                    <td>
                                        @php
                                            $statusName = $yearvalue->trip_status->tr_status_name ?? '';

                                            $buttonColor = match (strtolower($statusName)) {
                                                'trip request' => '#DB9ACA',
                                                'trip proposal' => '#F6A96D',
                                                'trip modification' => '#FBC11E',
                                                'trip accepted' => '#28C76F',
                                                'trip sold' => '#C5A070',
                                                'trip lost' => '#F56B62',
                                                'trip completed' => '#F56B62',
                                                'trip pending' => '#F6A96D',
                                                'in process' => '#F6A96D',
                                            };
                                        @endphp

                                        <button type="button" class="btn text-white"
                                            style="background-color: {{ $buttonColor }};">
                                            {{ $statusName }}
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{ route('trip.view', $yearvalue->tr_id) }}"><i
                                                class="fas fa-eye edit_icon_grid"></i></a>
                                        <a href="{{ route('trip.edit', $yearvalue->tr_id) }}"><i
                                                class="fas fa-pen edit_icon_grid"></i></a>
                                        <a data-toggle="modal"
                                            data-target="#delete-product-modal-{{ $yearvalue->tr_id }}"><i
                                                class="fas fa-trash delete_icon_grid"></i></a>


                                        <div class="modal fade"
                                            id="delete-product-modal-{{ $yearvalue->tr_id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered"
                                                role="document">
                                                <div class="modal-content">
                                                    <form id="delete-plan-form"
                                                        action="{{ route('trip.destroy', $yearvalue->tr_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body  pad-1 text-center">
                                                            <i class="fas fa-solid fa-trash delete_icon"></i>
                                                            <p class="company_business_name px-10"> <b>Delete Trip</b></p>
                                                            <p class="company_details_text">Are you sure you want to delete this trip?</p>
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

                            </tbody>
                        </table>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card-->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
