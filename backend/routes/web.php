<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])
    ->name('categories.create');

Route::post('/categories', [CategoryController::class, 'store'])
    ->name('categories.store');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
    ->name('categories.edit');
Route::put('/categories/{category}', [CategoryController::class, 'update'])
    ->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
    ->name('categories.destroy');

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])
    ->name('products.create');
Route::post('/products', [ProductController::class, 'store'])
    ->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
    ->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])
    ->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->name('products.destroy');

Route::get('/stock', [StockMovementController::class, 'index'])
    ->name('stock.index');
Route::get('/stock/create', [StockMovementController::class, 'create'])
    ->name('stock.create');
Route::post('/stock', [StockMovementController::class, 'store'])
    ->name('stock.store');
