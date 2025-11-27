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
            Add User
        </header>
        <div class="card-body">
            <form method="POST" action="{{url('registerUser')}}">
                @csrf
                <div class="form-row align-items-center">
                    
                    <div class="col-12">
                        
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-user"></i></div>
                            </div>
                            <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="First Name" name="userFirstName" required>
                            <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Last Name" name="userLastName" required>
                        </div>
                    </div>

                    <div class="col-12">
                        
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa-solid fa-envelope"></i></div>
                            </div>
                            <input type="email" class="form-control" id="inlineFormInputGroup" placeholder="Email" name="userEmail" required>
                           
                        </div>
                    </div>
                    <div class="col-6">
                        
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa-solid fa-lock"></i></div>
                            </div>
                            <input type="password" class="form-control" id="inlineFormInputGroup" placeholder="Enter Password" name="userPassword" required>
                           
                        </div>
                    </div>
                    <div class="col-6">
                        
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa-solid fa-arrow-down"></i></div>
                            </div>
                            <select name="userType" class="form-control" id="">
                                <option value="" selected>Select User Type.</option>
                                <option value="0">Admin</option>
                                <option value="1">Event Organizer</option>
                            </select>
                           
                        </div>
                    </div>
                    

                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mb-2 px-5">Add</button>
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