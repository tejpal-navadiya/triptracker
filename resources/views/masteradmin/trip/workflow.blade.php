<!DOCTYPE html>
<!-- saved from url=(0068)https://responsive-kanban-templates.vercel.app/template-1/index.html -->
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
        <link rel="stylesheet" href="{{url('public/dist/css/drag.css')}}">
        <link rel="stylesheet" href="{{url('public/dist/css/kanban-style.css')}}">
	</head>
	<body>
		
	<div class="wrapper">
		<div class="board">
			@foreach ($trip_status as $status)
				@php
					// Set the button color based on the status
					$buttonColor = '#CCCCCC'; // Default color
					switch ($status->tr_status_id) {
						case 1: $buttonColor = '#DB9ACA'; break;
						case 2: $buttonColor = '#F6A96D'; break;
						case 3: $buttonColor = '#FBC11E'; break;
						case 4: $buttonColor = '#28C76F'; break;
						case 5: $buttonColor = '#C5A070'; break;
						case 6: $buttonColor = '#F56B62'; break;
						case 7: $buttonColor = '#FBC11E'; break;
						case 8: $buttonColor = '#F6A96D'; break;
						case 9: $buttonColor = '#DB9ACA'; break;
						default: $buttonColor = '#CCCCCC'; break;
					}
				@endphp
				<div class="list">
					<div class="list-title" style="background-color: {{ $buttonColor }}; color: #fff;">
						<a href="#status-{{ $status->tr_status_id }}" style="color: #fff; text-decoration: none;">
							{{ $status->tr_status_name }}
						</a>
					</div>
					<div class="additional-info">
						<table class="table">
							<tr>
								<td>Total Price: {{ $totalsByStatus[$status->tr_status_id] ?? 0 }}</td>
							</tr>
						</table>
					</div>
					<div 
						class="list-content" 
						id="status-{{ $status->tr_status_id }}" 
						data-status="{{ $status->tr_status_id }}"
					>
						@if (isset($tripsGrouped[$status->tr_status_id]) && $tripsGrouped[$status->tr_status_id]->isNotEmpty())
							@foreach ($tripsGrouped[$status->tr_status_id] as $value)
								<div 
									class="card" 
									style="cursor: grab;" 
									draggable="true" 
									id="trip-{{ $value->tr_id }}" 
									data-status="{{ $status->tr_status_id }}"
								>
									<div class="card-title">
										<a href="#">{{ $value->tr_name ?? 'Trip Name' }}</a>
									</div>
									<div class="card-details">
										<div class="watching">
											<i class="fas fa-user"></i>
											{{ $value->users_first_name ?? '' }} {{ $value->users_last_name ?? '' }}
										</div>
										<div class="comments">
											<i class="fas fa-user-friends"></i>
											Traveler: {{ $value->tr_traveler_name ?? '' }}
										</div>
										<div class="attachment">
											<i class="fas fa-dollar-sign"></i>
											Trip Price: {{ $value->tr_value_trip ?? 'N/A' }}
										</div>
									</div>
									<div class="additional-info">
										<table class="table">
											<tr>
												<td>Created Date: {{ $value->created_at ?? 'N/A' }}</td>
											</tr>
											<tr>
												<td>Updated Date: {{ $value->updated_at ?? 'N/A' }}</td>
											</tr>
										</table>
									</div>
									<a href="{{ route('trip.edit', $value->tr_id) }}" class="edit-icon">
										<i class="fas fa-pen"></i>
									</a>
								</div>
							@endforeach
						@else
							<p>No trips available for this status.</p>
						@endif
					</div>
				</div>
			@endforeach
		</div>
	</div>

</div>		
			</div>
		</div>
