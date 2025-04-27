<?php

namespace App\Http\Controllers;

use App\Models\ReturnItem;
use App\Models\Stock;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:stocks,id',
            'qty' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $stock = Stock::findOrFail($request->item_id);

        // Check if return quantity is valid
        if ($request->qty > $stock->quantity) {
            return back()->with('error', 'Return quantity cannot be greater than available quantity.');
        }

        // Create return request
        ReturnItem::create([
            'user_id' => auth()->id(),
            'item_id' => $request->item_id,
            'quantity' => $request->qty,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Return request submitted successfully.');
    }

    public function myReturns()
    {
        $returns = \App\Models\ReturnItem::where('user_id', auth()->id())->with('item')->latest()->get();
        return view('User.my_returns', compact('returns'));
    }
}