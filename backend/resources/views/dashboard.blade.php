@extends('layouts.app')

@section('title', 'Dashboard · Stockroom')

@section('content')
<div class="page-wrap">
    <section class="hero">
        <div class="hero-copy">
            <div class="eyebrow">Operations overview</div>
            <h1>Know your stock.<br><span style="color:#70d9be">Move with confidence.</span></h1>
            <p>A calm, clear view of your inventory health and the movements shaping it today.</p>
        </div>
        <div class="hero-art"><div class="hero-orbit"></div></div>
    </section>

    <div class="stats-grid">
        <article class="card stat-card"><span class="stat-label">Total categories</span><strong class="stat-value">{{ $categoryCount }}</strong></article>
        <article class="card stat-card"><span class="stat-label">Total products</span><strong class="stat-value">{{ $productCount }}</strong></article>
        <article class="card stat-card"><span class="stat-label">Inventory on hand</span><strong class="stat-value">{{ $totalQuantity }}</strong></article>
        <article class="card stat-card"><span class="stat-label">Low-stock products</span><strong class="stat-value stat-accent">{{ $lowStockProducts->count() }}</strong></article>
    </div>

    <div class="dashboard-grid">
        <section class="card section-card reveal-on-scroll">
            <div class="section-title"><h2>Low-stock watchlist</h2><a href="{{ route('products.index') }}">View products →</a></div>
            @if ($lowStockProducts->isEmpty())
                <div class="empty-state"><div class="empty-icon">✓</div><h3>All clear</h3><p>No products are below the stock threshold.</p></div>
            @else
                <div class="table-scroll"><table><thead><tr><th>Product</th><th>Category</th><th>On hand</th></tr></thead><tbody>
                @foreach ($lowStockProducts as $product)
                    <tr><td><strong>{{ $product->name }}</strong><br><small class="muted">{{ $product->sku }}</small></td><td>{{ $product->category?->name }}</td><td class="quantity-low">{{ $product->quantity }}</td></tr>
                @endforeach
                </tbody></table></div>
            @endif
        </section>
        <section class="card section-card reveal-on-scroll">
            <div class="section-title"><h2>Recent movements</h2><a href="{{ route('stock.index') }}">View history →</a></div>
            @if ($recentMovements->isEmpty())
                <div class="empty-state"><div class="empty-icon">↗</div><h3>No movements yet</h3><p>Your latest stock activity will appear here.</p></div>
            @else
                <div class="table-scroll"><table><thead><tr><th>Product</th><th>Movement</th><th>Quantity</th><th>Note</th><th>Date</th></tr></thead><tbody>
                @foreach ($recentMovements as $movement)
                    <tr><td><strong>{{ $movement->product?->name }}</strong></td><td><span class="badge {{ $movement->type === 'in' ? 'badge-in' : 'badge-out' }}">{{ $movement->type === 'in' ? 'Stock In' : 'Stock Out' }}</span></td><td class="{{ $movement->type === 'in' ? 'quantity-ok' : 'quantity-low' }}">{{ $movement->quantity }}</td><td class="muted">{{ $movement->note ?: '—' }}</td><td class="muted">{{ $movement->created_at->format('M j, Y') }}</td></tr>
                @endforeach
                </tbody></table></div>
            @endif
        </section>
    </div>
    @include('partials.developer-credit', ['variant' => 'panel'])
</div>
@endsection
