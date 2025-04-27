<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnItem;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnItem::with(['user', 'item'])->orderBy('created_at', 'desc')->get();
        return view('Admin.returns', compact('returns'));
    }

    public function update(Request $request, $id)
    {
        $return = \App\Models\ReturnItem::findOrFail($id);
        $oldStatus = $return->status;
        $return->status = $request->status;
        $return->save();

        // Get user stock
        $userStock = \App\Models\Stock::find($return->item_id);
        // Find the admin supply by product_name
        $adminSupply = null;
        if ($userStock) {
            $adminSupply = \App\Models\SupplyInventory::where('product_name', $userStock->product_name)->first();
        }

        if ($oldStatus !== $request->status) {
            if ($request->status === 'approved') {
                // Reduce both by the returned quantity
                if ($userStock) {
                    $userStock->quantity = max(0, $userStock->quantity - $return->quantity);
                    $userStock->save();
                }
                if ($adminSupply) {
                    $adminSupply->quantity = max(0, $adminSupply->quantity - $return->quantity);
                    $adminSupply->save();
                    
                    // Create a transaction record
                    SupplyTransaction::create([
                        'supply_id' => $adminSupply->id,
                        'transaction_type' => 'issuance',
                        'quantity' => $return->quantity,
                        'reference_number' => 'RTN-' . strtoupper(Str::random(6)),
                        'balance' => $adminSupply->quantity,
                        'office' => 'Return from ' . ($return->user->name ?? 'User'),
                        'days_to_consume' => null,
                    ]);
                }
            } elseif ($request->status === 'replace') {
                // Only reduce admin supply by double, do not change user stock
                if ($adminSupply) {
                    $adminSupply->quantity = max(0, $adminSupply->quantity - ($return->quantity));
                    $adminSupply->save();
                    
                    // Create a transaction record for the replacement
                    SupplyTransaction::create([
                        'supply_id' => $adminSupply->id,
                        'transaction_type' => 'issuance',
                        'quantity' => $return->quantity,
                        'reference_number' => 'RPL-' . strtoupper(Str::random(6)),
                        'balance' => $adminSupply->quantity,
                        'office' => 'Replacement for ' . ($return->user->name ?? 'User'),
                        'days_to_consume' => null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.returns.index')->with('success', 'Return request updated successfully.');
    }
}