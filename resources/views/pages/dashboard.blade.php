@extends('layout.parent')
@section('content')
<style>
    /*     Card     */
.card {
  border-radius: 0px;
  background-color: #ffffff;
  margin-bottom: 30px;
  -webkit-box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08);
  -moz-box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08);
  box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08);
  border: 1px solid #eee;
}
.card .card-header {
  padding: 15px 15px;
  background-color: transparent;
  border-bottom: 1px solid #ebedf2 !important;
}
.card .card-title {
  margin: 0;
  color: #575962;
  font-size: 18px;
  font-weight: 600;
  line-height: 1.6;
}
.card .card-sub {
  display: block;
  margin: 5px 0 10px 0;
  font-size: .9rem;
  background: #f7f8fa;
  color: #575962;
  padding: 0.85rem 1.5rem;
  border-radius: 4px;
}
.card .card-category {
  margin-top: 5px;
  font-size: 14px;
  font-weight: 400;
  color: #9A9A9A;
  margin-bottom: 0px;
}
.card label {
  font-size: 14px;
  font-weight: 400;
  color: #9A9A9A;
  margin-bottom: 0px;
}
.card .card-body {
  padding: 15px 15px 10px 15px;
}
.card .card-footer {
  background-color: transparent;
  line-height: 30px;
  border-top: 1px solid #ebedf2 !important;
  font-size: 14px;
}
.card .card-action {
  padding: 30px;
  background-color: transparent;
  line-height: 30px;
  border-top: 1px solid #ebedf2 !important;
  font-size: 14px;
}
.card .card-footer hr {
  margin-top: 5px;
  margin-bottom: 5px;
}
.card .card-footer .legend {
  display: inline-block;
}

.card-stats .card-category {
  margin-top: 0px;
}
.card-stats .icon-big {
  font-size: 3em;
  min-height: 64px;
}

.card-tasks .table thead th {
  border-top: 1px solid #f4f4f4;
  background: #fafafa !important;
}
.card-tasks .table .form-check {
  padding: 0 0 0 0.75rem !important;
}
.card-tasks .table .form-check label {
  margin-bottom: 0px !important;
}
.card-tasks .table tbody td:first-child, .card-tasks .table thead th:first-child {
  padding-left: 15px;
  padding-right: 15px;
}
.card-tasks .table tbody td:last-child, .card-tasks .table thead th:last-child {
  padding-right: 15px;
}
.card-tasks .card-body .table td {
  font-size: 14px;
  line-height: 1.8;
}
.card-tasks .card-body .table td .btn {
  font-size: 17px;
  opacity: 0.7;
  transition: all .3s;
}
.card-tasks .card-body .table td:hover .btn {
  opacity: 1;
}

.card-default, .card-primary, .card-info, .card-success, .card-warning, .card-danger {
  color: #ffffff;
  border: 0px;
}

.card-default .card-header, .card-primary .card-header, .card-info .card-header, .card-success .card-header, .card-warning .card-header, .card-danger .card-header {
  border-bottom: transparent !important;
}

.card-default .card-category, .card-primary .card-category, .card-info .card-category, .card-success .card-category, .card-warning .card-category, .card-danger .card-category, .card-default .card-title, .card-primary .card-title, .card-info .card-title, .card-success .card-title, .card-warning .card-title, .card-danger .card-title, .card-default label, .card-primary label, .card-info label, .card-success label, .card-warning label, .card-danger label {
  color: #ffffff;
}

.card-default .icon-big > i, .card-primary .icon-big > i, .card-info .icon-big > i, .card-success .icon-big > i, .card-warning .icon-big > i, .card-danger .icon-big > i {
  color: #ffffff !important;
}

.card-default .card-footer, .card-primary .card-footer, .card-info .card-footer, .card-success .card-footer, .card-warning .card-footer, .card-danger .card-footer {
  border-top: transparent !important;
}

.card-default {
  background: #607D8B;
}

.card-primary {
  background: #1D62F0;
}

.card-info {
  background: #23CCEF;
}

.card-success {
  background: #59d05d;
}

.card-warning {
  background: #fbad4c;
}

.card-danger {
  background: #ff646d;
}

/*     Progress Card    */
.progress-card {
  margin-bottom: 25px;
}

