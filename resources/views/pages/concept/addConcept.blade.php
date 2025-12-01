@extends('layout.parent')
@push('script')
<script type="text/javascript">


	document.getElementById("eventType").addEventListener("change", function () {
		document.getElementById("event_type_id").value = this.value;
	});

	const input = document.getElementById("gallery_input");
	const container = document.getElementById("gallery_container");
	const orderInput = document.getElementById("image_order");

	let filesArray = [];

	input.addEventListener("change", (e) => {
		filesArray = Array.from(e.target.files); 
		renderImages();
	});

	function renderImages() {
		container.innerHTML = "";

		filesArray.forEach((file, index) => {
			const reader = new FileReader();
			reader.onload = function(e) {
				const div = document.createElement("div");
				div.className = "gallery-item position-relative";
				div.draggable = true;
				div.dataset.index = index;
				
				div.innerHTML = `
					<button class="remove-btn" onclick="removeImage(${index})">×</button>
					<img src="${e.target.result}">
				`;
				
				addDragEvents(div);
				container.appendChild(div);
			};
			reader.readAsDataURL(file);
		});

		updateOrder();
	}

	function addDragEvents(el) {
		el.addEventListener("dragstart", () => {
			el.classList.add("dragging");
		});

		el.addEventListener("dragend", () => {
			el.classList.remove("dragging");
			updateOrder();
		});

		el.addEventListener("dragover", (e) => {
			e.preventDefault();
			const dragging = container.querySelector(".dragging");
			const siblings = [...container.querySelectorAll(".gallery-item:not(.dragging)")];
			
			const nextSibling = siblings.find(sibling => {
				return e.clientX <= sibling.offsetLeft + sibling.offsetWidth / 2;
			});

			container.insertBefore(dragging, nextSibling);
		});
	}

	function removeImage(index) {
		filesArray.splice(index, 1);
		renderImages();
	}

	function updateOrder() {
		const order = [...container.children].map(item => item.dataset.index);
		orderInput.value = JSON.stringify(order);
	}
function updateTicketTypes(node) {
	// let numberOfTickets= document.getElementById("numberOfTickets").value;
	// numberOfTickets = parseInt(numberOfTickets);
	// if (numberOfTickets > 0) {
	//     let changed = node.value;
	//     if (changed > numberOfTickets || changed < 0) {
	//         return calculateSeats();
	//      }
	//     numberOfTickets = numberOfTickets-changed;
	//     totalNumber=numberOfTickets;
	//     const inputFields= document.querySelectorAll('.ticketTypes input[type="number"]');
	//     const fieldCount = inputFields.length-1;
	//     // Calculate the distributed value
	//     const distributedValue = Math.floor(totalNumber / fieldCount);
	//     const remainder = totalNumber % fieldCount;
	//     // Set the distributed values in each input field
	//     inputFields.forEach((input, index) => {
	//         if (input != node) {
	//         input.value = distributedValue + (index < remainder ? 1 : 0);
	//         }
	//     });
	// }
}

function addTicketType(node) {
	// $(node).parent().html(`<button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-danger px-5">Delete</button>`)
	// node.remove();
	let html = `<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">Type</div>
						</div>
						<select name="ticket[type][]" onkeyup="calculateSeats()" class="form-control form-select" required >
							<option value="" >Ticket Type</option>
							<?php foreach ($TicketTypes as $TicketType): ?>
							<option value="{{ $TicketType->id }}" >{{ $TicketType->ticketType }}</option>
							<?php endforeach ?>
						</select>
						<div class="input-group-prepend">
							<div class="input-group-text">Quantity</div>
						</div>
						<input required type="number" style="z-index: 9;" onkeyup="calculateSeats()" class="form-control" placeholder="Quantity" name="ticket[quantity][]" >
						<div class="input-group-prepend">
							<div class="input-group-text">Price</div>
						</div>
						<input required type="number" class="form-control" placeholder="Price" name="ticket[price][]" >
						<div class="col-2 text-center">
							<button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-danger px-5">Delete</button>
						</div>
					</div>`;
	$('.ticketTypes').append(html);
}
</script>
@endpush

