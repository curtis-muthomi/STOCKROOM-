<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('product')
            ->latest()
            ->get();

        return view('stock.index', compact('movements'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('stock.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['type'] === 'out' && $validated['quantity'] > $product->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'The stock-out quantity cannot exceed the available stock.',
                ]);
            }

            $quantityChange = $validated['type'] === 'in'
                ? $validated['quantity']
                : -$validated['quantity'];

            $product->increment('quantity', $quantityChange);

            StockMovement::create($validated);
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Stock movement recorded successfully.');
    }
}
