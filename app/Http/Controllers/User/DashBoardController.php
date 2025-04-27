<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as StockRequest;
use App\Models\Stock;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        return view('user.dashboard', [
            'pendingRequestsCount' => StockRequest::where('user_id', $user->id)
                                        ->where('status', 'pending')
                                        ->count(),
            'approvedRequestsCount' => StockRequest::where('user_id', $user->id)
                                        ->where('status', 'approved')
                                        ->count(),
            'rejectedRequestsCount' => StockRequest::where('user_id', $user->id)
                                        ->where('status', 'rejected')
                                        ->count(),
            'recentRequests' => StockRequest::where('user_id', $user->id)
                                        ->with('user')
                                        ->latest()
                                        ->paginate(5),
            'lowStockItems' => Stock::where('quantity', '<', 50)->get()
        ]);
    }
}