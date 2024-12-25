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
                                    <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($trip as $value)
                                <tr>
                                    <td>{{ $value->tr_name ?? '' }}</td>
                                    <td>{{ $value->users_first_name ?? ''}} {{$value->users_last_name ?? '' }}</td>
                                    <td>{{ $value->trtm_first_name ?? ''}}</td>
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
                                    <td>
                                    <a href="{{ route('trip.view', $value->tr_id) }}"><i
                                            class="fas fa-eye edit_icon_grid"></i></a>
                                    <a href="{{ route('trip.edit', $value->tr_id) }}"><i
                                            class="fas fa-pen edit_icon_grid"></i></a>
                                    <a data-toggle="modal"
                                        data-target="#delete-product-modal-{{ $value->tr_id }}"><i
                                            class="fas fa-trash delete_icon_grid"></i></a>


                                    <div class="modal fade"
                                        id="delete-product-modal-{{ $value->tr_id }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-sm modal-dialog-centered"
                                            role="document">
                                            <div class="modal-content">
                                                <form id="delete-plan-form"
                                                    action="{{ route('trip.destroy', $value->tr_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body pad-1 text-center">
                                                        <i
                                                            class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_business_name px-10"><b>Delete
                                                                Trip</b></p>
                                                        <p>Are you sure you want to delete this trip?
                                                                </p>
                                                        <button type="button" class="add_btn px-15"
                                                            data-dismiss="modal">Cancel</button>
                                                        @csrf
                                                        @method('DELETE')
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
