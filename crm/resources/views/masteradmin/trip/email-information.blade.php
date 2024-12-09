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
// Initialize WebSocket connection
const socket = new WebSocket('ws://localhost:6001');

// Event listeners
socket.onopen = function() {
    console.log('WebSocket connection established');
};

socket.onmessage = function(event) {
    console.log('Message from server:', event.data);
};

socket.onerror = function(error) {
    console.log('WebSocket Error:', error);
};

socket.onclose = function(event) {
    console.log('WebSocket closed:', event);
};

$(document).ready(function() {
    var table = $('#EmailDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('masteradmin.trip.fetchEmails', ['tripId' => $trip_id, 'user_id' => $user_id, 'unique_id' => $uniq_id]) }}",
            type: 'GET',
            dataSrc: function (json) {
                if (json.error) {
                    $('#error-message').text(json.error).show();
                    return []; // Return an empty array to DataTable
                }
                $('#error-message').hide();
                return json.data; // Return the data to DataTable
            },
            error: function (xhr) {
                // Handle server errors gracefully
                $('#error-message').text(xhr.responseJSON.error).show();
            }
        },
        columns: [
            { data: 'from', name: 'from' },
            { data: 'subject', name: 'subject' },
            { data: 'date', name: 'date' }
        ]
    });

   
});
</script>
