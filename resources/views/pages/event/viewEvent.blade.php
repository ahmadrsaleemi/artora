@extends('layout.parent')
@section('content')
<section id="main-content">
	<section class="wrapper">
			<header class="card-header d-flex justify-content-between align-items-center">
			<span>View Events</span>
			<a href="/event/Add-Event" class="btn btn-primary btn-sm">Add Event</a>
		</header>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Title</th>
							<th>Address</th>
							<th>Seating</th>
							<th>Ticket Type</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($allEvents as $allEvent)
						<tr>
							<td>{{ $allEvent->eid }}</td>
							<td>{{ date('d-m-Y',strtotime($allEvent->eventDate)) }}</td>
							<td>{{ $allEvent->title }}</td>
							<td>{{ $allEvent->address }}</td>
							<td>{{ ucfirst($allEvent->detail->seatArrangement ?? '') }}</td>
							<td><?php
				foreach ($allEvent->tickettypes->pluck('ticketType','tickets')->toArray() as $total => $type) {
					echo $type.':'.$total.'<br>';
					}
				?></td>

							<td>

								<a href="updateEventPage/{{ $allEvent->eid }}" class="btn btn-primary"><i
										class="fa fa-edit"></i></a>
								<a href="deleteEvent/{{ $allEvent->eid }}" class="btn btn-danger"
									onclick="return confirm('Are you sure you want to delete?');"><i
										class="fa fa-trash-o"></i></a>

								<a href="{{ url('/ticket/View-Ticket?id=') }}{{ $allEvent->eid }}" title="View Tickets"
									class="btn btn-warning"><i class="fa fa-ticket"></i></a>
									<a href="{{ url('/ticket/Ticket-Assign?id=') }}{{ $allEvent->eid }}" title="Assign Tickets"
									class="btn btn-success"><i class="fa fa-user-plus"></i></a>

								<a href="#" class="btn btn-secondary" title="Import Ticket details from CSV" onclick="return importTicketsModal('{{ $allEvent->title }}','{{ $allEvent->eid }}');"><i class="fa fa-upload"></i></a>
								<a href="{{ url('/ticket/Seat-Plan?id=') }}{{ $allEvent->eid }}" class="btn btn-info" title="View Seating Plan"><i class="fa fa-th"></i></a>


							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>

		</div>
	</section>
</section>
@endsection