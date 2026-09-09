@extends('layouts.app')
@section('title', 'Stock Movements · Stockroom')
@section('content')
<div class="page-wrap">
    <div class="page-head"><div><div class="eyebrow">Inventory control</div><h1>Stock movements</h1><p>A clear audit trail for every unit in and out.</p></div><a class="button button-primary" href="{{ route('stock.create') }}">＋ Record movement</a></div>
    <section class="card table-card">
    @if ($movements->isEmpty())
        <div class="empty-state"><div class="empty-icon">↗</div><h3>No movements yet</h3><p>Record your first stock-in or stock-out to begin the history.</p><a class="button button-primary" href="{{ route('stock.create') }}">Record movement</a></div>
    @else
        <div class="table-scroll"><table><thead><tr><th>Product</th><th>Type</th><th>Quantity</th><th>Note</th><th>Date</th></tr></thead><tbody>
        @foreach ($movements as $movement)
            <tr><td><strong>{{ $movement->product->name }}</strong></td><td><span class="badge {{ $movement->type === 'in' ? 'badge-in' : 'badge-out' }}">{{ $movement->type === 'in' ? 'Stock In' : 'Stock Out' }}</span></td><td class="{{ $movement->type === 'in' ? 'quantity-ok' : 'quantity-low' }}">{{ $movement->type === 'in' ? '+' : '−' }}{{ $movement->quantity }}</td><td class="muted">{{ $movement->note ?: '—' }}</td><td class="muted">{{ $movement->created_at->format('M j, Y · g:i A') }}</td></tr>
        @endforeach
        </tbody></table></div>
    @endif
    </section>
</div>
@endsection
