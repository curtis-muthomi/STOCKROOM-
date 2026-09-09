@extends('layouts.app')
@section('title', 'Categories · Stockroom')
@section('content')
<div class="page-wrap">
    <div class="page-head"><div><div class="eyebrow">Catalog structure</div><h1>Categories</h1><p>Organize your inventory into clear, useful groups.</p></div><a class="button button-primary" href="{{ route('categories.create') }}">＋ New category</a></div>
    <section class="card table-card">
    @if ($categories->isEmpty())
        <div class="empty-state"><div class="empty-icon">⌘</div><h3>No categories yet</h3><p>Create your first category to start organizing products.</p><a class="button button-primary" href="{{ route('categories.create') }}">Create category</a></div>
    @else
        <div class="table-scroll"><table><thead><tr><th>ID</th><th>Name</th><th>Created</th><th>Actions</th></tr></thead><tbody>
        @foreach ($categories as $category)
            <tr><td class="muted">#{{ $category->id }}</td><td><strong>{{ $category->name }}</strong></td><td class="muted">{{ $category->created_at->format('M j, Y') }}</td><td class="actions"><a class="button button-soft button-small" href="{{ route('categories.edit', $category) }}">Edit</a><form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Are you sure you want to delete this category?');">@csrf @method('DELETE')<button class="button button-danger button-small" type="submit">Delete</button></form></td></tr>
        @endforeach
        </tbody></table></div>
    @endif
    </section>
</div>
@endsection
