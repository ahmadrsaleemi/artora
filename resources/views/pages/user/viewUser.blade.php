@extends('layout.parent')

@section('content')
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="card">
                    <header class="card-header">
                        User Details
                    </header>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->userFirstName}}</td>
                                        <td>{{$user->userLastName}}</td>
                                        <td>{{$user->userEmail}}</td>
                                        <td>
                                            @if ($user->userType==0)
                                                Admin
                                            @else
                                                Event Organizer
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" 
                                            data-toggle="modal" 
                                            data-target="#editUserModal" 
                                            data-id="{{ $user->id }}"
                                            data-firstname="{{ $user->userFirstName }}"
                                            data-lastname="{{ $user->userLastName }}"
                                            data-email="{{ $user->userEmail }}"
                                            data-usertype="{{ $user->userType }}"
                                            class="btn btn-success edit-btn">Edit</a>
                                            <a href="deleteUser/{{$user->id}}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?');">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editUserForm">
                                            @csrf
                                            <input type="hidden" id="user-id" name="userId">
                                            <div class="form-row align-items-center">
                                                
                                                <div class="col-12">
                                                    
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text"><i class="fa fa-user"></i></div>
                                                        </div>
                                                        <input type="text" class="form-control" id="userFirstName" placeholder="First Name" name="userFirstName" required>
                                                        <input type="text" class="form-control" id="userLastName" placeholder="Last Name" name="userLastName" required>
                                                    </div>
                                                </div>
                            
                                                <div class="col-12">
                                                    
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text"><i class="fa-solid fa-envelope"></i></div>
                                                        </div>
                                                        <input type="email" class="form-control" id="userEmail" placeholder="Email" name="userEmail" required>
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12">
                                                    
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text"><i class="fa-solid fa-arrow-down"></i></div>
                                                        </div>
                                                        <select name="userType" class="form-control" id="userType">
                                                            <option value="" selected>Select User Type.</option>
                                                            <option value="0">Admin</option>
                                                            <option value="1">Event Organizer</option>
                                                        </select>
                                                       
                                                    </div>
                                                </div>
                                                
                            
                                                
                                                <div class="col-12">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="updateUserBtn" class="btn btn-primary px-5">Update</button>
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

    
