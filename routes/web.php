<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Livewire\Admin\ManajemenProduk;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.store');


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        // Jika Anda punya AdminController dengan method dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/produk', ManajemenProduk::class)->name('produk.index');
    });

// Customer routes group
Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth', 'verified', 'role:customer'])
    ->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders.index');
        Route::get('/orders/create', [CustomerController::class, 'createOrder'])->name('order.create');
    });
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::prefix('api')
    ->name('api.')
    ->group(function () {
        Route::get('/jenis-sablon{id}', [HomeController::class, 'show'])->name('jenis-sablon');
    });
require __DIR__ . '/auth.php';
