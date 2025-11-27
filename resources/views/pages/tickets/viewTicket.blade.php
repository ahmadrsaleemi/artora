@extends('layout.parent')
@push('script')
<script type="text/javascript">
    function UnAssigned(node,id){
        if (!confirm(`Are you Sure want to UnAssign?`)) return '';
        let tr = $(node).parents('tr').find('td');
        for (var i = 3; i < tr.length; i++) {
            if (tr[i]) {
                tr[i].innerHTML = '';
            }
        }
        tr[3].innerHTML =`<span class="badge badge-success">Available</span>`;
        $.get('{{ route('unassign-ticket') }}',{id: id});
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
                    <script> document.addEventListener("DOMContentLoaded", () => { toastr.info('{{session('message')}}'); }); </script>
                </div>

            @endif
            View Tickets
        </header>
        <div class="card-body">
           <div class="row">
            <div class="col-md-4">
                <select name="eventSelect" id="eventSelect" class="form-control">
                    <option value="" selected>Select Event</option>
                    @foreach ($allEvent as $allEvent)
                        <option {{ $allEvent->eid == request()->id ? 'selected' : '' }} value="{{$allEvent->eid}}">{{$allEvent->title}}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-12 mt-5">
                <div class="table-responsive">
                    <table class="table" id="datatable-ajax">
                        <thead>
                            <tr>
                                <th>Ticket Id</th>
                                <th>Event Title</th>
                                <th>Ticket Type</th>
                                <th>Status</th>
                                <th>Holder Name</th>
                                <th>Leader Name</th>
                                <th>Seat Number</th>
                                <th>QR Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="ticketBody">

                        </tbody>
                    </table>
                    <div class="pagination-wrapper"></div>
                </div>
            </div>
           </div>

        </div>
    </section>

</div>
</div>
</section>
</section>
@endsection
