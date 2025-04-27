<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuppliesInventory;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SupplyInventoryController extends Controller
{
    public function create()
    {
        $units = [
            'Box', 'Piece', 'Pack', 'Ream', 'Roll', 'Bottle', 
            'Cartridges', 'Gallon', 'Litre', 'Meter', 'Pound', 'Sheet'
        ];
        
        return view('Admin.stock.supplyinventory', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit_type' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:255'
        ]);

        try {
            // Check if an item with the same name and unit type exists
            $existingSupply = SuppliesInventory::where('product_name', $validated['product_name'])
                                             ->where('unit_type', $validated['unit_type'])
                                             ->first();

            if ($existingSupply) {
                // Update existing supply quantity
                $existingSupply->quantity += $validated['quantity'];
                $existingSupply->save();

                return redirect()->route('admin.inventory')
                               ->with('success', 'Supply quantity updated successfully');
            }

            // If no existing supply found, create new one
            $supply = new SuppliesInventory();
            $supply->control_code = 'SUP-' . strtoupper(Str::random(8));
            $supply->product_name = $validated['product_name'];
            $supply->quantity = $validated['quantity'];
            $supply->unit_type = $validated['unit_type'];
            $supply->description = $validated['description'];
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('supplies', 'public');
                $supply->product_image = $path;
            }

            $supply->save();
            // Record the initial transaction
            SupplyTransaction::create([
                'supply_id' => $supply->id,
                'transaction_type' => 'receipt',
                'quantity' => $validated['quantity'],
                'reference_number' => 'INIT-' . strtoupper(Str::random(6)),
                'balance' => $validated['quantity'],
                'office' => null,
                'days_to_consume' => null,
            ]);
                        // Record the initial transaction
            SupplyTransaction::create([
                'supply_id' => $supply->id,
                'transaction_type' => 'receipt',
                'quantity' => $validated['quantity'],
                'reference_number' => 'INIT-' . strtoupper(Str::random(6)),
                'balance' => $validated['quantity'],
                'office' => null,
                'days_to_consume' => null,
            ]);

            return redirect()->route('admin.inventory')
                           ->with('success', 'Supply item added successfully');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Failed to add supply item. Please try again.']);
        }
    }
    

    public function index()
    {
        $supplies = SuppliesInventory::orderBy('product_name', 'asc')->paginate(200);
        return view('admin.inventory', compact('supplies'));
    }

    public function edit($id)
    {
        $supply = SuppliesInventory::findOrFail($id);
        return view('Admin.stock.edit-supply', compact('supply'));
    }

    public function update(Request $request, $id)
    {
        $supply = SuppliesInventory::findOrFail($id);
        
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Add the new quantity to the existing quantity
        $oldQuantity = $supply->quantity;
        $supply->quantity += $validated['quantity'];
        $supply->save();
        SupplyTransaction::create([
            'supply_id' => $supply->id,
            'transaction_type' => 'receipt',
            'quantity' => $validated['quantity'],
            'reference_number' => 'RCV-' . strtoupper(Str::random(6)),
            'balance' => $supply->quantity,
            'office' => null,
            'days_to_consume' => null,
        ]);
        
        return redirect()->route('admin.inventory')
            ->with('success', 'Supply quantity updated successfully');
    }

    public function destroy($id)
    {
        try {
            $supply = SuppliesInventory::findOrFail($id);
            
            // Delete image if exists
            if ($supply->product_image) {
                Storage::disk('public')->delete($supply->product_image);
            }
            
            $supply->delete();

            return redirect()->route('admin.inventory')
                           ->with('success', 'Supply item deleted successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete supply item. Please try again.']);
        }
    }

    
    public function stockCard($id)
    {
        $supply = SuppliesInventory::findOrFail($id);
        $transactions = SupplyTransaction::where('supply_id', $id)
                        ->orderBy('created_at', 'asc')
                        ->get();
        
        return view('Admin.stock.stock-card', compact('supply', 'transactions'));
    }

    /**
     * Handle issuance of supplies (outgoing stock)
     */
    public function issuance($id)
    {
        $supply = SuppliesInventory::findOrFail($id);
        return view('Admin.stock.issue-supply', compact('supply'));
    }

    /**
     * Process the issuance of supplies
     */
    public function processIssuance(Request $request, $id)
    {
        $supply = SuppliesInventory::findOrFail($id);
        
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $supply->quantity,
            'office' => 'required|string|max:255',
        ]);
        
        // Subtract the issued quantity from the existing quantity
        $oldQuantity = $supply->quantity;
        $supply->quantity -= $validated['quantity'];
        $supply->save();
        
        // Record the transaction
        SupplyTransaction::create([
            'supply_id' => $supply->id,
            'transaction_type' => 'issuance',
            'quantity' => $validated['quantity'],
            'reference_number' => 'ISS-' . strtoupper(Str::random(6)),
            'balance' => $supply->quantity,
            'office' => $validated['office'],
            'days_to_consume' => null,
        ]);
        
        return redirect()->route('admin.inventory')
            ->with('success', 'Supply issuance processed successfully');
    }

    /**
     * Show issuance form for an approved request
     */
    public function issueRequestItems($requestId)
    {
        // Find the request
        $mainRequest = \App\Models\Request::where('request_id', $requestId)->first();
        
        if (!$mainRequest || $mainRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Request not found or not approved.');
        }
        
        // Get items for this request
        $stockItems = \App\Models\StockRequestItem::where('stock_request_id', $requestId)->get();
        
        // If no stock items found, try using the legacy format
        if ($stockItems->count() === 0) {
            $stockItems = \App\Models\Request::where('request_id', $requestId)->get();
        }
        
        // Find matching inventory items
        $inventoryItems = [];
        foreach ($stockItems as $item) {
            $productName = $item->product_name;
            $unitType = $item->category;
            $requestedQty = $item->quantity;
            
            $inventoryItem = SuppliesInventory::where('product_name', $productName)
                ->where('unit_type', $unitType)
                ->first();
                
            if ($inventoryItem) {
                $inventoryItems[] = [
                    'inventory' => $inventoryItem,
                    'requested_qty' => $requestedQty
                ];
            }
        }
        
        return view('Admin.stock.issue-request-items', compact('mainRequest', 'inventoryItems', 'stockItems'));
    }
    
    /**
     * Process issuance of items from an approved request
     */
    public function processRequestIssuance(Request $request, $requestId)
    {
        // Validate request
        $validated = $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'required|integer|exists:supplies_inventory,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);
        
        // Start transaction
        \DB::beginTransaction();
        
        try {
            $mainRequest = \App\Models\Request::where('request_id', $requestId)->first();
            
            if (!$mainRequest || $mainRequest->status !== 'approved') {
                return redirect()->back()->with('error', 'Request not found or not approved.');
            }
            
            // Process each item
            foreach ($validated['item_ids'] as $index => $supplyId) {
                $supply = SuppliesInventory::findOrFail($supplyId);
                $quantity = $validated['quantities'][$index];
                
                // Check if there's enough quantity
                if ($supply->quantity < $quantity) {
                    \DB::rollBack();
                    return redirect()->back()->with('error', "Insufficient stock for {$supply->product_name}. Available: {$supply->quantity}, Requested: {$quantity}");
                }
                
                // Subtract the issued quantity
                $supply->quantity -= $quantity;
                $supply->save();
                
                // Record the transaction
                SupplyTransaction::create([
                    'supply_id' => $supply->id,
                    'transaction_type' => 'issuance',
                    'quantity' => $quantity,
                    'reference_number' => 'REQ-' . $requestId . '-' . strtoupper(Str::random(3)),
                    'balance' => $supply->quantity,
                    'office' => $mainRequest->department,
                    'days_to_consume' => null,
                ]);
            }
            
            // Mark the request as fulfilled if needed
            // This is optional - you might want to add a 'fulfilled' status
            
            \DB::commit();
            return redirect()->route('admin.requests.index')->with('success', 'Request items issued successfully.');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error issuing items: ' . $e->getMessage());
        }
    }

}
