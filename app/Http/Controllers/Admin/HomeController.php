<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        // Set default values in case database is empty
        $data = [
            'totalItems' => 0,
            'lowStockCount' => 0,
            'totalValue' => 0,
            'stocks' => []
        ];

        try {
            $data['totalItems'] = Stock::count();
            $data['lowStockCount'] = Stock::where('quantity', '<', 50)->count();
            $data['totalValue'] = Stock::sum(\DB::raw('price * quantity'));
            $data['stocks'] = Stock::all();
        } catch (\Exception $e) {
            // Log error if needed
        }

        return view('dashboard', $data);
    }

    public function dashboard()
    {
        return $this->home();
    }
}
