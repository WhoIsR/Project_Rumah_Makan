<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PurchaseTransaction;
use Illuminate\Http\Request; // <-- Pastikan ini ada
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman utama laporan dan data penjualan.
     * PASTIKAN ADA 'Request $request' DI SINI.
     */
    public function index(Request $request)
    {
        // Tetapkan tanggal default jika tidak ada input: bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data order dalam rentang tanggal yang dipilih
        $orders = Order::where('status', 'completed')
                       ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                       ->with('user') // Eager load relasi user
                       ->orderBy('created_at', 'desc')
                       ->get();

        // Hitung ringkasan data
        $totalRevenue = $orders->sum('total_amount');
        $totalTransactions = $orders->count();
        $totalItemsSold = $orders->flatMap(fn($order) => $order->items)->sum('quantity');

        // Cek apakah permintaan ini untuk mencetak
        if ($request->has('print')) {
            return view('admin.reports.sales-print', compact('orders', 'startDate', 'endDate', 'totalRevenue'));
        }

        return view('admin.reports.index', compact(
            'orders',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalTransactions',
            'totalItemsSold'
        ));
    }

    /**
     * Menampilkan laporan pembelian bahan baku.
     */
    public function purchaseReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $purchases = PurchaseTransaction::whereBetween('transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                                        ->with('supplier')
                                        ->orderBy('transaction_date', 'desc')
                                        ->get();

        $totalExpense = $purchases->sum('total_amount');
        $totalTransactions = $purchases->count();

        if ($request->has('print')) {
            return view('admin.reports.purchases-print', compact('purchases', 'startDate', 'endDate', 'totalExpense'));
        }

        return view('admin.reports.purchases', compact('purchases', 'startDate', 'endDate', 'totalExpense', 'totalTransactions'));
    }

    /**
     * Menampilkan laporan laba rugi sederhana.
     */
    public function profitAndLoss(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $totalRevenue = Order::where('status', 'completed')->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->sum('total_amount');
        $totalExpense = PurchaseTransaction::whereBetween('transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->sum('total_amount');
        $grossProfit = $totalRevenue - $totalExpense;

        if ($request->has('print')) {
            return view('admin.reports.profit-loss-print', compact('startDate', 'endDate', 'totalRevenue', 'totalExpense', 'grossProfit'));
        }

        return view('admin.reports.profit-loss', compact('startDate', 'endDate', 'totalRevenue', 'totalExpense', 'grossProfit'));
    }
}