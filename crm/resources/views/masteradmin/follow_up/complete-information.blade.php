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
                        <table id="completedDatatable" class="table table-hover text-nowrap data-table">
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
                            @foreach ($tripCompleted as $comvalue)
                                <tr>
                                    <td>{{ $comvalue->tr_name ?? '' }}</td>
                                    <td>{{ $comvalue->users_first_name ?? ''}} {{$comvalue->users_last_name ?? '' }}</td>
                                    <td>{{ $comvalue->trtm_first_name ?? ''}}</td>
                                    <td>{{ $comvalue->tr_number ?? ''}}</td>
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
                                    <td>
                                        <a href="{{ route('trip.view', $comvalue->tr_id) }}"><i
                                                class="fas fa-eye edit_icon_grid"></i></a>
                                        <a href="{{ route('trip.edit', $comvalue->tr_id) }}"><i
                                                class="fas fa-pen edit_icon_grid"></i></a>
                                        <a data-toggle="modal"
                                            data-target="#delete-product-modal-{{ $comvalue->tr_id }}"><i
                                                class="fas fa-trash delete_icon_grid"></i></a>


                                        <div class="modal fade"
                                            id="delete-product-modal-{{ $comvalue->tr_id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered"
                                                role="document">
                                                <div class="modal-content">
                                                    <form id="delete-plan-form"
                                                        action="{{ route('trip.destroy', $comvalue->tr_id) }}"
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
