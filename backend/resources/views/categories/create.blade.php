@extends('layouts.app')
@section('title', 'New Category · Stockroom')
@section('content')
<div class="page-wrap">
    <div class="page-head"><div><div class="eyebrow">Catalog structure</div><h1>New category</h1><p>Give a group a clear name so your team can find it fast.</p></div></div>
    <form class="card form-card form-grid" method="POST" action="{{ route('categories.store') }}">
        @csrf
        <div class="field"><label for="name">Category name</label><input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus><span class="field-error">{{ isset($errors) ? $errors->first('name') : '' }}</span></div>
        <div class="form-actions"><button class="button button-primary" type="submit">Create category</button><a class="button button-soft" href="{{ route('categories.index') }}">Cancel</a></div>
    </form>
</div>
@endsection
