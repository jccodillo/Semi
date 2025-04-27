<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($attributes)) {
            session()->regenerate();
            
            // Get the intended URL from session
            $intendedUrl = session('intended_url');
            
            // Clear the intended URL from session
            session()->forget('intended_url');
            
            // If there was an intended URL, redirect there
            if ($intendedUrl) {
                return redirect($intendedUrl);
            }
            
            // Otherwise, redirect based on user role
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Email or password invalid.']);
    }
    
    public function destroy()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'You\'ve been logged out.');
    }
}
