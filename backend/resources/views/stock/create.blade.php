@extends('layouts.app')
@section('title', 'Record Movement · Stockroom')
@section('content')
<div class="page-wrap">
    <div class="page-head"><div><div class="eyebrow">Inventory control</div><h1>Record movement</h1><p>Adjust inventory with a traceable stock-in or stock-out.</p></div></div>
    <form class="card form-card form-grid" method="POST" action="{{ route('stock.store') }}">
        @csrf
        <div class="field"><label for="product_id">Product</label><select id="product_id" name="product_id" required><option value="">Select a product</option>@foreach ($products as $product)<option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} · {{ $product->quantity }} on hand</option>@endforeach</select><span class="field-error">{{ isset($errors) ? $errors->first('product_id') : '' }}</span></div>
        <div class="field"><label for="type">Movement type</label><select id="type" name="type" required><option value="in" @selected(old('type') === 'in')>Stock In · add inventory</option><option value="out" @selected(old('type') === 'out')>Stock Out · remove inventory</option></select><span class="field-error">{{ isset($errors) ? $errors->first('type') : '' }}</span></div>
        <div class="field"><label for="quantity">Quantity</label><input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required><span class="field-error">{{ isset($errors) ? $errors->first('quantity') : '' }}</span></div>
        <div class="field"><label for="note">Note <span class="muted">(optional)</span></label><input type="text" id="note" name="note" value="{{ old('note') }}" placeholder="e.g. supplier delivery"><span class="field-error">{{ isset($errors) ? $errors->first('note') : '' }}</span></div>
        <div class="form-actions"><button class="button button-primary" type="submit">Record movement</button><a class="button button-soft" href="{{ route('stock.index') }}">Cancel</a></div>
    </form>
</div>
@endsection
