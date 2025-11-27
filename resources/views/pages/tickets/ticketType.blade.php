@extends('layout.parent')
@push('script')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
            // Use event delegation to handle clicks on the Edit buttons
            document.querySelectorAll('.type-edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Retrieve data attributes from the clicked button
                    const id = this.getAttribute('data-id');
                    const ticketType = this.getAttribute('data-ticketType');
        
                    // Set the values in the modal form
                    document.getElementById('editid').value = id;
                    document.getElementById('ticketType').value = ticketType;
                    
                });
            });
        });
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
            Add Ticket Type
        </header>
        <div class="card-body">
            <form action="{{url('ticketType')}}" method="POST">
                @csrf
                <label for="">Type Name</label>
                <input type="text" class="form-control mb-3" name="ticketType">
                <button class="btn btn-primary px-5">Add</button>
            </form>
            <hr>
            <header>Ticket Type List</header>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($TicketTypes as $type)
                        <tr>
                            <td>{{$type->id}}</td>
                            <td>{{$type->ticketType}}</td>
                            <td>
                                <a href="javascript:void(0);" 
                                data-toggle="modal" 
                                data-target="#edit" 
                                data-id="{{ $type->id }}"
                                data-ticketType="{{ $type->ticketType }}"
                                class="btn btn-success type-edit-btn">Edit</a>
                                <a href="{{ url('ticket/Ticket-Type/delete?id=').$type->id}}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?');">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade"  id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editLabel">Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="{{ route('ticketType.update') }}" >
                                @csrf
                                @method('put')
                                <input type="hidden" id="editid" name="id">
                                <div class="form-row align-items-center">
                                    
                                    <div class="col-12">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="ticketType" placeholder="Type Name" name="ticketType" required>
                                        </div>
                                    </div>
                
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary px-5">Update</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Edit Modal -->

        </div>
    </section>
    
</div>
</div>
</section>
</section>
@endsection