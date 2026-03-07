<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials))
        {
            $user = Auth::user();

            if($user->roles->contains('name','admin')){
                return redirect('/admin/dashboard');
            }

            if($user->roles->contains('name','cdc')){
                return redirect('/cdc/dashboard');
            }

            if($user->roles->contains('name','hod')){
                return redirect('/hod/dashboard');
            }

            if($user->roles->contains('name','faculty')){
                return redirect('/faculty/dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Invalid Email',
            'password' => 'Invalid Password'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
