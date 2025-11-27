@extends('layout.parent')

@section('content')
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="card">
                    <header class="card-header">
                        @if(session('message'))
                            <div class="alert-danger alert">{{session('message')}}</div>
                        @endif
                        @if(session('message'))
                            <div class="alert">
                                <script> document.addEventListener("DOMContentLoaded", () => { toastr.info('{{session('message')}}'); }); </script>
                            </div>
                        @endif
                        Assign Tickets
                    </header>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <select name="selectEvent" onchange="getAllTickets(this)" id="selectEvent" class="form-control">
                                    <option value="" selected>Select Event</option>
                                    @foreach ($allEvent as $allEvents)
                                        <option {{ $allEvents->eid == request()->id ? 'selected' : '' }} value="{{$allEvents->eid}}">{{$allEvents->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        <div class="col-2">
                            <?php 
                                if(isset(request()->id))
                                {
                            ?>
                                <a href="{{ url('/ticket/Seat-Plan?id=') }}{{ request()->id }}" class="btn btn-info assignTicketViewSeatingPlan" title="View Seating Plan">View Seating Plan</a>
                            <?php 
                                }
                                else
                                {
                            ?>
                                <a href="" style="display: none;" class="btn btn-info assignTicketViewSeatingPlan" title="View Seating Plan">View Seating Plan</a>
                            <?php
                                }
                            ?>
                        </div>
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                getAllTickets(selectEvent)
                             });
                            </script>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="ticketTable" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 10%;">Ticket Id</th>
                                            <th style="width: 10%;">Ticket Type</th>
                                            <th style="width: 20%;">Holder Name</th>
                                            <th style="width: 20%;">Leader Name</th>
                                            <th style="width: 15%;">Table</th>
                                            <th style="width: 15%;">Seats</th>
                                            <th style="width: 10%;">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Ticket rows will be appended here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>



            <!-- Modal -->
            <div class="modal fade" id=" Modal" tabindex="-1" role="dialog" aria-labelledby="assignTicketLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignTicketLabel">Assign Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form class="assignTicketModalForm" method="post" action="{{route('assign-ticket-form')}}" >
                        @csrf
                        <input type="hidden" name="ticketId" class="ticketIdModalForm"  />
                        <input type="hidden" name="seatNumber" class="ticketSeatNumber"  />
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

            <div class="modal fade" id="ViewassignTicket" tabindex="-1" role="dialog" aria-labelledby="ViewassignTicketLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ViewassignTicketLabel">Assign Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form method="post" action="{{url('/assign-ticket')}}" >

                    </form>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    </div>
                    </div>
                </div>
            </div>
    </section>
</section>
@endsection
