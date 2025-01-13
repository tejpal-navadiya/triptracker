<div class="card px-20">
    <div class="card-body1">
        <div class="col-md-12 table-responsive pad_table">
            <table id="example1" class="table table-hover text-nowrap data-table">
                <thead>
                    <tr>
                        <th>Trip Name</th>
                        <th>Agent Name</th>
                        <th>Traveler Name</th>
                        <th>Trip Number</th>
                        <th>Start Date</th>
                        <th>Trip Due Days</th>
                        <th class="sorting_disabled">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingTrips as $value)
                                        <tr>
                                            <td>{{ $value->tr_name ?? '' }}</td>
                                            <td>{{ $value->users_first_name ?? '' }}
                                                {{ $value->users_last_name ?? '' }}
                                            </td>
                                            <td>{{ $value->trtm_first_name ?? '' }}</td>
                                            <td>{{ $value->tr_number ?? ''}}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->tr_start_date ?? '')->format('M d, Y') }}
                                            </td>
                                            <td>-</td>
                                            <td>
                                                @php
                                                    $statusName = $value->trip_status->tr_status_name ?? '';

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

                                                <button type="button" class="btn text-white" style="background-color: {{ $buttonColor }};">
                                                    {{ $statusName }}
                                                </button>
                                            </td>
                                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div><!-- /.card-body -->
</div><!-- /.card-->