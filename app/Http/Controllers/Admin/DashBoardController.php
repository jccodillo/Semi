<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\SuppliesInventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get the selected period (default to monthly)
            $period = $request->get('period', 'monthly');

            // Stock Summary Cards Data
            $totalStockItems = Stock::count();
            $lowStockCount = Stock::where('quantity', '<', 50)->count();
            $totalStockValue = Stock::sum(DB::raw('quantity * price'));

            // Supply Inventory Summary Data
            $totalSupplyItems = SuppliesInventory::count();
            $lowSupplyCount = SuppliesInventory::where('quantity', '<', 50)->count();

            // Analytics Data based on period
            $stockData = $this->getStockDataByPeriod($period);
            $supplyData = $this->getSupplyDataByPeriod($period);

            // Get recent stock movements (last 5)
            $recentStocks = Stock::latest('created_at')
                ->take(5)
                ->get([
                    'product_name as name',
                    'department',
                    'category',
                    'quantity',
                    'price',
                    'created_at'
                ]);
                
            // Get recent supplies inventory movements (last 5)
            $recentSupplies = SuppliesInventory::latest('created_at')
                ->take(5)
                ->get([
                    'product_name',
                    'quantity',
                    'unit_type',
                    'created_at'
                ]);

            // Add user statistics
            $userStats = [
                'metric' => 'Total Users',
                'count' => User::count(),
                'last_joined' => User::latest()->first()?->created_at
            ];

            // Return data to the view with all required variables
            return view('admin.dashboard', compact(
                'totalStockItems',
                'lowStockCount',
                'totalStockValue',
                'totalSupplyItems',
                'lowSupplyCount',
                'stockData',
                'supplyData',
                'recentStocks',
                'recentSupplies',
                'period',
                'userStats'
            ));
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load dashboard data.');
        }
    }

    private function getStockDataByPeriod($period)
    {
        $query = Stock::select(DB::raw('COUNT(*) as count'));

        switch ($period) {
            case 'daily':
                $query->addSelect(DB::raw('DATE(created_at) as label'))
                      ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                      ->groupBy('label')
                      ->orderBy('label');
                break;

            case 'weekly':
                $query->addSelect(DB::raw('DATE_FORMAT(created_at, "%W") as label'))
                      ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                      ->groupBy('label')
                      ->orderBy('created_at');
                break;

            case 'monthly':
                $query->addSelect(DB::raw('DATE_FORMAT(created_at, "%M") as label'))
                      ->whereYear('created_at', Carbon::now()->year)
                      ->groupBy('label')
                      ->orderBy('created_at');
                break;

            case 'annually':
                $query->addSelect(DB::raw('YEAR(created_at) as label'))
                      ->groupBy('label')
                      ->orderBy('label');
                break;
        }

        return $query->get();
    }
    
    private function getSupplyDataByPeriod($period)
    {
        $query = SuppliesInventory::select(DB::raw('COUNT(*) as count'));

        switch ($period) {
            case 'daily':
                $query->addSelect(DB::raw('DATE(created_at) as label'))
                      ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                      ->groupBy('label')
                      ->orderBy('label');
                break;

            case 'weekly':
                $query->addSelect(DB::raw('DATE_FORMAT(created_at, "%W") as label'))
                      ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                      ->groupBy('label')
                      ->orderBy('created_at');
                break;

            case 'monthly':
                $query->addSelect(DB::raw('DATE_FORMAT(created_at, "%M") as label'))
                      ->whereYear('created_at', Carbon::now()->year)
                      ->groupBy('label')
                      ->orderBy('created_at');
                break;

            case 'annually':
                $query->addSelect(DB::raw('YEAR(created_at) as label'))
                      ->groupBy('label')
                      ->orderBy('label');
                break;
        }

        return $query->get();
    }

    private function getPeriodTitle($period)
    {
        switch ($period) {
            case 'daily':
                return 'Overview - ' . now()->format('F j, Y');
            case 'weekly':
                return 'Overview - Week ' . now()->weekOfMonth . ' of ' . now()->format('F Y');
            case 'monthly':
                return 'Overview - ' . now()->format('F Y');
            case 'annually':
                return 'Overview - Year ' . now()->format('Y');
            default:
                return 'Overview';
        }
    }
}