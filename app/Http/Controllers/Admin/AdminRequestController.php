<?php

namespace App\Http\Controllers\Admin;

use App\Models\Request;
use App\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.role:admin');
    }

    public function index()
    {
        // Get unique requests with aggregated information
        $requests = Request::with(['user', 'approver'])
                         ->select([
                             'request_id',
                             'department',
                             'branch',
                             'status',
                             'user_id',
                             'created_at',
                             DB::raw('COUNT(*) as item_count'),
                             DB::raw('MAX(id) as id'),
                             DB::raw('MAX(approved_by) as approved_by'),
                             DB::raw('MAX(approved_at) as approved_at'),
                         ])
                         ->groupBy('request_id', 'department', 'branch', 'status', 'user_id', 'created_at')
                         ->latest('created_at')
                         ->get();

        // Get stock request items from StockRequestItem model
        $stockItems = \App\Models\StockRequestItem::whereIn('stock_request_id', $requests->pluck('request_id'))
                    ->get()
                    ->groupBy('stock_request_id');
        
        // Attach items to each request
        foreach ($requests as $request) {
            $request->stockItems = $stockItems[$request->request_id] ?? collect();
        }

        // For backwards compatibility with existing view
        $requestItems = $stockItems;

        return view('Admin.adminviewreq', compact('requests', 'requestItems'));
    }

    public function updateStatus(HttpRequest $request, $requestId)
    {
        try {
            \Log::info('Starting updateStatus', [
                'requestId' => $requestId,
                'input' => $request->all(),
                'method' => $request->method(),
                'url' => $request->url(),
                'ajax' => $request->ajax(),
                'user_id' => Auth::id(),
                'path' => $request->path(),
                'full_url' => $request->fullUrl()
            ]);

            // Find the main request
            $mainRequest = Request::where('request_id', $requestId)->first();
            \Log::info('Main request found', ['mainRequest' => $mainRequest]);

            if (!$mainRequest) {
                \Log::error('Request not found', ['requestId' => $requestId]);
                return redirect()->back()->with('error', 'Request not found.');
            }

            $validated = $request->validate([
                'status' => 'required|in:approved,rejected',
                'remarks' => 'required|string'
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            // Start a transaction
            DB::beginTransaction();
            
            try {
                // Update all associated requests with the same request_id
                $affected = DB::table('requests')
                    ->where('request_id', $requestId)
                    ->update([
                        'status' => $validated['status'],
                        'remarks' => $validated['remarks'],
                        'approved_by' => Auth::id(),
                        'approved_at' => now()
                    ]);

                \Log::info('Requests updated', ['affected' => $affected]);

                // If requests are approved, update inventory and prepare for stock creation
                if ($validated['status'] === 'approved') {
                    // Get stock items for this request
                    $stockItems = \App\Models\StockRequestItem::where('stock_request_id', $requestId)->get();
                    \Log::info('Stock items found', ['count' => $stockItems->count(), 'items' => $stockItems]);
                    
                    // Send a message to the user about the approved request
                    \Log::info('Sending approval message to user');
                    try {
                        $this->sendApprovalMessage($mainRequest, $validated['remarks'], $stockItems);
                        \Log::info('Approval message sent successfully');
                    } catch (\Exception $e) {
                        \Log::error('Error sending approval message', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                    
                    // If we have stock items, use those
                    if ($stockItems->count() > 0) {
                        foreach ($stockItems as $item) {
                            // Find the corresponding inventory item
                            $inventoryItem = \App\Models\SuppliesInventory::where('product_name', $item->product_name)
                                ->where('unit_type', $item->category)
                                ->first();

                            \Log::info('Processing inventory item', [
                                'product' => $item->product_name,
                                'found' => $inventoryItem ? true : false,
                                'inventory' => $inventoryItem
                            ]);

                            if ($inventoryItem) {
                                // Check if there's enough quantity
                                if ($inventoryItem->quantity < $item->quantity) {
                                    DB::rollBack();
                                    return redirect()->back()->with('error', "Insufficient stock for {$item->product_name}. Available: {$inventoryItem->quantity}, Requested: {$item->quantity}");
                                }

                                // Decrease inventory quantity
                                $inventoryItem->quantity -= $item->quantity;
                                $inventoryItem->save();
                                \Log::info('Inventory updated', [
                                    'item' => $item->product_name,
                                    'new_quantity' => $inventoryItem->quantity
                                ]);
                            }
                        }

                        // Prepare data for stock creation
                        $requestData = $stockItems->map(function($item) use ($mainRequest) {
                            return [
                                'product_name' => $item->product_name,
                                'quantity' => $item->quantity,
                                'price' => $item->price,
                                'department' => $mainRequest->department,
                                'branch' => $mainRequest->branch,
                                'category' => $item->category,
                            ];
                        })->toArray();

                        \Log::info('Prepared request data for session', ['requestData' => $requestData]);

                        // Store request data in session and redirect
                        session(['request_data' => $requestData]);
                        \Log::info('Session data stored', ['session' => session()->all()]);
                        
                        DB::commit();
                        \Log::info('Transaction committed successfully');
                        
                        return redirect()->route('admin.stocks.create')->with('success', 'Request approved successfully and inventory updated.');
                    } else {
                        // Legacy support for old requests without stock items
                        $oldRequests = Request::where('request_id', $requestId)->get();
                        \Log::info('Using legacy requests', ['count' => $oldRequests->count(), 'requests' => $oldRequests]);

                        foreach ($oldRequests as $oldRequest) {
                            // Find the corresponding inventory item
                            $inventoryItem = \App\Models\SuppliesInventory::where('product_name', $oldRequest->product_name)
                                ->where('unit_type', $oldRequest->category)
                                ->first();

                            if ($inventoryItem) {
                                // Check if there's enough quantity
                                if ($inventoryItem->quantity < $oldRequest->quantity) {
                                    DB::rollBack();
                                    return redirect()->back()->with('error', "Insufficient stock for {$oldRequest->product_name}. Available: {$inventoryItem->quantity}, Requested: {$oldRequest->quantity}");
                                }

                                // Decrease inventory quantity
                                $inventoryItem->quantity -= $oldRequest->quantity;
                                $inventoryItem->save();
                                \Log::info('Inventory updated (legacy)', [
                                    'item' => $oldRequest->product_name,
                                    'new_quantity' => $inventoryItem->quantity
                                ]);
                            }
                        }

                        $requestData = $oldRequests->map(function($request) {
                            return [
                                'product_name' => $request->product_name,
                                'quantity' => $request->quantity,
                                'price' => $request->price,
                                'department' => $request->department,
                                'branch' => $request->branch,
                                'category' => $request->category,
                            ];
                        })->toArray();

                        \Log::info('Prepared legacy request data for session', ['requestData' => $requestData]);

                        // Store request data in session and redirect
                        session(['request_data' => $requestData]);
                        \Log::info('Legacy session data stored', ['session' => session()->all()]);
                        
                        DB::commit();
                        \Log::info('Transaction committed successfully');
                        
                        return redirect()->route('admin.stocks.create')->with('success', 'Request approved successfully and inventory updated.');
                    }
                } else if ($validated['status'] === 'rejected') {
                    // Send rejection message to user
                    \Log::info('Sending rejection message to user');
                    try {
                        $this->sendRejectionMessage($mainRequest, $validated['remarks']);
                        \Log::info('Rejection message sent successfully');
                    } catch (\Exception $e) {
                        \Log::error('Error sending rejection message', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }

                // If rejected, just commit and go back
                DB::commit();
                \Log::info('Transaction committed successfully for rejection');
                return redirect()->back()->with('success', 'Request has been rejected successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error in transaction', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error in updateStatus:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Send approval message to user
     */
    private function sendApprovalMessage($request, $remarks, $stockItems = null)
    {
        try {
            \Log::info('Starting sendApprovalMessage', [
                'request_id' => $request->request_id,
                'user_id' => $request->user_id,
                'stock_items_count' => $stockItems ? $stockItems->count() : 0
            ]);
            
            // Check if user_id exists before sending message
            if (!$request->user_id) {
                \Log::error('Cannot send message: user_id is null', [
                    'request' => $request
                ]);
                return false;
            }
            
            // Check if the remarks already contain a formatted approval message
            // This happens when using the pre-generated remarks from the modal
            if (strpos($remarks, 'REQUEST APPROVAL NOTIFICATION') !== false || 
                strpos($remarks, 'Your request (ID:') !== false) {
                // Just use the remarks as the message content
                $messageContent = $remarks;
            } else {
                // Get the next Thursday for pickup
                $today = now();
                $dayOfWeek = $today->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
                $daysUntilThursday = (4 - $dayOfWeek + 7) % 7; // Thursday is day 4
                
                // If today is Thursday and it's before 5 PM, pickup is today
                // Otherwise, pickup is next Thursday
                if ($dayOfWeek === 4 && $today->hour < 17) {
                    $pickupDate = $today->format('l, F j, Y');
                } else {
                    $pickupDate = $today->addDays($daysUntilThursday)->format('l, F j, Y');
                }
                
                // Create message content with request details - using better formatting with clear section breaks
                $messageContent = "REQUEST APPROVAL NOTIFICATION\n";
                
                $messageContent .= "Your request [{$request->request_id}] has been APPROVED.\n";
                $messageContent .= "Department: {$request->department}\n";
                $messageContent .= "Branch: {$request->branch}\n";
                $messageContent .= "Date Requested: {$request->created_at->format('M d, Y')}\n\n";
                
                $messageContent .= "REQUESTED ITEMS\n";
                $messageContent .= "===================================\n";
                
                // Add items to the message with improved formatting
                if ($stockItems && $stockItems->count() > 0) {
                    foreach ($stockItems as $index => $item) {
                        // Try to find the inventory item to get the control code
                        $inventoryItem = \App\Models\SuppliesInventory::where('product_name', $item->product_name)
                            ->where('unit_type', $item->category)
                            ->first();

                        $stockNo = ($inventoryItem ? $inventoryItem->control_code : ($item->control_code ?? 'N/A'));
                        
                        $messageContent .= "Item #" . ($index + 1) . "\n";
                        $messageContent .= "Stock No: " . $stockNo . "\n";
                        $messageContent .= "Item: {$item->product_name}\n";
                        $messageContent .= "Quantity: {$item->quantity} {$item->category}\n";
                        $messageContent .= "Price: ₱" . number_format($item->price ?? 0, 2) . "\n\n";
                    }
                } else {
                    // Legacy support for requests without stock items
                    // Try to find the inventory item to get the control code
                    $inventoryItem = \App\Models\SuppliesInventory::where('product_name', $request->product_name)
                        ->where('unit_type', $request->category)
                        ->first();

                    $stockNo = ($inventoryItem ? $inventoryItem->control_code : ($request->control_code ?? 'N/A'));
                    
                    $messageContent .= "Item #1\n";
                    $messageContent .= "Stock No: " . $stockNo . "\n";
                    $messageContent .= "Item: {$request->product_name}\n";
                    $messageContent .= "Quantity: {$request->quantity} {$request->category}\n";
                    $messageContent .= "Price: ₱" . number_format($request->price ?? 0, 2) . "\n\n";
                }
                
                $messageContent .= "PICKUP INFORMATION\n";
                $messageContent .= "===================================\n";
                $messageContent .= "Date: {$pickupDate}\n";
                $messageContent .= "Location: Supply Office\n";
                $messageContent .= "Time: 6:00 AM - 4:00 PM\n";
                $messageContent .= "Important: Please bring your ID and request reference\n";
                $messageContent .= "number when collecting your supplies.\n\n";
                
                $messageContent .= "REMARKS\n";
                $messageContent .= "===================================\n";
                $messageContent .= "{$remarks}\n\n";
                
                $messageContent .= "Thank you for using our request system!";
            }
            
            \Log::info('Creating message', [
                'sender_id' => Auth::id(),
                'receiver_id' => $request->user_id,
                'message_length' => strlen($messageContent)
            ]);
            
            try {
                // Send message to the user
                $message = Message::create([
                    'sender_id' => Auth::id(),
                    'receiver_id' => $request->user_id,
                    'message' => $messageContent,
                    'is_read' => 0
                ]);
                
                \Log::info('Message created successfully', [
                    'message_id' => $message->id
                ]);
                
                return true;
            } catch (\Exception $e) {
                \Log::error('Error creating message record', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Error in sendApprovalMessage', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
    
    /**
     * Send rejection message to user
     */
    private function sendRejectionMessage($request, $remarks)
    {
        try {
            \Log::info('Starting sendRejectionMessage', [
                'request_id' => $request->request_id,
                'user_id' => $request->user_id
            ]);
            
            // Check if user_id exists before sending message
            if (!$request->user_id) {
                \Log::error('Cannot send rejection message: user_id is null', [
                    'request' => $request
                ]);
                return false;
            }
            
            // Create message content with plain text formatting
            $messageContent = "REQUEST REJECTION NOTICE\n";
            
            $messageContent .= "Your request [{$request->request_id}] has been REJECTED.\n";
            
            $messageContent .= "REQUEST DETAILS\n";
            $messageContent .= "===================================\n\n";
            $messageContent .= "Department: {$request->department}\n";
            $messageContent .= "Branch: {$request->branch}\n";
            $messageContent .= "Date Requested: {$request->created_at->format('M d, Y')}\n\n";
            
            $messageContent .= "REASON FOR REJECTION\n";
            $messageContent .= "===================================\n\n";
            $messageContent .= "{$remarks}\n\n";
            
            $messageContent .= "Important: If you have any questions or need further clarification,\n";
            $messageContent .= "please contact the supply office.\n\n";
            
            $messageContent .= "Thank you for your understanding.";
            
            \Log::info('Creating rejection message', [
                'sender_id' => Auth::id(),
                'receiver_id' => $request->user_id,
                'message_length' => strlen($messageContent)
            ]);
            
            try {
                // Send message to the user
                $message = Message::create([
                    'sender_id' => Auth::id(),
                    'receiver_id' => $request->user_id,
                    'message' => $messageContent,
                    'is_read' => 0
                ]);
                
                \Log::info('Rejection message created successfully', [
                    'message_id' => $message->id
                ]);
                
                return true;
            } catch (\Exception $e) {
                \Log::error('Error creating rejection message record', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Error in sendRejectionMessage', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    public function approve(Request $request)
    {
        return $this->updateStatus($request, [
            'status' => 'approved',
            'remarks' => request('remarks')
        ]);
    }

    public function reject(Request $request)
    {
        return $this->updateStatus($request, [
            'status' => 'rejected',
            'remarks' => request('remarks')
        ]);
    }
}
