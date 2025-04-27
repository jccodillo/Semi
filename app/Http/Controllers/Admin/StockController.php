<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockEditHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $query = Stock::query();
        
        // Apply college filter if provided
        if (request()->has('college')) {
            $query->where('department', request('college'));
        }
        
        $stocks = $query->latest()->paginate(10);
        return view('admin.tables', compact('stocks'));
    }

    public function create()
    {
        // Get request data from session if it exists
        $requestData = session('request_data', []);
        
        \Log::info('Stock create view - session data:', ['request_data' => $requestData]);
        
        return view('admin.stock.create', compact('requestData'));
    }

    public function createDirect()
    {
        $units = [
            'Box', 'Piece', 'Pack', 'Ream', 'Roll', 'Bottle', 
            'Cartridges', 'Gallon', 'Litre', 'Meter', 'Pound', 'Sheet'
        ];
        
        $departments = [
            'COLLEGE OF ENGINEERING',
            'COLLEGE OF ARTS AND SCIENCES',
            'COLLEGE OF EDUCATION',
            'COLLEGE OF BUSINESS',
            'ADMINISTRATION'
        ];
        
        return view('admin.stock.add-supply', compact('units', 'departments'));
    }

    public function store(Request $request)
    {
        try {
            if (!$request->has('request_data')) {
                return redirect()->route('admin.requests.index')
                    ->with('error', 'Stock items can only be created from approved requests.');
            }

            DB::beginTransaction();

            $items = json_decode($request->request_data, true);
            
            foreach ($items as $index => $item) {
                $validated = [
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'department' => $item['department'],
                    'branch' => $item['branch'],
                    'category' => $item['category'],
                    'control_number' => $item['product_name'] . '-' . uniqid(),
                    'description' => 'No description'
                ];

                // Handle image upload for this specific item
                if ($request->hasFile("images.{$index}")) {
                    $file = $request->file("images.{$index}");
                    if ($file->isValid()) {
                        $filename = $file->store('stock-images', 'public');
                        $validated['description'] = $filename;
                    }
                }

                Stock::create($validated);
            }

            // Clear the request data from session after successful processing
            session()->forget('request_data');

            DB::commit();
            return redirect()->route('admin.tables')->with('success', 'Stock items added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating stock:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Failed to create stock. Please try again.');
        }
    }

    public function storeDirect(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'department' => 'required|string',
                'branch' => 'required|string',
                'category' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            DB::beginTransaction();
            
            $stock = [
                'product_name' => $validated['product_name'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'department' => $validated['department'],
                'branch' => $validated['branch'],
                'category' => $validated['category'],
                'control_number' => $validated['product_name'] . '-' . uniqid(),
                'description' => 'No description'
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file->isValid()) {
                    $filename = $file->store('stock-images', 'public');
                    $stock['description'] = $filename;
                }
            }

            Stock::create($stock);

            DB::commit();
            return redirect()->route('admin.tables')->with('success', 'Supply added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating supply directly:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->withInput()->with('error', 'Failed to add supply. Please try again.');
        }
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        return view('admin.stock.editstock', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'control_number' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'edit_reason' => 'required|string|min:5',
        ]);

        // Track changes
        $changes = [];
        $fieldsToUpdate = [
            'product_name',
            'category',
            'department',
            'control_number',
            'price',
            'quantity'
        ];

        foreach ($fieldsToUpdate as $field) {
            if ($stock->$field != $validated[$field]) {
                $changes[$field] = [
                    'old' => $stock->$field,
                    'new' => $validated[$field]
                ];
            }
        }

        // Only create history if there are actual changes
        if (!empty($changes)) {
            // Create edit history
            StockEditHistory::create([
                'stock_id' => $stock->id,
                'changes' => json_encode($changes),
                'reason' => $validated['edit_reason'],
                'edited_by' => auth()->id()
            ]);

            // Update the stock with new values
            $stock->update([
                'product_name' => $validated['product_name'],
                'category' => $validated['category'],
                'department' => $validated['department'],
                'control_number' => $validated['control_number'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity']
            ]);

            return redirect()->route('tables')->with('success', 'Stock updated successfully!');
        }

        return redirect()->route('tables')->with('info', 'No changes were made to the stock.');
    }

    public function destroy($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->delete();
            
            return redirect()->route('stock.index')
                ->with('success', 'Stock deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('stock.index')
                ->with('error', 'Error deleting stock');
        }
    }

    public function inventory()
    {
        $stocks = Stock::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.inventory', compact('stocks'));
    }

    public function showDetails($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            
            // For debugging
            \Log::info('Admin accessing stock detail', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'stock_id' => $stock->id,
                'stock_department' => $stock->department,
                'stock_branch' => $stock->branch
            ]);
            
            // Admin can view all items regardless of department
            if (auth()->user()->role !== 'admin') {
                // This is a fallback check in case a non-admin somehow uses this controller
                \Log::warning('Non-admin user tried to access admin stock controller', [
                    'user_id' => auth()->id(),
                    'user_role' => auth()->user()->role,
                    'stock_id' => $stock->id
                ]);
                
                return view('scan.error', [
                    'message' => 'You do not have permission to view this item.'
                ]);
            }
            
            return view('scan.item-details', compact('stock'));
        } catch (\Exception $e) {
            \Log::error('Error showing stock details in admin controller', [
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