<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a list of users to chat with
     */
    public function index()
    {
        $users = [];
        if (Auth::user()->isAdmin()) {
            // Admins can see all users
            $users = User::where('role', 'user')->get();
        } else {
            // Users can only see admins
            $users = User::where('role', 'admin')->get();
        }
        
        return view('messages.index', compact('users'));
    }
    
    /**
     * Display chat with a specific user
     */
    public function chat($userId)
    {
        $user = User::findOrFail($userId);
        
        // Check if the current user can chat with the selected user
        if (Auth::user()->isAdmin() && $user->isAdmin() && $user->id != Auth::id()) {
            return redirect()->route('messages.index')->with('error', 'You can only chat with users.');
        }
        
        if (Auth::user()->isUser() && $user->isUser()) {
            return redirect()->route('messages.index')->with('error', 'You can only chat with admins.');
        }
        
        // Mark all unread messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
        
        // Get messages between these two users
        $messages = Message::where(function($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $userId);
        })->orWhere(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();
        
        return view('messages.chat', compact('user', 'messages'));
    }
    
    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);
        
        $receiver = User::findOrFail($request->receiver_id);
        
        // Check if the current user can chat with the selected user
        if (Auth::user()->isAdmin() && $receiver->isAdmin() && $receiver->id != Auth::id()) {
            return redirect()->back()->with('error', 'You can only chat with users.');
        }
        
        if (Auth::user()->isUser() && $receiver->isUser()) {
            return redirect()->back()->with('error', 'You can only chat with admins.');
        }
        
        // Create the message
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => 0
        ]);
        
        return redirect()->back();
    }
    
    /**
     * Get count of unread messages
     */
    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
                      ->where('is_read', 0)
                      ->count();
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Get count of unread messages from a specific user
     */
    public function getUnreadFromUser($userId)
    {
        $count = Message::where('sender_id', $userId)
                      ->where('receiver_id', Auth::id())
                      ->where('is_read', 0)
                      ->count();
        
        return response()->json(['count' => $count]);
    }
}
