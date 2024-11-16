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
				<div 
                        class="list-content" 
                        id="status-{{ $status->tr_status_id }}" 
                        ondragover="allowDrop(event)" 
                        ondrop="drop(event, {{ $status->tr_status_id }})"
                    >
                        @if (isset($tripsGrouped[$status->tr_status_id]) && $tripsGrouped[$status->tr_status_id]->isNotEmpty())
                            @foreach ($tripsGrouped[$status->tr_status_id] as $value)
                                <div 
                                    class="card" 
                                    style="cursor: grab;" 
                                    draggable="true" 
                                    ondragstart="drag(event)" 
                                    id="trip-{{ $value->tr_id }}" 
                                    data-status="{{ $status->tr_status_id }}"
                                >
                                    <div class="card-cover">
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





				
				<!-- <div class="list">
					<div class="list-title" style="cursor: grab;">To Do</div>
					<div class="list-content">
						
						
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-blue"></div>
								</div>
								<div class="card-title">
									Write unit tests for API
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date">
										<i class="far fa-clock"></i>
										12 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										3
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										2
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-green"></div>
								</div>
								<div class="card-title">
									Create blog post feature
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date overdue">
										<i class="far fa-clock"></i>
										13 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										0
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-purple"></div>
								</div>
								<div class="card-title">
									Add comments section to posts
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date">
										<i class="far fa-clock"></i>
										14 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										2
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										2
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				
				<!-- <div class="list">
					<div class="list-title" style="cursor: grab;">In Progress</div>
					<div class="list-content">
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=4&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-purple"></div>
								</div>
								<div class="card-title">
									Implement payment gateway
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										11 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										4
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										3
									</div>
								</div>
							</div>
						</div><div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-orange"></div>
								</div>
								<div class="card-title">
									Conduct user testing
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										15 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										2
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										0
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-blue"></div>
								</div>
								<div class="card-title">
									Integrate third-party APIs
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date overdue">
										<i class="far fa-clock"></i>
										16 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										3
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=6&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-green"></div>
								</div>
								<div class="card-title">
									Optimize website performance
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date">
										<i class="far fa-clock"></i>
										18 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										5
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-purple"></div>
								</div>
								<div class="card-title">
									Finalize website design
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										19 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										2
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=7&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-blue"></div>
								</div>
								<div class="card-title">
									Prepare deployment scripts
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date overdue">
										<i class="far fa-clock"></i>
										20 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										3
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				
				<!-- <div class="list">
					<div class="list-title" style="cursor: grab;">Review</div>
					<div class="list-content">
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-green"></div>
								</div>
								<div class="card-title">
									Design homepage layout
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date overdue">
										<i class="far fa-clock"></i>
										10 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										2
									</div>
								</div>
							</div>
						</div><div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=8&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-green"></div>
								</div>
								<div class="card-title">
									Review tech partner pages
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										21 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										2
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										0
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-orange"></div>
								</div>
								<div class="card-title">
									Conduct peer code review
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date">
										<i class="far fa-clock"></i>
										22 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										0
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-purple"></div>
								</div>
								<div class="card-title">
									Review design mockups
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										23 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										2
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=9&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-green"></div>
								</div>
								<div class="card-title">
									Verify database migrations
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date overdue">
										<i class="far fa-clock"></i>
										24 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										0
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-blue"></div>
								</div>
								<div class="card-title">
									Review API documentation
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date">
										<i class="far fa-clock"></i>
										25 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										3
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										2
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->

				<!-- <div class="list">
					<div class="list-title" style="cursor: grab;">Testing</div>
					<div class="list-content">
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=5&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-purple"></div>
								</div>
								<div class="card-title">
									Test new user roles
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date">
										<i class="far fa-clock"></i>
										15 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										0
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-blue"></div>
								</div>
								<div class="card-title">
									Conduct integration testing
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										16 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										0
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				
				<!-- <div class="list">
					<div class="list-title" style="cursor: grab;">Done</div>
					<div class="list-content">
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-purple"></div>
								</div>
								<div class="card-title">
									Launch website
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										26 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										0
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=10&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-green"></div>
								</div>
								<div class="card-title">
									Set up hosting
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										27 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										0
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-blue"></div>
								</div>
								<div class="card-title">
									Prepare marketing materials
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										28 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										2
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=11&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-orange"></div>
								</div>
								<div class="card-title">
									Publish blog post
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										29 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										3
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										0
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-green"></div>
								</div>
								<div class="card-title">
									Submit final report
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										30 May
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										0
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				
				<!-- <div class="list">
					<div class="list-title" style="cursor: grab;">Archived</div>
					<div class="list-content">
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=12&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-purple"></div>
								</div>
								<div class="card-title">
									Archive old project files
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										1 June
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										0
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										2
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-blue"></div>
								</div>
								<div class="card-title">
									Remove outdated resources
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										2 June
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										1
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=13&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-green"></div>
								</div>
								<div class="card-title">
									Update documentation
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date overdue">
										<i class="far fa-clock"></i>
										3 June
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										2
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										0
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-content">
								<div class="label-group">
									<div class="label label-orange"></div>
								</div>
								<div class="card-title">
									Consolidate project feedback
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										4 June
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										1
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										3
									</div>
								</div>
							</div>
						</div>
						<div class="card" style="cursor: grab;">
							<div class="card-cover" style="background-image: url(&#39;https://picsum.photos/400/300?random=14&#39;);"></div>
							<div class="card-content">
								<div class="label-group">
									<div class="label label-purple"></div>
								</div>
								<div class="card-title">
									Prepare for project review
								</div>
								<div class="card-details">
									<div class="watching">
										<i class="fas fa-eye"></i>
									</div>
									<div class="due-date complete">
										<i class="far fa-clock"></i>
										5 June
									</div>
									<div class="comments">
										<i class="fas fa-comment"></i>
										0
									</div>
									<div class="attachment">
										<i class="fas fa-paperclip"></i>
										2
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				
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
