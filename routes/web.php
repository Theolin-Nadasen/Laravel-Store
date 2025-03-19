<?php

use App\Http\Controllers\NavigationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
Route::post('/products', [ProductController::class, 'store'])->name('product.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/products/{product}/update', [ProductController::class, 'update'])->name('product.update');
Route::delete('/products/{product}/destroy', [ProductController::class, 'destroy'])->name('product.destroy');

Route::get('/catalogue', [NavigationController::class, 'catalogue'])->name('catalogue');
Route::get('/view/{product}', [NavigationController::class, 'viewProduct'])->name('view');

Route::get('/addtocart/{product}', [NavigationController::class, 'addtocart'])->name('addtocart');

require __DIR__.'/auth.php';
