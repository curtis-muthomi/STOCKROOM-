@extends('layouts.app')
@section('title', 'Edit Category · Stockroom')
@section('content')
<div class="page-wrap">
    <div class="page-head"><div><div class="eyebrow">Catalog structure</div><h1>Edit category</h1><p>Keep your catalog language consistent.</p></div></div>
    <form class="card form-card form-grid" method="POST" action="{{ route('categories.update', $category) }}">
        @csrf @method('PUT')
        <div class="field"><label for="name">Category name</label><input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required autofocus><span class="field-error">{{ isset($errors) ? $errors->first('name') : '' }}</span></div>
        <div class="form-actions"><button class="button button-primary" type="submit">Save changes</button><a class="button button-soft" href="{{ route('categories.index') }}">Cancel</a></div>
    </form>
</div>
@endsection
