@extends('layout.parent')
@push('script')
<script type="text/javascript">
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
                            <select name="typeType[type][]" onkeyup="calculateSeats()" class="form-control form-select" required >
                                <option value="" >Ticket Type</option>
                                <?php foreach ($TicketTypes as $TicketType): ?>
                                <option value="{{ $TicketType->id }}" >{{ $TicketType->ticketType }}</option>
                                <?php endforeach ?>
                            </select>
                            <div class="input-group-prepend">
                                <div class="input-group-text">Quantity</div>
                            </div>
                            <input required type="number" style="z-index: 9;" onkeyup="calculateSeats()" class="form-control" placeholder="Quantity" name="typeType[quantity][]" >
                            <div class="input-group-prepend">
                                <div class="input-group-text">Price</div>
                            </div>
                            <input required type="number" class="form-control" placeholder="Price" name="typeType[price][]" >
                            <div class="col-2 text-center">
                                <button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-danger px-5">Delete</button>
                            </div>
                        </div>`;
        $('.ticketTypes').append(html);
    }
</script>
@endpush
@section('content')
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="card">
                    <header class="card-header">
                        @if(Session::has('message'))
                        <div class="alert">
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    toastr.info('{{session('message')}}');
                                });
                            </script>
                        </div>

                        @endif
                        Update Event
                    </header>
                    <div class="card-body">
                        <form method="POST" id="updateEventForm" action="{{url('updateEvent')}}">
                            @csrf
                            <input type="hidden" name="eventId" value="{{ $eventData[0]->eid }}" />
                            <div class="form-row align-items-center">

                                <div class="col-6">
                                    <label for="eventTitle" class="form-label">Event Title</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="eventTitle" placeholder="Title"
                                            name="eventTitle" value="{{ $eventData[0]->title }}" required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="headline" class="form-label">Headline</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="headline" placeholder="Headline"
                                            name="headline" value="{{ $eventData[0]->headline }}" required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="eventContactEmail" class="form-label">Contact Email</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                        </div>
                                        <input type="email" class="form-control" id="eventContactEmail"
                                            placeholder="Contact Email" name="eventContactEmail" value="{{ $eventData[0]->contactEmail }}" required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="eventContactNumber" class="form-label">Contact Number</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-mobile"></i></div>
                                        </div>
                                        <input type="tel" class="form-control" id="eventContactNumber"
                                            placeholder="Contact Number" name="eventContactNumber" value="{{ $eventData[0]->contactNumber }}" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="eventDescription" class="form-label">Description</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-book"></i></div>
                                        </div>
                                        <textarea class="form-control" id="eventDescription" placeholder="Description"
                                            name="eventDescription" >{{ $eventData[0]->description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="eventAddress" class="form-label">Address</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-map-marker"></i></div>
                                        </div>
                                        <textarea class="form-control" id="eventAddress" placeholder="Address"
                                            name="eventAddress"  required>{{ $eventData[0]->address }}</textarea>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="eventDate" class="form-label">Start Date</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" id="eventDate"
                                            name="eventDate" value="{{ $eventData[0]->eventDate }}" required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                        </div>
                                        <input type="datetime-local" class="form-control" id="endDate" name="endDate" value="{{ $eventData[0]->endDate }}"
                                            required>
                                    </div>
                                </div>

                            </div>

                            <div class="form-row align-items-center">
                                <div class="col-12">
                                    <label for="eventType" class="form-label">Event Category</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-exchange"></i></div>
                                        </div>
                                        <select name="eventType" id="eventType" class="form-control" required>
                                            <option value="" selected>Select Category</option>
                                            @foreach ($eventType as $eventTypes)
                                            <option {{ $eventDetailsData[0]->eventType == $eventTypes->eventType ? 'selected' : '' }} value="{{ $eventTypes->eventType }}">{{ $eventTypes->eventType }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr />
                                    <h4>Location</h4>
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="venue_name" class="form-label">Venue Name</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="venue_name"
                                                    placeholder="Venue Name" name="venue_name" value="{{ $eventData[0]->venue_name }}" required>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <label for="venue_address" class="form-label">Venue Address</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="venue_address"
                                                    placeholder="Venue Address" name="venue_address" value="{{ $eventData[0]->venue_address }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr />
                                    <h4>Event Organizer</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="event_organizer_Description"
                                                class="form-label">Description</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-book"></i></div>
                                                </div>
                                                <textarea class="form-control" id="event_organizer_Description"
                                                    placeholder="Description"
                                                    name="event_organizer_Description">{{ $eventData[0]->event_organizer_Description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <label for="event_organizer_phone" class="form-label">Phone</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="event_organizer_phone"
                                                    placeholder="Phone" name="event_organizer_phone" value="{{ $eventData[0]->event_organizer_phone }}" required>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <label for="event_organizer_email" class="form-label">Email</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                                </div>
                                                <input type="email" class="form-control" id="event_organizer_email"
                                                    placeholder="Email" name="event_organizer_email" value="{{ $eventData[0]->event_organizer_email }}" required>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <label for="event_organizer_website" class="form-label">Website</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="event_organizer_website"
                                                    placeholder="Website" name="event_organizer_website" value="{{ $eventData[0]->event_organizer_website }}" required>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <label for="event_organizer_social_media" class="form-label">Social
                                                Media</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                                </div>
                                                <input type="text" class="form-control"
                                                    id="event_organizer_social_media" placeholder="Social Media"
                                                    name="event_organizer_social_media" value="{{ $eventData[0]->event_organizer_social_media }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                </div>

                                <div class="col-12">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-ticket"></i></div>
                                        </div>
                                        <select name="eventAccess" id="eventAccess" class="form-control">
                                            <option value="">Select Event Access</option>
                                            <option value="0"
                                                {{ $eventDetailsData[0]->eventAccess == '0' ? 'selected' : '' }}>Public
                                            </option>
                                            <option value="1"
                                                {{ $eventDetailsData[0]->eventAccess == '1' ? 'selected' : '' }}>Private
                                            </option>
                                        </select>

                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-th-large"></i></div>
                                        </div>
                                        <select name="seatArrange" id="seatArrange" class="form-control">
                                            <option value="">Select Seating Arrangement</option>
                                            <option value="grid"
                                                {{ $eventDetailsData[0]->seatArrangement == 'grid' ? 'selected' : '' }}>
                                                Grid</option>
                                            <option value="roundTable"
                                                {{ $eventDetailsData[0]->seatArrangement == 'roundTable' ? 'selected' : '' }}>
                                                Table</option>
                                        </select>

                                        <!-- <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-check-square"></i></div>
                            </div>
                            <select name="eventStatus" id="eventStatus" class="form-control">
                                <option value="">Select Status</option>
                                <option value="-1" {{ $eventData[0]->eventStatus == '-1' ? 'selected' : '' }}>Admin Denied</option>
                                <option value="0" {{ $eventData[0]->eventStatus== '0' ? 'selected' : '' }}>Not Approved</option>
                                <option value="1" {{ $eventData[0]->eventStatus == '1' ? 'selected' : '' }}>Approved</option>
                               
                            </select> -->
                                        {{-- <input type="text" class="form-control" id="eventSta" placeholder="Seats per row" name="ticketDivision" oninput="calculateSeats()" value="{{$eventDetailsData[0]->ticketDivision}}">
                                        --}}
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-bookmark-o"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="ticketDivision"
                                            placeholder="Seats per row" name="ticketDivision" oninput="calculateSeats()"
                                            value="{{$eventDetailsData[0]->ticketDivision}}">
                                    </div>
                                </div>

                                <div class="col-12 ticketTypes">
                                    <h4>Select ticket types</h4>
                                    <?php
                        foreach ($eventData[0]->tickettypes as $i => $tickettype) { ?>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Type</div>
                                        </div>
                                        <select name="typeType[type][]" class="form-control form-select" required>
                                            <option value="" onchange="calculateSeats()">Ticket Type</option>
                                            <?php foreach ($TicketTypes as $TicketType): ?>
                                            <option {{ $tickettype->type_id == $TicketType->id ? 'selected' : '' }}
                                                value="{{ $TicketType->id }}">{{ $TicketType->ticketType }}</option>
                                            <?php endforeach ?>
                                        </select>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Quantity</div>
                                        </div>
                                        <input required type="number" value="{{ $tickettype->tickets }}"
                                            style="z-index: 9;" onkeyup="calculateSeats()" class="form-control"
                                            placeholder="Quantity" name="typeType[quantity][]">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Price</div>
                                        </div>
                                        <input required type="number" class="form-control" placeholder="Price"
                                            value="{{ $tickettype->price }}" name="typeType[price][]">
                                        <div class="col-3">
                                        @if($i == 0)
                                            <button type="button" onclick="addTicketType(this)" class="btn btn-success">Add Ticket Type</button>
                                        @endif
                                            <button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-danger">Delete</button>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div id="resultDiv"></div>
                                <div class="col-12">
                                    <button type="submit" id="updateEventBtn" class="btn btn-primary mb-2 px-5">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </section>
</section>
@endsection