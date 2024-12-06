<link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">
<div class="card">
    <div class="card-header">
        <!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-12 table-responsive pad_table">
                <table id="EmailDataTable" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Msg</th>
                            <th>Create Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<?php //dd($trip_id); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function() {
    setTimeout(function() {
        $('#EmailDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.trip.fetchEmails', $trip_id) }}", 
                type: 'GET',
                data: function(d) {
                    d._token = "{{ csrf_token() }}"; 
                }
            },
           
            columns: [
                { data: 'from', name: 'from' }, 
                { data: 'subject', name: 'subject' }, 
                { data: 'date', name: 'date' } 
            ]
        });
    }, 3000);
});
 </script>