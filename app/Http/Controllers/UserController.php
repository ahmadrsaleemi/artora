<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    //
    function addUser()
    {
        return view('pages.user.addUser');
    }
    function registerUser(Request $request)
    {
        $createUser=new User;
        $createUser->userFirstName=$request->userFirstName;
        $createUser->userLastName=$request->userLastName;
        $createUser->userEmail=$request->userEmail;
        $createUser->userPassword=Hash::make($request->userPassword);
        $createUser->userType=$request->userType;
        if($createUser->save())
        {

            return redirect()->back()->with("message","User Added SuccessFully!");
        }
        else
        {
            return redirect()->back()->with("message","User Not Added SuccessFully. Try Again!");
        }
    }
    function viewUser()
    {
        $users=user::all();
        return view('pages.user.viewUser', compact('users'));
        
    }
    function deleteUser($id)
    {
        $userDelete=user::find($id);
        if($userDelete->delete())
        {
            return redirect()->back()->with("message","User Deleted Successfully!");
        }
        else
        {
            return redirect()->back()->with("message","User Not Deleted Successfully. Try Again!");
        }
    }
    public function updateUser(Request $request)
{
    $user = User::find($request->input('userId'));
    
    if ($user) 
    {
        $user->userFirstName = $request->input('userFirstName');
        $user->userLastName = $request->input('userLastName');
        $user->userEmail = $request->input('userEmail');
        $user->userType = $request->input('userType');
        $user->save();
        
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 404);
}

}
