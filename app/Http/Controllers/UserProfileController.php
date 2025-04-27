<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:8',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:11 ',
            'location' => 'nullable|string',
            'about' => 'nullable|string'
        ]);

        auth()->user()->update($request->only(['name', 'email', 'phone', 'location', 'about']));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            
            // Store the file in storage/app/public/avatars
            $path = $avatar->storeAs('public/avatars', $filename);
            
            // Update user's avatar in database
            auth()->user()->update([
                'avatar' => Storage::url($path)
            ]);

            return response()->json([
                'success' => true,
                'path' => Storage::url($path)
            ]);
        }

        return response()->json(['success' => false]);
    }
}
