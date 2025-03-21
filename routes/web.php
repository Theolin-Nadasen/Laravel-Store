<?php

use App\Http\Controllers\NavigationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NavigationController::class, 'home'])->name('landing');

Route::get('/dashboard', function () {
    return redirect(route('landing'));
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('product.index');
Route::get('/products/create', [ProductController::class, 'create'])->middleware('auth')->name('product.create');
Route::post('/products', [ProductController::class, 'store'])->middleware('auth')->name('product.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware('auth')->name('product.edit');
Route::put('/products/{product}/update', [ProductController::class, 'update'])->middleware('auth')->name('product.update');
Route::delete('/products/{product}/destroy', [ProductController::class, 'destroy'])->middleware('auth')->name('product.destroy');

Route::get('/catalogue', [NavigationController::class, 'catalogue'])->name('catalogue');
Route::get('/view/{product}', [NavigationController::class, 'viewProduct'])->name('view');

Route::get('/addtocart/{product}', [NavigationController::class, 'addtocart'])->name('addtocart');
Route::get('/cart', [NavigationController::class, 'viewcart'])->name('cart');
Route::post('/cart/{product}', [NavigationController::class, 'removefromcart'])->name('cart.remove');

require __DIR__.'/auth.php';
