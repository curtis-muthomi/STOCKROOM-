@extends('layouts.app')
@section('title', 'New Product · Stockroom')
@section('content')
<div class="page-wrap">
    <div class="page-head"><div><div class="eyebrow">Inventory catalog</div><h1>New product</h1><p>Add an item to your catalog. Stock levels are managed through movements.</p></div></div>
    <form class="card form-card form-grid" method="POST" action="{{ route('products.store') }}">
        @csrf
        <div class="field"><label for="name">Product name</label><input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus><span class="field-error">{{ isset($errors) ? $errors->first('name') : '' }}</span></div>
        <div class="field"><label for="sku">SKU</label><input type="text" id="sku" name="sku" value="{{ old('sku') }}" required><span class="field-error">{{ isset($errors) ? $errors->first('sku') : '' }}</span></div>
        <div class="field"><label for="category_id">Category</label><select id="category_id" name="category_id" required><option value="">Select a category</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select><span class="field-error">{{ isset($errors) ? $errors->first('category_id') : '' }}</span></div>
        <div class="field"><label for="price">Unit price</label><input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required><span class="field-error">{{ isset($errors) ? $errors->first('price') : '' }}</span></div>
        <div class="form-actions"><button class="button button-primary" type="submit">Create product</button><a class="button button-soft" href="{{ route('products.index') }}">Cancel</a></div>
    </form>
</div>
@endsection
