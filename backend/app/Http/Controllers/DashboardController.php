<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $categoryCount = Category::count();
        $productCount = Product::count();
        $totalQuantity = Product::sum('quantity');
        $lowStockProducts = Product::with('category')
            ->where('quantity', '<=', 5)
            ->orderBy('quantity')
            ->orderBy('name')
            ->get();
        $recentMovements = StockMovement::with('product')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'categoryCount',
            'productCount',
            'totalQuantity',
            'lowStockProducts',
            'recentMovements',
        ));
    }
}
