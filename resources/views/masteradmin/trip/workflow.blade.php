<!DOCTYPE html>
<!-- saved from url=(0068)https://responsive-kanban-templates.vercel.app/template-1/index.html -->
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="{{url('public/dist/css/drag.css')}}">
	<link rel="stylesheet" href="{{url('public/dist/css/kanban-style.css')}}">
</head>

<body>

	<div class="wrapper" id="grid-info" class="tab">
		<div class="board">
			@foreach ($trip_status as $status)
						@php
							// Set the button color based on the status
							$buttonColor = '#CCCCCC'; // Default color
							switch ($status->tr_status_id) {
								case 1:
									$buttonColor = '#DB9ACA';
									break;
								case 2:
									$buttonColor = '#F6A96D';
									break;
								case 3:
									$buttonColor = '#FBC11E';
									break;
								case 4:
									$buttonColor = '#28C76F';
									break;
								case 5:
									$buttonColor = '#C5A070';
									break;
								case 6:
									$buttonColor = '#F56B62';
									break;
								case 7:
									$buttonColor = '#FBC11E';
									break;
								case 8:
									$buttonColor = '#F6A96D';
									break;
								case 9:
									$buttonColor = '#DB9ACA';
									break;
								default:
									$buttonColor = '#CCCCCC';
									break;
							}
						@endphp

						<!-- Status Title as List Title -->
						<div class="list">
							<div class="list-title" style="background-color: {{ $buttonColor }}; color: #fff;">
								<a href="#status-{{ $status->tr_status_id }}" style="color: #fff; text-decoration: none;">
									{{ $status->tr_status_name }} <!-- Display the status name directly -->
								</a>
							</div>
							<div class="additional-info">
								<table class="table">
									<tr>
										<td>Total Price: {{ $totalsByStatus[$status->tr_status_id] ?? 0 }}</td>
									</tr>
								</table>
							</div>
							<!-- Trip Cards for this Status -->
							<div class="list-content" id="status-{{ $status->tr_status_id }}" ondragover="allowDrop(event)"
								ondrop="drop(event, {{ $status->tr_status_id }})">
								@if (isset($tripsGrouped[$status->tr_status_id]) && $tripsGrouped[$status->tr_status_id]->isNotEmpty())
									@foreach ($tripsGrouped[$status->tr_status_id] as $value)
										<div class="card" style="cursor: grab;" draggable="true" ondragstart="drag(event)"
											id="trip-{{ $value->tr_id }}" data-status="{{ $status->tr_status_id }}">
											<div class="trip-name-grid">
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



	<script src="{{url('public/dist/js/drag.min.js') }}"></script>
	<script src="{{url('public/dist/js/kanban.script.js') }}"></script>

</html>
<script>
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
					console.log("Trip status updated successfully!");
				} else {
					console.error("Failed to update status:", data.message);
				}
			})
			.catch(error => console.error("Error:", error));
	}

</script>