<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order; // Import model Order
use Carbon\Carbon; // Untuk memudahkan manipulasi tanggal
use Illuminate\Support\Facades\DB; // Untuk query database yang lebih kompleks
use App\Models\MenuItem; // Import model MenuItem jika perlu laporan menu terlaris

class ReportController extends Controller
{
    /**
     * Menampilkan dashboard utama laporan penjualan.
     */
    public function index(Request $request)
    {
        // Filter periode laporan
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::today()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::today()->endOfDay();

        // Pastikan endDate tidak melewati hari ini jika tidak dispesifikkan
        if (!$request->input('end_date') && $endDate->isFuture()) { // Hanya set ke today jika end_date tidak dispesifikkan dan endDate default adalah masa depan
            $endDate = Carbon::now()->endOfDay();
        }

        // 1. Ringkasan Penjualan
        $totalSales = Order::whereBetween('created_at', [$startDate, $endDate])
                            ->where('status', 'completed')
                            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
                           ->where('status', 'completed')
                           ->count();

        $averageOrderValue = ($totalOrders > 0) ? $totalSales / $totalOrders : 0;

        // 2. Menu Terlaris (Top Selling Menu Items)
        // Gabungkan tabel secara eksplisit dan kualifikasi kolomnya
        $topSellingMenuItems = OrderItem::select(
                                        'menu_items.name', // Ambil nama menu dari tabel menu_items
                                        DB::raw('SUM(order_items.quantity) as total_quantity_sold'),
                                        DB::raw('SUM(order_items.subtotal) as total_revenue')
                                    )
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id') // Join ke tabel orders
                                    ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id') // Join ke tabel menu_items
                                    ->whereBetween('orders.created_at', [$startDate, $endDate]) // Kualifikasi created_at dari tabel orders
                                    ->where('orders.status', 'completed') // Kualifikasi status dari tabel orders
                                    ->groupBy('menu_items.name') // Group berdasarkan nama menu
                                    ->orderByDesc('total_quantity_sold')
                                    ->limit(10)
                                    ->get();

        // 3. Penjualan per Kategori (Sales by Category)
        // Gabungkan tabel secara eksplisit dan kualifikasi kolomnya
        $salesByCategory = OrderItem::select(
                                        'categories.name as category_name', // Ambil nama kategori dari tabel categories
                                        DB::raw('SUM(order_items.subtotal) as total_revenue')
                                    )
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id') // Join ke tabel orders
                                    ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id') // Join ke tabel menu_items
                                    ->join('categories', 'menu_items.category_id', '=', 'categories.id') // Join ke tabel categories
                                    ->whereBetween('orders.created_at', [$startDate, $endDate]) // Kualifikasi created_at dari tabel orders
                                    ->where('orders.status', 'completed') // Kualifikasi status dari tabel orders
                                    ->groupBy('categories.name') // Group berdasarkan nama kategori
                                    ->orderByDesc('total_revenue')
                                    ->get();


        return view('admin.reports.index', compact(
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'topSellingMenuItems',
            'salesByCategory',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Menampilkan laporan penjualan detail per tanggal.
     * (Contoh tambahan, bisa dikembangkan lebih lanjut)
     */
    public function dailySales(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $orders = Order::with(['user', 'items.menuItem'])
                       ->whereDate('created_at', $date)
                       ->where('status', 'completed')
                       ->orderByDesc('created_at')
                       ->paginate(10); // Paginate untuk detail per hari

        $dailyTotalSales = $orders->sum('total_amount'); // Total penjualan untuk hari itu

        return view('admin.reports.daily_sales', compact('orders', 'date', 'dailyTotalSales'));
    }

    // Kamu bisa menambahkan method lain seperti:
    // public function monthlySales(Request $request) { ... }
    // public function salesByCategory(Request $request) { ... }
    // public function salesByCashier(Request $request) { ... }
}