<?php

namespace App\Http\Controllers\User;

use App\Models\Request;
use App\Models\StockRequestItem;
use App\Models\SuppliesInventory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.role:user');
    }

    public function create()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get supplies with proper ordering
        $supplies = SuppliesInventory::select('id', 'product_name', 'quantity', 'unit_type')
            ->orderBy('product_name')
            ->get();

        // Check if user has department and branch set
        if (!$user->department || !$user->branch) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please set your department and branch in your profile first.');
        }

        return view('User.requests.createreq', compact('supplies'));
    }

    public function store(HttpRequest $request)
    {
        // Log the incoming request data for debugging
        \Log::info('Request data received in store method:', $request->all());
        
        try {
            // Validate the request data
            $validated = $request->validate([
                'department' => 'required|string|max:255',
                'branch' => 'required|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.product_name' => 'required|string|max:255',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.category' => 'required|string|max:255',
            ]);
            
            \Log::info('Validation passed:', $validated);

            // Start a database transaction
            DB::beginTransaction();
            
            try {
                // Create the main request record - let the model generate the request_id
                $stockRequest = new Request();
                $stockRequest->department = $request->department;
                $stockRequest->branch = $request->branch;
                $stockRequest->user_id = Auth::id();
                $stockRequest->status = 'pending';
                
                // We need to set these fields to satisfy the database constraints
                // These will be dummy values for the main request record
                $stockRequest->product_name = 'Multiple Items';
                $stockRequest->quantity = 0;
                $stockRequest->price = 0;
                $stockRequest->category = 'Multiple';
                
                $stockRequest->save();
                
                // Now we have the generated request_id
                \Log::info('Main request created:', [
                    'id' => $stockRequest->id,
                    'request_id' => $stockRequest->request_id
                ]);
                
                // Create the request items using request_id as the stock_request_id
                $itemsCreated = 0;
                foreach ($request->items as $item) {
                    if (!empty($item['product_name']) && !empty($item['quantity']) && !empty($item['category'])) {
                        \Log::info('Creating item:', [
                            'request_id' => $stockRequest->request_id,
                            'product_name' => $item['product_name'],
                            'quantity' => $item['quantity'],
                            'category' => $item['category']
                        ]);
                        
                        $requestItem = new StockRequestItem();
                        $requestItem->stock_request_id = $stockRequest->request_id; // Use the string request_id
                        $requestItem->product_name = $item['product_name'];
                        $requestItem->quantity = $item['quantity'];
                        $requestItem->price = $item['price'] ?? 0;
                        $requestItem->category = $item['category'];
                        $requestItem->save();
                        
                        \Log::info('Item saved with ID:', ['id' => $requestItem->id]);
                        
                        $itemsCreated++;
                    }
                }
                
                \Log::info('Items created:', ['count' => $itemsCreated]);
                
                if ($itemsCreated === 0) {
                    throw new \Exception('No valid items were found in the request.');
                }
                
                DB::commit();
                \Log::info('Transaction committed successfully');
                
                return redirect()->route('user.requests.viewrequests')
                    ->with('success', 'Stock request created successfully!');
                
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error in transaction:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
            
        } catch (\Exception $e) {
            \Log::error('Error creating request:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to create stock request: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function index()
    {
        // Get unique request IDs and their primary info
        $requests = Request::select('request_id', 'department', 'branch', 'status', 'user_id', 'created_at')
                    ->where('user_id', Auth::id())
                    ->groupBy('request_id', 'department', 'branch', 'status', 'user_id', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Load all stock request items for all the user's requests
        $stockItems = StockRequestItem::whereIn('stock_request_id', $requests->pluck('request_id'))
                    ->get()
                    ->groupBy('stock_request_id');

        // Combine the data
        foreach ($requests as $request) {
            $request->items = $stockItems[$request->request_id] ?? collect();
        }
        
        \Log::info('Requests with items:', [
            'requests' => $requests->map(function($req) {
                return [
                    'request_id' => $req->request_id,
                    'status' => $req->status,
                    'item_count' => $req->items->count(),
                    'items' => $req->items->map(function($item) {
                        return [
                            'product_name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'category' => $item->category
                        ];
                    })
                ];
            })
        ]);
        
        return view('User.requests.viewrequests', compact('requests'));
    }
}