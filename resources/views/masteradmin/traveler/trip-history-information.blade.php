<div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-12 table-responsive pad_table">
                <table id="example1" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Trip Name</th>
                            <th>Agent Name</th>
                            <th>Start to End Date</th>
                            <th>Status</th>
                            <th class="sorting_disabled text-right" data-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trip_history as $historyvalue)
                        <tr>
                            <td>{{ $historyvalue->tr_name ?? '' }}</td>
                            <td>{{ $historyvalue->users_first_name ?? '' }} {{ $historyvalue->users_last_name ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($historyvalue->tr_start_date ?? '')->format('M d, Y') }}
                                {{ \Carbon\Carbon::parse($historyvalue->tr_end_date ?? '')->format('M d, Y') }}
                                </td>
                                <td>
                                    @php
                                        $statusName = $historyvalue->trip_status->tr_status_name ?? '';

                                        $buttonColor = match (strtolower($statusName)) {
                                            'trip request' => '#DB9ACA',
                                            'trip proposal' => '#F6A96D',
                                            'trip modification' => '#FBC11E',
                                            'trip accepted' => '#28C76F',
                                            'trip sold' => '#C5A070',
                                            'trip lost' => '#F56B62',
                                            'trip completed' => '#F56B62',
                                        };
                                    @endphp
                                    <button type="button" class="btn text-white"
                                        style="background-color: {{ $buttonColor }};">
                                        {{ $statusName }}
                                    </button>
                                </td>
                                <td><a href="{{ route('trip.view', $historyvalue->tr_id) }}"><i
                                class="fas fa-eye edit_icon_grid"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
