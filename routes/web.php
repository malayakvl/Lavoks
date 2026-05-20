<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalog routes (placeholder - will be implemented later)
Route::get('/catalog', function () {
    return view('catalog.index');
})->name('catalog');

Route::get('/catalog/{slug}', function ($slug) {
    return view('catalog.category', compact('slug'));
})->name('catalog.category');

// Cart route (placeholder)
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
