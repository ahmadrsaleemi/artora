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
                        Assign Tickets
                    </header>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <select onchange="window.location.href = './scanned-tickets?id='+this.value "  class="form-control">
                                    <option value="" selected>Select Event</option>
                                    @foreach ($allEvent as $allEvents)
                                        <option {{ $allEvents->eid == request()->id ? 'selected' : '' }} value="{{$allEvents->eid}}">{{$allEvents->title}}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <table id="ticketTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ticket Id</th>
                                            <th>Ticket Type</th>
                                            <th>Holder Name</th>
                                            <th>Holder Email</th>
                                            <th>Seat</th>
                                            <th>Scaned On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tickets as $ticket)
                                            <tr>
                                                <td>{{ $ticket->ticketId }}</td>
                                                <td>{{ $ticket->tickettype_name }}</td>
                                                <td>{{ $ticket->holder_name }}</td>
                                                <td>{{ $ticket->holder_email }}</td>
                                                <td>{{ $ticket->seatNumber }}</td>
                                                <td>{{ date('Y-m-d h:i:s A',strtotime($ticket->scaned_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" ><center><h3>No Tickets Scaned Yet!</h3></center></td>
                                                
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
@endsection
