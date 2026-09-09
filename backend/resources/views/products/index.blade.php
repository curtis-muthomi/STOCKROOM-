@extends('layouts.app')
@section('title', 'Products · Stockroom')
@section('content')
<div class="page-wrap">
    <div class="page-head"><div><div class="eyebrow">Inventory catalog</div><h1>Products</h1><p>Search, filter, and keep a pulse on everything you carry.</p></div><a class="button button-primary" href="{{ route('products.create') }}">＋ New product</a></div>
    <form class="card filter-bar" method="GET" action="{{ route('products.index') }}">
        <div class="field"><label for="search">Search products</label><input type="search" id="search" name="search" placeholder="Name or SKU…" value="{{ request('search') }}"></div>
        <div class="field"><label for="category">Category</label><select id="category" name="category"><option value="">All categories</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <button class="button button-primary" type="submit">Filter</button><a class="button button-soft" href="{{ route('products.index') }}">Clear</a>
    </form>
    <section class="card table-card">
    @if ($products->isEmpty())
        <div class="empty-state"><div class="empty-icon">▦</div><h3>No products found</h3><p>Try clearing your filters or add a new product to the catalog.</p><a class="button button-primary" href="{{ route('products.create') }}">Create product</a></div>
    @else
        <div class="table-scroll"><table><thead><tr><th>ID</th><th>Product</th><th>SKU</th><th>Category</th><th>Price</th><th>Quantity</th><th>Actions</th></tr></thead><tbody>
        @foreach ($products as $product)
            <tr><td class="muted">#{{ $product->id }}</td><td><strong>{{ $product->name }}</strong></td><td class="muted">{{ $product->sku }}</td><td>{{ $product->category?->name }}</td><td>${{ number_format((float) $product->price, 2) }}</td><td class="{{ $product->quantity <= 5 ? 'quantity-low' : 'quantity-ok' }}">{{ $product->quantity }} @if($product->quantity <= 5)<small>low</small>@endif</td><td class="actions"><a class="button button-soft button-small" href="{{ route('products.edit', $product) }}">Edit</a><form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">@csrf @method('DELETE')<button class="button button-danger button-small" type="submit">Delete</button></form></td></tr>
        @endforeach
        </tbody></table></div>
    @endif
    </section>
</div>
@endsection
