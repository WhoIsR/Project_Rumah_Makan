<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PurchaseTransactionDetail; // <-- Tambahkan ini
use App\Observers\PurchaseTransactionDetailObserver; // <-- Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan Observer secara manual di sini
        PurchaseTransactionDetail::observe(PurchaseTransactionDetailObserver::class);
    }
}
