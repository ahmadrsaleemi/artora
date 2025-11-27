<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    function login(Request $request)
    {
        $credentials = [
            'userEmail' => $request->input('userEmail'),
            'password' => $request->input('userPassword') 
        ];     
    
        if(Auth::attempt($credentials, $request->filled('remember')))
        {
      
            return redirect()->intended(route('dashboard'));
        }
        else
        {
            return redirect()->back()->with(['msg'=>'Your Credentials are not correct! Try Again!!!']);
        }
    }
    
    function logout()
    {
        Auth::logout();
        return redirect('/login'); 
    }
}
