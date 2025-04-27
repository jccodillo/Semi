<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index()
    {
        return view('admin.reports');
    }

    public function generate(Request $request)
    {
        try {
            $request->validate([
                'filter_type' => 'required|in:week,month,year',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'department' => 'required|string'
            ]);

            // Adjust end date to include the entire end day
            $endDate = date('Y-m-d 23:59:59', strtotime($request->end_date));
            $startDate = date('Y-m-d 00:00:00', strtotime($request->start_date));

            // Log the request parameters for debugging
            \Log::info('Report request parameters:', [
                'department' => $request->department,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            $stocks = DB::table('stocks')
                ->select('control_number', 'product_name', 'category', 'quantity', 'price', 'department', 'branch', 'created_at')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('department', $request->department)
                ->get();

            // Log the number of stocks found
            \Log::info('Stocks found for report:', ['count' => $stocks->count()]);

            $items = $stocks->map(function ($stock) {
                return [
                    'stock_no' => $stock->control_number,
                    'unit' => $stock->category,
                    'description' => $stock->product_name,
                    'price' => number_format($stock->price, 2),
                    'quantity' => $stock->quantity,
                    'department' => $stock->department,
                    'remarks' => ''
                ];
            });

            $data = [
                'items' => $items,
                'signatures' => [
                    'requested_by' => 'JOHN DOE',
                    'approved_by' => 'JANE SMITH',
                    'issued_by' => 'MARK JOHNSON'
                ],
                'purpose' => 'For office use'
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Report generation error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate report'], 500);
        }
    }
}
