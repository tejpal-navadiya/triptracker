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
                        <table id="example4" class="table table-hover text-nowrap data-table">
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


