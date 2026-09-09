<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stockroom')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">S</span>
                <span><strong>Stockroom</strong><small>inventory, simplified</small></span>
            </a>
            <nav class="main-nav" aria-label="Main navigation">
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span>◈</span> Dashboard</a>
                <a class="{{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}"><span>⌘</span> Categories</a>
                <a class="{{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}"><span>▦</span> Products</a>
                <a class="{{ request()->routeIs('stock.*') ? 'active' : '' }}" href="{{ route('stock.index') }}"><span>↗</span> Stock</a>
            </nav>
            <div class="sidebar-foot">
                <span class="status-dot"></span>
                <span>Workspace online</span>
            </div>
        </aside>
        <main class="main-content">
            <span class="app-watermark" aria-hidden="true">CM</span>
            <div class="mobile-topbar">
                <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">S</span><strong>Stockroom</strong></a>
                <button class="menu-toggle" type="button" aria-label="Toggle navigation">☰</button>
            </div>
            @include('partials.flash')
            @yield('content')
            @include('partials.developer-credit')
        </main>
    </div>
</body>
</html>
