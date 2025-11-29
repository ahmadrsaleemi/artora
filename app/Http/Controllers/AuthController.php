<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function login(Request $request)
    {
        $credentials = [
            'email' => $request->input('userEmail'),
            'password' => $request->input('userPassword')
        ];
       if(Auth::attempt($credentials))
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
