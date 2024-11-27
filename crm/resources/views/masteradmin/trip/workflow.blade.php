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
			<div class="additional-info price">
				<table class="table">
					<tr>
						<td>Total Price: <strong>${{ $totalsByStatus[$status->tr_status_id] ?? 0 }}</strong></td>
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
				<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect width="28" height="28" rx="3" fill="#DCEBFF"/>
					<path d="M16.2124 20.2227H9.72266C8.6501 20.2227 7.77821 19.3508 7.77821 18.2782C7.77821 17.2057 8.6501 16.3338 9.72266 16.3338H15.1671C16.6682 16.3338 17.8893 15.1127 17.8893 13.6115C17.8893 12.1104 16.6682 10.8893 15.1671 10.8893H11.3863C11.1007 11.4492 10.747 11.9717 10.3332 12.4449H15.1671C15.8103 12.4449 16.3338 12.9683 16.3338 13.6115C16.3338 14.2548 15.8103 14.7782 15.1671 14.7782H9.72266C7.79299 14.7782 6.22266 16.3485 6.22266 18.2782C6.22266 20.2079 7.79299 21.7782 9.72266 21.7782H17.1839C16.8022 21.2981 16.4763 20.7763 16.2124 20.2227ZM8.55599 6.22266C7.26955 6.22266 6.22266 7.26955 6.22266 8.55599C6.22266 11.0355 8.55599 12.4449 8.55599 12.4449C8.55599 12.4449 10.8893 11.0348 10.8893 8.55599C10.8893 7.26955 9.84243 6.22266 8.55599 6.22266ZM8.55599 9.72266C8.40273 9.72261 8.25098 9.69237 8.10941 9.63367C7.96783 9.57497 7.83921 9.48897 7.73087 9.38056C7.62254 9.27215 7.53661 9.14347 7.47801 9.00185C7.41941 8.86024 7.38927 8.70847 7.38932 8.55521C7.38937 8.40195 7.41961 8.2502 7.47831 8.10863C7.53701 7.96705 7.62301 7.83843 7.73142 7.73009C7.83983 7.62176 7.96851 7.53584 8.11013 7.47723C8.25174 7.41863 8.40351 7.38849 8.55677 7.38855C8.86629 7.38865 9.16309 7.5117 9.38189 7.73064C9.60068 7.94958 9.72354 8.24647 9.72343 8.55599C9.72333 8.86551 9.60028 9.16232 9.38134 9.38111C9.1624 9.5999 8.86551 9.72276 8.55599 9.72266Z" fill="#384150"/>
					<path d="M19.4447 15.5559C18.1582 15.5559 17.1113 16.6028 17.1113 17.8892C17.1113 20.3688 19.4447 21.7781 19.4447 21.7781C19.4447 21.7781 21.778 20.368 21.778 17.8892C21.778 16.6028 20.7311 15.5559 19.4447 15.5559ZM19.4447 19.0559C19.2914 19.0559 19.1397 19.0256 18.9981 18.9669C18.8565 18.9082 18.7279 18.8222 18.6195 18.7138C18.5112 18.6054 18.4253 18.4767 18.3667 18.3351C18.3081 18.1935 18.2779 18.0417 18.278 17.8885C18.278 17.7352 18.3083 17.5835 18.367 17.4419C18.4257 17.3003 18.5117 17.1717 18.6201 17.0633C18.7285 16.955 18.8572 16.8691 18.9988 16.8105C19.1404 16.7519 19.2922 16.7217 19.4454 16.7218C19.755 16.7219 20.0518 16.845 20.2706 17.0639C20.4894 17.2828 20.6122 17.5797 20.6121 17.8892C20.612 18.1988 20.4889 18.4956 20.27 18.7144C20.0511 18.9332 19.7542 19.056 19.4447 19.0559Z" fill="#384150"/>
				</svg>

				<a href="#">{{ $value->tr_name ?? 'Trip Name' }}</a>
			</div>
			<div class="card-details">
				<div class="watching">
					<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect width="28" height="28" rx="3" fill="#DCEBFF"/>
						<path d="M7 21.0002V19.4447C7 18.6196 7.32778 17.8282 7.91122 17.2448C8.49467 16.6614 9.28599 16.3336 10.1111 16.3336H13.2222C14.0473 16.3336 14.8387 16.6614 15.4221 17.2448C16.0056 17.8282 16.3333 18.6196 16.3333 19.4447V21.0002M17.1111 7.10136C17.7803 7.2727 18.3735 7.6619 18.7971 8.2076C19.2206 8.75329 19.4505 9.42445 19.4505 10.1152C19.4505 10.806 19.2206 11.4772 18.7971 12.0229C18.3735 12.5686 17.7803 12.9578 17.1111 13.1291M21 21.0002V19.4447C20.9961 18.758 20.765 18.092 20.343 17.5503C19.9209 17.0087 19.3315 16.6219 18.6667 16.4502M8.55556 10.1114C8.55556 10.9365 8.88333 11.7278 9.46678 12.3112C10.0502 12.8947 10.8415 13.2225 11.6667 13.2225C12.4918 13.2225 13.2831 12.8947 13.8666 12.3112C14.45 11.7278 14.7778 10.9365 14.7778 10.1114C14.7778 9.28624 14.45 8.49491 13.8666 7.91147C13.2831 7.32802 12.4918 7.00024 11.6667 7.00024C10.8415 7.00024 10.0502 7.32802 9.46678 7.91147C8.88333 8.49491 8.55556 9.28624 8.55556 10.1114Z" stroke="#384150" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					{{ $value->users_first_name ?? '' }} {{ $value->users_last_name ?? '' }}
				</div>
				<div class="comments">
					<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect width="28" height="28" rx="3" fill="#DCEBFF"/>
						<path d="M9.33398 20.9993V19.4437C9.33398 18.6186 9.66176 17.8273 10.2452 17.2438C10.8287 16.6604 11.62 16.3326 12.4451 16.3326H15.5562C16.3813 16.3326 17.1726 16.6604 17.7561 17.2438C18.3395 17.8273 18.6673 18.6186 18.6673 19.4437V20.9993M10.8895 10.1104C10.8895 10.9355 11.2173 11.7268 11.8008 12.3103C12.3842 12.8937 13.1755 13.2215 14.0007 13.2215C14.8258 13.2215 15.6171 12.8937 16.2005 12.3103C16.784 11.7268 17.1118 10.9355 17.1118 10.1104C17.1118 9.28526 16.784 8.49394 16.2005 7.91049C15.6171 7.32704 14.8258 6.99927 14.0007 6.99927C13.1755 6.99927 12.3842 7.32704 11.8008 7.91049C11.2173 8.49394 10.8895 9.28526 10.8895 10.1104Z" stroke="#384150" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Traveler: {{ $value->tr_traveler_name ?? '' }}
				</div>
				<div class="attachment">					
					Trip Price: &#36;{{ $value->tr_value_trip ?? '0' }}
				</div>
			</div>
			<div class="additional-info">
				<table class="table">
					<tr>
						<td>Created Date: {{ $value->created_at ? $value->created_at->format('m/d/Y') : '' }}</td>
					</tr>
					<tr>
						<td>Updated Date: {{ $value->updated_at ? $value->updated_at->format('m/d/Y') : '' }}</td>
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