<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the admin profile page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('Admin.profile');
    }

    /**
     * Update the admin's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:8',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|integer|max:11', // Changed to string for phone numbers with special characters
            'location' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $userData = $request->only(['name', 'email', 'phone', 'location']);
        
        // Only update password if a new one is provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        Auth::user()->update($userData);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change the admin's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Upload and update admin's profile avatar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            Auth::user()->update([
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