/* Row Card No Padding */
.row-card-no-pd {
  margin-left: 0;
  margin-right: 0;
  background: #ffffff;
  margin-bottom: 30px;
  padding-top: 30px;
  padding-bottom: 30px;
  position: relative;
}
.row-card-no-pd .card {
  margin-bottom: 0px;
  border-width: 0px;
  box-shadow: none;
  position: unset;
}
.row-card-no-pd [class*=col-] .card:after {
  position: absolute;
  height: calc(100%);
  width: 1px;
  background: #eee;
  content: '';
  right: 0px;
}
.row-card-no-pd [class*=col-] .card:after:last-child {
  width: 0px;
}
.la-users:before {
  content: "\f369";
}
@font-face {
  font-family: LineAwesome;
  src: url(../fonts/line-awesome.eot?v=1.1.);
  src: url(../fonts/line-awesome.eot??v=1.1.#iefix) format("embedded-opentype"), url(../fonts/line-awesome.woff2?v=1.1.) format("woff2"), url(../fonts/line-awesome.woff?v=1.1.) format("woff"), url(../fonts/line-awesome.ttf?v=1.1.) format("truetype"), url(../fonts/line-awesome.svg?v=1.1.#fa) format("svg");
  font-weight: 400;
  font-style: normal;
}
.la {
    font: normal normal normal 16px / 1 LineAwesome;
    font-size: inherit;
    text-decoration: inherit;
    text-rendering: optimizeLegibility;
    text-transform: none;
    -moz-osx-font-smoothing: grayscale;
    -webkit-font-smoothing: antialiased;
    font-smoothing: antialiased;
}
.la-bar-chart:before {
    content: "\f12e";
}
.la-newspaper-o:before {
    content: "\f29c";
}
.la-check-circle:before {
    content: "\f17c";
}
</style>
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <h1>Dashboard</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card card-stats card-warning">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-users"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Total Events</p>
                                    <h4 class="card-title">{{ \App\Models\Event::count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-success">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-bar-chart"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Upcoming Events</p>
                                    <h4 class="card-title">{{ \App\Models\Event::where('eventDate','>',date('Y-m-d'))->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-danger">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-newspaper-o"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Total Tickets</p>
                                    <h4 class="card-title">{{ $totalTickets }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-primary">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="la la-check-circle"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Tickets Sold</p>
                                    <h4 class="card-title">{{ $totalTicketsSold }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row" >
            <div class="col-12" >
            <section class="card">
        <header class="card-header">
            Upcoming Events            
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
                            <th>Contact Number</th>
                            <th>Contact Email</th>
<!--                             
                            <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allEvents as $allEvent)
                        <tr>
                            <td>{{ $allEvent->eid }}</td>
                            <td>{{ $allEvent->eventDate }}</td>
                            <td>{{ $allEvent->title }}</td>
                            <td>{{ $allEvent->address }}</td>
                            <td>{{ ucfirst($allEvent->detail->seatArrangement ?? '') }}</td>
                            <td><?php
                            foreach ($allEvent->tickettypes->pluck('ticketType','tickets')->toArray() as $total => $type) {
                                echo $type.':'.$total.'<br>';
                                }
                            ?></td>
                            <td>{{ $allEvent->contactNumber }}</td>
                            <td>{{ $allEvent->contactEmail }}</td>
                      
                            <!-- <td>
                                
                                   <a href="updateEventPage/{{ $allEvent->eid }}" class="btn btn-success">Edit</a>
                                <a href="deleteEvent/{{ $allEvent->eid }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?');">Delete</a>
                               
                                @if ($allEvent->ticketStatus==0)
                                    <a href="#" class="btn btn-success" onclick="return confirmAndOpenModal('{{ $allEvent->title }}','{{ $allEvent->eid }}');">Generate Tickets</a>
                                @else
                                    <a href="/ticket/View-Ticket" class="btn btn-primary">View Tickets</a>

                                @endif
                            </td> -->
                        </tr>
                        @endforeach
                        @if(count($allEvents) == 0)
                        <tr>
                            <td colspan="8" style="text-align: center;" >
                                <h3>No Upcoming Events! </h3>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
        </div>
    </section>
            </div>
        </div>

    </section>
    
</section>
@endsection