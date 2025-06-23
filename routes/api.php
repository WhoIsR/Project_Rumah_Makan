<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicMenuController; // Controller yang akan kita buat

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API untuk publik (customer), tidak memerlukan autentikasi
Route::get('/public/menu', [PublicMenuController::class, 'index'])->name('api.public.menu');
