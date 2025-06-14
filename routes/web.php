<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\IngredientController; // Sudah dibuat di Bagian II
use App\Http\Controllers\Admin\MenuItemController;   // Sudah dibuat di Bagian II
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    // Ini akan jadi halaman untuk customer, kita atur nanti
    return view('customer_landing'); // Buat file Blade ini nanti
})->name('customer.landing');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk Admin Panel, dengan prefix 'admin' dan nama 'admin.'
    Route::prefix('admin')->name('admin.')->middleware(['verified'])->group(function () { // Pastikan admin terverifikasi jika perlu
        Route::get('/', function() { // Dashboard khusus admin jika ada
            return redirect()->route('admin.menu-items.index'); // Atau redirect ke manajemen menu
        })->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('ingredients', IngredientController::class);
        Route::resource('menu-items', MenuItemController::class);
        // Route ini untuk mengambil menu berdasarkan kategori via API
        Route::get('/categories/{category}/menu-items', [\App\Http\Controllers\Admin\CategoryController::class, 'getMenuItems'])->name('categories.menuItems');
        // Route tambahan jika ada, misal untuk laporan, dll.
    });
});

require __DIR__.'/auth.php';
