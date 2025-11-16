<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Livewire\Admin\ManajemenProduk;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DesignEditorController;
use App\Http\Controllers\DesignFileController;
use App\Http\Controllers\ShippingController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.store');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/produk', ManajemenProduk::class)->name('produk.index');

        // Download single file
        Route::get(
            '/orders/{orderId}/items/{itemId}/design/{area}/file/{fileIndex}',
            [DesignFileController::class, 'downloadSingleFile']
        )
            ->name('order.design.download-file');

        // Download semua files dari 1 area
        Route::get(
            '/orders/{orderId}/items/{itemId}/design/{area}/download',
            [DesignFileController::class, 'downloadAreaFiles']
        )
            ->name('order.design.download-area');

        // Download semua design dari 1 item
        Route::get(
            '/orders/{orderId}/items/{itemId}/design/download-all',
            [DesignFileController::class, 'downloadItemDesigns']
        )
            ->name('order.design.download-item');

        // Download semua design dari entire order
        Route::get(
            '/orders/{orderId}/design/download-complete',
            [DesignFileController::class, 'downloadOrderDesigns']
        )
            ->name('order.design.download-complete');
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
        Route::get('/orders/{order}', [CustomerController::class, 'showOrder'])->name('orders.show');

        // Design Editor Routes (AJAX)
        Route::post('/design-editor/upload-image', [DesignEditorController::class, 'uploadImage'])->name('design-editor.upload');
        Route::post('/design-editor/delete-image', [DesignEditorController::class, 'deleteImage'])->name('design-editor.delete');

        // Shipping API
        Route::get('/shipping/provinces', [ShippingController::class, 'getProvinces'])->name('shipping.provinces');
        Route::get('/shipping/cities', [ShippingController::class, 'getCities'])->name('shipping.cities');
        Route::get('/shipping/search-city', [ShippingController::class, 'searchCity'])->name('shipping.search-city');
        Route::post('/shipping/calculate-cost', [ShippingController::class, 'calculateCost'])->name('shipping.calculate-cost');
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
