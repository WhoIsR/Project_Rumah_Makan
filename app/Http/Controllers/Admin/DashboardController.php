<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil 5 menu yang paling baru dibuat
        $recentMenus = MenuItem::latest()->take(5)->get();

        // Ambil 5 bahan yang stoknya paling sedikit (stok menipis)
        $lowStockIngredients = Ingredient::orderBy('stock', 'asc')->take(5)->get();

        // ==> PERUBAHAN DI SINI <==
        // Tambahkan logika untuk menghitung total
        $totalMenus = MenuItem::count();
        $totalIngredients = Ingredient::count();

        // Kirim semua data itu ke view 'dashboard'
        return view('dashboard', [
            'recentMenus' => $recentMenus,
            'lowStockIngredients' => $lowStockIngredients,
            'totalMenus' => $totalMenus,
            'totalIngredients' => $totalIngredients,
        ]);
    }
}