<div class="modal fade" id="document-success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body pad-1 text-center">
                <i class="fas fa-check-circle success_icon"></i>
                <p class="company_business_name px-10"><b>Success!</b></p>
                <p class="company_details_text px-10" id="document-success-message">Data has been successfully inserted!</p>
                <button type="button" class="add_btn px-15" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>	
       

		<script src="{{url('public/dist/js/drag.min.js') }}"></script>
		<script src="{{url('public/dist/js/kanban.script.js') }}"></script>
	
</html>
<!-- <script>
    // Allow dropping on status containers
    function allowDrop(event) {
        event.preventDefault();
    }

    // Store the ID of the dragged card
    function drag(event) {
        event.dataTransfer.setData("text", event.target.id);
    }

    // Handle drop and update status
    function drop(event, newStatus) {
        event.preventDefault();
        
        // Get the dragged card's ID
        const draggedCardId = event.dataTransfer.getData("text");
        const cardElement = document.getElementById(draggedCardId);
        
        // Append the card to the new status container
        event.target.appendChild(cardElement);

        // Get the trip ID from the card's ID
        const tripId = draggedCardId.split('-')[1];
        
        // Send an AJAX request to update the status
        updateTripStatus(tripId, newStatus);
    }

    // AJAX request to update trip status
    function updateTripStatus(tripId, newStatus) {
        fetch("{{ route('trip.updateStatus') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                trip_id: tripId,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
				alert(data.success);
                console.log("Trip status updated successfully!");
            } else {
                console.error("Failed to update status:", data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    }
	
</script> -->
<script>
$(document).ready(function() {
    // Flag to prevent multiple AJAX calls
    var ajaxCallInProgress = false;

    $(".card").draggable({
        revert: true,
        start: function(event, ui) {
            $(this).data("origin", $(this).data("status")); // Store the original status
        }
    });

    $(".list-content").droppable({
        drop: function(event, ui) {
            var originStatus = ui.draggable.data("origin"); // Get the original status
            var newStatus = $(this).data("status"); // Get the new status

            // Prevent moving to the same status
            if (originStatus === newStatus) {
                return;
            }

            // Move the card to the new status
            $(this).append(ui.draggable);

            // Update the status attribute of the card
            ui.draggable.data("status", newStatus);

            // Prevent further AJAX calls until this one is complete
            if (ajaxCallInProgress) {
                alert("Please wait, the status is being updated.");
                return;
            }
            ajaxCallInProgress = true; // Set the flag to true

            // Send AJAX request to update the status in the backend
            $.ajax({
                url: "{{ route('trip.updateStatus') }}", // Update this URL as needed
                method: "POST",
                data: {
                    trip_id: ui.draggable.attr("id").replace("trip-", ""), // Get the trip ID
                    status: newStatus, // Send the new status
                    _token: "{{ csrf_token() }}" // Include CSRF token for Laravel
                },
                success: function(response) {
					
						// // Update the card's status or any other relevant UI elements
						// ui.draggable.find('.status-indicator').text(newStatus); // Assuming you have an element to show status

						// // Perform additional actions after the successful update
						// // Example: Log the response to the console
						// console.log("Updated trip ID: " + response.trip_id + ", New Status: " + newStatus);

						// // Optionally update any counters or other UI elements
						// // Example: Update a counter for the status column
						// var statusCounter = $(this).find('.status-counter'); // Assuming you have a counter element
						// statusCounter.text(parseInt(statusCounter.text()) + 1); // Increment the counter
						

						$.ajax({
							url: "{{ route('trip.gridView') }}",
							type: 'GET',
							success: function (response) {
								
								$('#viewContainer').html(response);
								$('#document-success-message').text("Trip status updated successfully!");
                    
                    			$('#document-success-modal').modal('show');
							},
							error: function (xhr) {
								console.error('Error loading grid view:', xhr);
							}
						});
						
					},
                error: function() {
                    alert("An error occurred while updating the status.");
                },
                complete: function() {
                    // Reset the flag regardless of success or error
                    ajaxCallInProgress = false;
                }
            });
        }
    });
});
</script>