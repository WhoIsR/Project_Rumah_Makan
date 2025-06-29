<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\PurchaseTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- DATA UNTUK KARTU STATISTIK ---
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Pendapatan Hari Ini
        $todaysRevenue = Order::where('status', 'completed')->whereDate('created_at', $today)->sum('total_amount');

        // 2. Transaksi Hari Ini
        $todaysTransactions = Order::where('status', 'completed')->whereDate('created_at', $today)->count();

        // 3. Laba Kotor Bulan Ini
        $monthlyRevenue = Order::where('status', 'completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');
        $monthlyExpense = PurchaseTransaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('total_amount');
        $monthlyProfit = $monthlyRevenue - $monthlyExpense;


        // --- DATA UNTUK DAFTAR DI BAWAH ---

        // 4. Stok Menipis (misalnya di bawah 1000 gram/ml atau 100 pcs)
        // Anda bisa menyesuaikan angka '1000' sesuai kebutuhan
        $lowStockIngredients = Ingredient::with('baseUnit')->where('stock', '<', 1000)->orderBy('stock', 'asc')->limit(5)->get();

        // 5. Menu Terlaris Bulan Ini
        $topSellingMenus = MenuItem::select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            // PERBAIKAN: Ubah 'order_details' menjadi 'order_items'
            ->join('order_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 6. Data untuk Grafik Penjualan 7 Hari Terakhir
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $sales = Order::where('status', 'completed')->whereDate('created_at', $date)->sum('total_amount');
            $salesData['labels'][] = $date->isoFormat('dd, D MMM');
            $salesData['data'][] = $sales;
        }


        // Kirim semua data baru ke view dashboard
        return view('dashboard', compact(
            'todaysRevenue',
            'todaysTransactions',
            'monthlyProfit',
            'lowStockIngredients',
            'topSellingMenus',
            'salesData'
        ));
    }
}
