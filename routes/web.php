<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Livewire\Admin\ManajemenProduk;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DesignEditorController;
use App\Http\Controllers\DesignFileController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\PaymentController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.store');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/produk', ManajemenProduk::class)->name('produk.index');
        Route::get('/pesanan', [AdminController::class, 'dataPesanan'])->name('data.pesanan');
        Route::get('/penjadwalan-prioritas', [AdminController::class, 'penjadwalan'])->name('penjadwalan.prioritas');

        Route::get('/detail-pesanan/{id}', [AdminController::class, 'detailPesanan'])->name('detail.pesanan');

        Route::prefix('production')->name('production.')->group(function () {
            Route::get('/', [AdminController::class, 'production'])->name('index');
            Route::post('/{order}/complete', [AdminController::class, 'completeProduction'])->name('complete');

            Route::post('/item/{item}/start', [AdminController::class, 'starItemProduction'])->name('start-item');
            Route::post('/item/{item}/complete', [AdminController::class, 'completeItemProduction'])->name('complete-item');
        });
        Route::prefix('shipping')->name('shipping.')->group(function () {
            Route::get('/', [ShippingController::class, 'index'])->name('index');
            Route::get('/{order}', [ShippingController::class, 'show'])->name('show');
            Route::post('/{order}/input-resi', [ShippingController::class, 'inputResi'])->name('input-resi');
            // Route::post('/{order}/refresh-tracking', [ShippingController::class, 'refreshTracking'])->name('refresh-tracking');

            Route::post('/{order}/update-tracking', [ShippingController::class, 'updateTracking'])->name('update-tracking');
            Route::delete('/tracking/{tracking}', [ShippingController::class, 'deleteTracking'])->name('delete-tracking');
        });

        Route::get('/returns', [AdminController::class, 'returns'])->name('returns');

        // Download design files
        Route::get('/order/{orderId}/item/{itemId}/design/{area}/file/{fileIndex}', [DesignFileController::class, 'downloadSingleFile'])->name('download.design.single');
        Route::get('/order/{orderId}/item/{itemId}/design/{area}/download', [DesignFileController::class, 'downloadAreaFiles'])->name('download.design.area');
        Route::get('/order/{orderId}/item/{itemId}/design/download-all', [DesignFileController::class, 'downloadItemDesigns'])->name('download.design.item');
        Route::get('/order/{orderId}/design/download-all', [DesignFileController::class, 'downloadOrderDesigns'])->name('download.design.order');
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

        Route::prefix('payment')->name('payment.')->group(function () {
            Route::post('/initiate/{order}', [PaymentController::class, 'initiatePayment'])->name('initiate');
            Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
            Route::get('/unfinish', [PaymentController::class, 'unfinish'])->name('unfinish');
            Route::get('/error', [PaymentController::class, 'error'])->name('error');
            Route::get('/check-status/{order}', [PaymentController::class, 'checkStatus'])->name('check-status');
        });

        Route::get('/orders/{order}/return', [CustomerController::class, 'returnForm'])->name('orders.return');
    });
// Midtrans callback 
Route::post('/payment/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::prefix('api')
    ->name('api.')
    ->group(function () {
        Route::get('/jenis-sablon{id}', [HomeController::class, 'show'])->name('jenis-sablon');
    });

require __DIR__ . '/auth.php';