@section('content')
<style>
label {
	font-weight: 700;
}
</style>
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">
				<section class="card">
					<header class="card-header">
						@if(Session::has('message'))
						<div class="alert">
							<script> document.addEventListener("DOMContentLoaded", () => { toastr.info('{{session('message')}}'); }); </script>
						</div>
						@endif
					</header>
					<div class="card-body">
						<h4>Concept Details</h4>
						<form method="POST" action="{{route('register_event')}}" enctype="multipart/form-data">
							@csrf
							<div class="form-row align-items-center">
								<div class="col-6">
									<label for="headline" class="form-label">Title</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
										</div>
										<input type="text" class="form-control" id="eventTitle" placeholder="Title"
											name="eventTitle" required>
									</div>
								</div>

								<div class="col-6">
									<label for="subtitle" class="form-label">Subtitle</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
										</div>
										<input type="text" class="form-control" id="subtitle" placeholder="Enter subtitle"
											name="subtitle" required>
									</div>
								</div>

								<!-- <div class="col-6">
									<label for="eventContactEmail" class="form-label">Contact Email</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-envelope"></i></div>
										</div>
										<input type="email" class="form-control" id="eventContactEmail"
											placeholder="Contact Email" name="eventContactEmail" required>
									</div>
								</div> -->

								<!-- <div class="col-6">
									<label for="eventContactNumber" class="form-label">Contact Number</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-mobile"></i></div>
										</div>
										<input type="tel" class="form-control" id="eventContactNumber"
											placeholder="Contact Number" name="eventContactNumber" required>
									</div>
								</div> -->

								<div class="col-12">
									<label for="eventDescription" class="form-label">Description</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-book"></i></div>
										</div>
										<textarea class="form-control" id="eventDescription" placeholder="Description"
											name="eventDescription"></textarea>
									</div>
								</div>

								<div class="col-6">
									<label for="eventAddress" class="form-label">Address</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-map-marker"></i></div>
										</div>
										<textarea class="form-control" id="eventAddress" placeholder="Address"
										name="eventAddress" required></textarea>
									</div>
								</div>

								<div class="col-6">
									<label for="eventDate" class="form-label">Start Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
										</div>
										<input type="datetime-local" class="form-control" id="eventDate"
											name="eventDate" required>
									</div>
								</div>
								<!--
								<div class="col-6">
									<label for="endDate" class="form-label">End Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
										</div>
										<input type="datetime-local" class="form-control" id="endDate" name="endDate"
											required>
									</div>
								</div> -->


							</div>

							<div class="form-row align-items-center">
								<div class="col-4">
									<label for="eventType" class="form-label">Event Category</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-exchange"></i></div>
										</div>
										<input type="hidden" id="event_type_id" name="event_type_id" />
										<select name="eventType" id="eventType" class="form-control" required>
											<option value="" selected>Select Category</option>
											@foreach ($eventType as $eventTypes)
											<option value="{{ $eventTypes->etid }}">{{ $eventTypes->eventType }}
											</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-4">
									<label for="eventType" class="form-label">Event Capacity</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-map-marker"></i></div>
										</div>
										<input class="form-control" id="eventCapacity" placeholder="Enter event capcity"
										name="eventCapacity" type="text" required />
									</div>
								</div>

								<div class="col-4">
									<label for="eventType" class="form-label">Event Ticket Price</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-map-marker"></i></div>
										</div>
										<input class="form-control" id="eventTicketPrice" placeholder="Enter event ticket price"
										name="eventTicketPrice" type="text" required />
									</div>
								</div>

								<!-- <div class="col-12">
									<hr/>
									<h4>Location</h4>
									<div class="row" >
										<div class="col-6">
											<label for="venue_name" class="form-label">Venue Name</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
												</div>
												<input type="text" class="form-control" id="venue_name" placeholder="Venue Name"
													name="venue_name" required>
											</div>
										</div>

										<div class="col-6">
											<label for="venue_address" class="form-label">Venue Address</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
												</div>
												<input type="text" class="form-control" id="venue_address" placeholder="Venue Address"
													name="venue_address" required>
											</div>
										</div>
									</div>
								</div> -->

								<div class="col-12">
									<hr/>
									<h4>Event Images</h4>
									<div class="row">
										<div class="col-12 mb-3">
											<label class="form-label">Cover Image</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<div class="input-group-text"><i class="fa fa-image"></i></div>
												</div>
												<input type="file" accept="image/*" class="form-control" id="cover_image" name="cover_image" required>
											</div>
											<div id="cover_preview" style="max-width: 200px;"></div>
										</div>

										<label class="form-label">Upload Event Images</label>
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fa fa-images"></i></div>
											</div>
											<input type="file" id="gallery_input" name="gallery_images[]" accept="image/*" multiple class="form-control">
										</div>

										<div id="gallery_container" class="d-flex flex-wrap" style="gap: 10px;"></div>
										<input type="hidden" id="image_order" name="image_order">
									</div>
								</div>

								<!-- <div class="col-12">
									<hr/>
									<h4>Concept Organizer</h4>
									<div class="row" >
										<div class="col-12">
											<label for="event_organizer_Description" class="form-label">Description</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<div class="input-group-text"><i class="fa fa-book"></i></div>
												</div>
												<textarea class="form-control" id="event_organizer_Description" placeholder="Description"
													name="event_organizer_Description"></textarea>
											</div>
										</div>

										<div class="col-6">
											<label for="event_organizer_phone" class="form-label">Phone</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
												</div>
												<input type="text" class="form-control" id="event_organizer_phone" placeholder="Phone"
													name="event_organizer_phone" required>
											</div>
										</div>

										<div class="col-6">
											<label for="event_organizer_email" class="form-label">Email</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
												</div>
												<input type="email" class="form-control" id="event_organizer_email" placeholder="Email"
													name="event_organizer_email" required>
											</div>
										</div>

										<div class="col-6">
											<label for="event_organizer_website" class="form-label">Website</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
												</div>
												<input type="text" class="form-control" id="event_organizer_website" placeholder="Website"
													name="event_organizer_website" required>
											</div>
										</div>

										<div class="col-6">
											<label for="event_organizer_social_media" class="form-label">Social Media</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
												</div>
												<input type="text" class="form-control" id="event_organizer_social_media" placeholder="Social Media"
													name="event_organizer_social_media" required>
											</div>
										</div>
									</div>
									<hr/>
								</div> -->

								<!-- <div class="col-12">
									<label for="eventAccess" class="form-label">Event Access</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-ticket"></i></div>
										</div>
										<select name="eventAccess" id="eventAccess" class="form-control" required>
											<option value="">Select Event Access</option>
											<option value="0">Public</option>
											<option value="1">Private</option>
										</select>
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-th-large"></i></div>
										</div>
										<select name="seatArrange" id="seatArrange" class="form-control" required>
											<option value="grid">Grid</option>
											<option value="roundTable">Table</option>
										</select>
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-bookmark-o"></i></div>
										</div>
										<input type="text" class="form-control" id="ticketDivision"
											placeholder="Seats per row" name="ticketDivision"
											oninput="calculateSeats()">
									</div>
								</div> -->

								<div class="col-12">
									<button type="submit" class="btn btn-primary mb-2 px-5">Create</button>
								</div>
							</div>
						</form>
					</div>
				</section>
				<!-- <div class="modal fade" id="generateTicketsModal" tabindex="-1" role="dialog"
					aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">

							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Generate Tickets</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>

							<div class="modal-body">
								<form action="">
									<p class="mb-3 text-muted">Are you sure you want to generate tickets?</p>
									<input type="hidden" name="eventId" id="eventId">
								</form>
								<div id="generateTicketResponse" class="alert d-none mt-3"></div> 
							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-primary btnGenerate" onclick="generateTicket(event)">Generate</button>
							</div>

						</div>
					</div>
				</div> -->


				<!-- <div class="modal fade" id="importTicketsModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="importModalLabel">Import Tickets Sold</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p>Please upload a CSV file containing ticket sales.</p>
							<form id="importTicketsForm">
								@csrf
								<input type="hidden" name="eventId" id="eventId" value="1"> 
								<input type="file" class="form-control" name="csvFile" id="csvFile" accept=".csv" required>
							</form>
							<div id="importResponse" class="mt-3"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary" id="submitImport">Import</button>
						</div>
					</div>
				</div> -->
			</div>



			</div>
		</div>
	</section>
</section>

@endsection
