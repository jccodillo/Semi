<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userDepartment = $user->department;
        $userBranch = $user->branch;
        
        // User can only see stocks from their own department and branch
        $query = Stock::where('department', $userDepartment)
                      ->where('branch', $userBranch);
        
        $stocks = $query->get();
    
        return view('user.tables', compact('stocks'));
    }
    
    public function showDetails($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $user = Auth::user();
            
            // For debugging
            \Log::info('User accessing stock detail', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'user_department' => $user->department,
                'user_branch' => $user->branch,
                'stock_id' => $stock->id,
                'stock_department' => $stock->department,
                'stock_branch' => $stock->branch
            ]);
            
            // Check if user has permission to view this item
            // Allow access if EITHER department OR branch matches
            if ($stock->department !== $user->department && $stock->branch !== $user->branch) {
                \Log::warning('User denied access to stock', [
                    'user_id' => $user->id,
                    'stock_id' => $stock->id,
                    'reason' => 'Department/branch mismatch'
                ]);
                
                return view('scan.error', [
                    'message' => 'You do not have permission to view this item.'
                ]);
            }
            
            return view('scan.item-details', compact('stock'));
        } catch (\Exception $e) {
            \Log::error('Error showing stock details', [
                'stock_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('scan.error', [
                'message' => 'Item not found or you do not have permission to view it.'
            ]);
        }
    }
}