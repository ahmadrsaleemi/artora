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
            Add Event Type
        </header>
        <div class="card-body">
            <form action="{{url('addEventType')}}" method="POST">
                @csrf
                <label for="">Type Name</label>
                <input type="text" class="form-control mb-3" name="eventType">
                <button class="btn btn-primary px-5">Add</button>
            </form>
        </div>
    </section>
    
</div>
</div>
</section>
</section>
@endsection