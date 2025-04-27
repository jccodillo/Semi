<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Stock::select('category')->distinct()->get();
        return view('admin.category', compact('categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:stocks,category'
        ]);

        // Create a placeholder stock with just the category
        // Or handle this according to your business logic
        Stock::create([
            'category' => $request->name,
            // Add other required fields with default values
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $oldCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:stocks,category'
        ]);

        // Update all stocks with the old category to the new category
        Stock::where('category', $oldCategory)
            ->update(['category' => $request->name]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category
     */
    public function destroy($category)
    {
        // Either set category to null or delete stocks with this category
        // depending on your business requirements
        Stock::where('category', $category)
            ->update(['category' => null]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
