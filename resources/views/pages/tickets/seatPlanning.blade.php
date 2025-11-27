@extends('layout.parent')

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
                    <script> document.addEventListener("DOMContentLoaded", () => { toastr.info('{{session('message')}}'); }); </script>
                </div>
            
            @endif
            Seating Arrangement
        </header>
        <div class="card-body">
           <div class="row mb-5">
                <div class="col-md-4">
                    <select name="eventSelect" id="eventSelectAssign" class="form-control">
                        <option value="" selected>Select Event</option>
                        @foreach ($allEvent as $allEvent)
                            <option {{ $allEvent->eid == request()->id ? 'selected' : '' }} value="{{$allEvent->eid}}">{{$allEvent->title}}</option>
                        @endforeach
                    </select>
                    
                </div>
                <div class="col-md-6"></div>
                <!-- <div class="col-md-2">
                    <button style="display: none;" class="btn btn-primary btnAddTable">Add Table/Row</button>
                </div> -->

                <div class="col-md-2">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" style="display: none;" type="button" id="seatingDropdown"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            Seating Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="seatingDropdown">
                            <li><a class="dropdown-item" href="#" id="addTicket"><i class="fa-solid fa-ticket"></i> Add Ticket</a></li>
                            <li><a class="dropdown-item" href="#" id="addSection"><i class="fa-solid fa-square-plus"></i> Add Seating Section</a></li>
                            <li><a class="dropdown-item" href="#" id="updateSection"><i class="fa-solid fa-pen-to-square"></i> Update Seating Section</a></li>
                        </ul>
                    </div>
                </div>
            
           </div>

            <div id="seating-plan">
            
            </div>

        </div>
    </section>

    <!-- modal to create tables and seating capacity -->
    <div class="modal fade" id="generateTableAndSeatsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Table/Row & Seats</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="tableForm">
                        <input type="hidden" name="eventId" id="eventId2" value="">
                        
                        <div class="form-group">
                            <label for="tableNumber">Section Number</label>
                            <input type="text" class="form-control" name="tableNumber" id="tableNumber" placeholder="Enter section ID" required>
                        </div>

                        <div class="form-group">
                            <label for="tableName">Section Name</label>
                            <input type="text" class="form-control" name="tableName" id="tableName" placeholder="Enter section name">
                        </div>

                        <div class="form-group ticketTypeDropdown"></div>

                        <div class="form-group">
                            <label for="numSeats">Number of Seats for Table/Row</label>
                            <input type="number" class="form-control" name="numSeats" id="numSeats" placeholder="Enter Number of Seats for section" required min="1">
                        </div>
                        <div id="saveTableAndSeatsResponse" class="mt-3"></div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveTable()">Save</button>
                </div>
            </div>
        </div>
    </div>


    <!-- modal to show tickets -->
    <div class="modal fade" id="viewTicketSeatingPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body modalBodyForTicketView"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal to add more seating capacity -->
    <div class="modal fade" id="addMoreTicketsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add tickets to section</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="tableForm">
                        <input type="hidden" name="eventId" id="eventId1" value="">
                        
                        <select id="seatingSection" class="form-control">
                            <option value="">Select a Seating Section</option>
                        </select>

                        <div class="form-group">
                            <label for="numSeats">Number of Seats to add</label>
                            <input type="number" class="form-control getNewSeats" name="numSeats" id="numSeats" value="" placeholder="Enter Number of Seats for table/row" required min="0">
                        </div>

                        <div class="form-group seatsCalculation">
                            <label style="display: none;" id="lblCurrentSeats" for="currentSeats">Current Seats</label>
                            <input style="display: none;" type="number" class="form-control" name="currentSeats" id="currentSeats" readonly placeholder="Total current seats">

                            <label style="display:none;" for="totalSeats" id="lblTotalSeats">Total Seats After Addition</label>
                            <input style="display:none;" type="number" class="form-control" name="totalSeats" id="totalSeats" readonly placeholder="Total seats after addition">
                        </div>
                        <div id="addMoreSeatsResponse" class="mt-3"></div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="btnCancelAddSeatsModal()" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addSeatstoTable()" id="btnSaveSeats">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="assignTicketModalSeatPlanning" tabindex="-1" role="dialog" aria-labelledby="assignTicketLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTicketLabel">Assign Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form class="assignTicketModalFormSeatPlanning" method="post" action="{{route('assign-ticket-form-seating-plan')}}" >
                @csrf
                <input type="hidden" name="seatNumber" class="ticketSeatNumber"  />
                <input type="hidden" name="eventId" class="ticketEventId"  />
                <input type="hidden" name="tableNumber" class="ticketTableNumber"  />
                <div class="form-group">
                    <label for="leader_name">Guest Name</label>
                    <input type="text" class="form-control" name="holder_name" id="holder_name"  placeholder="Guest Name">
                </div>
                <div class="form-group">
                    <label for="leader_name">Leader</label>
                    <input type="text" class="form-control" name="leader_name" id="leader_name"  placeholder="Leader Name">
                </div>
                <div class="form-group">
                    <label for="leader_phone">Leader Phone</label>
                    <input type="text" class="form-control" name="leader_phone" id="leader_phone"  placeholder="Leader Phone">
                </div>
                <div class="form-group">
                    <label for="leader_email">Leader Email</label>
                    <input type="text" class="form-control" name="leader_email" id="leader_email"  placeholder="Leader Email">
                </div>
                <div class="form-group">
                    <label for="guest_phone">Guest Phone</label>
                    <input type="text" class="form-control" name="guest_phone" id="guest_phone"  placeholder="Guest Phone">
                </div>
                <div class="form-group">
                    <label for="guest_email">Guest email</label>
                    <input type="text" class="form-control" name="guest_email" id="guest_email"  placeholder="Guest email">
                </div>
                <div class="form-group">
                    <label for="table_name">Table name</label>
                    <input type="text" class="form-control" readonly name="table_name" id="table_name"  placeholder="Table name">
                </div>
                <div class="form-group">
                    <label for="table_num">Table number</label>
                    <input type="text" class="form-control" readonly name="table_num" id="table_num"  placeholder="Table number">
                </div>
                <div class="form-group">
                    <label for="amount">Amount Paid</label>
                    <input type="number" class="form-control" onkeyup="$('[name=bal]')[0].value = ticketprice-this.value" name="amount" id="amount" placeholder="Amount Paid">
                </div>
                <div class="form-group">
                    <label for="bal">Balance</label>
                    <input type="number" readonly class="form-control" name="bal" id="bal" placeholder="Balance">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            </div>
            </div>
        </div>
    </div>

    
</div>
</div>
</section>
</section>
@endsection