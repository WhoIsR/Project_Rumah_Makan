<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IngredientController;

// Halaman Landing Page untuk Customer (Publik, tidak perlu login)
Route::get('/', function () {
    return view('customer_landing');
})->name('customer.landing');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('login');
})->name('register');

// Grup route yang WAJIB login untuk mengaksesnya
Route::middleware(['auth', 'verified'])->group(function () {

    // Halaman Dashboard Utama (Semua role yang login bisa akses)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman Profile (Bawaan Breeze, semua role bisa akses)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === Grup Khusus ADMIN (dijaga oleh middleware 'role:admin') ===
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/', function () {
            return view('customer_landing');
        })->name('customer.landing');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('categories/{category}/menu-items', [CategoryController::class, 'getMenuItems'])->name('categories.menu-items');

        Route::resource('users', UserController::class);
        Route::resource('ingredients', IngredientController::class);
        Route::resource('menu-items', MenuItemController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        // Nanti route 'expenses' (pengeluaran) juga di sini
    });

    // === Grup Khusus ATASAN (dan Admin juga boleh) ===
    Route::middleware('role:admin,atasan')->prefix('laporan')->name('laporan.')->group(function () {
        // Nanti semua route laporan di sini.
        // Contoh: Route::get('/penjualan', [ReportController::class, 'sales'])->name('sales');
    });

    // === Grup Khusus KASIR (dan Admin juga boleh) ===
    Route::middleware('role:admin,kasir')->group(function () {
        
        Route::get('/kasir', [CashierController::class, 'index'])->name('kasir.index');
        Route::post('/place-order', [CashierController::class, 'placeOrder'])->name('kasir.placeOrder');
        Route::get('/history', [CashierController::class, 'history'])->name('kasir.history');
        Route::get('/orders/{order}', [CashierController::class, 'showOrder'])->name('kasir.show');

        // Nanti route untuk proses transaksi kasir di sini
    });
});


// Route untuk otentikasi (login, register, dll)
require __DIR__ . '/auth.php';
