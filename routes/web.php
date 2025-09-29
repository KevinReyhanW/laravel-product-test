<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\Product;

// Redirect home → products page
Route::get('/', fn() => redirect()->route('products.index'));

// Resourceful routes for CRUD (index, create, store, edit, update, destroy)
Route::resource('products', ProductController::class);

// Extra route to sync products if you need it
Route::post('/products/sync', [ProductController::class, 'sync'])->name('products.sync');

// API endpoint (returns JSON)
Route::get('/api/products', function () {
    return Product::all();
});
