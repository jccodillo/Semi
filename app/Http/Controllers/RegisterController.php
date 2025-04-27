<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function create()
    {
        return view('session.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:user,admin'],
            'department' => ['required', 'string'],
            'branch' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department' => $request->department,
            'branch' => $request->branch,
        ]);

        session()->flash('success', 'Your account has been created.');
        Auth::login($user);

        // Check user role and redirect accordingly
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
        } else {
            return redirect()->route('user.dashboard')->with('success', 'Welcome!');
        }
    }
}
