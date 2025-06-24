<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import untuk logging

class CashierController extends Controller
{
    /**
     * Menampilkan halaman utama POS untuk kasir.
     */
    public function index()
    {
        // Ambil semua kategori dengan menu item-nya (jika perlu ditampilkan)
        $categories = Category::with('menuItems')->get();

        // Ambil semua menu item (untuk daftar lengkap)
        $menuItems = MenuItem::all();

        return view('admin.kasir.index', compact('categories', 'menuItems'));
    }

    /**
     * Memproses pesanan dari kasir.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1', // Harus ada minimal 1 item
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0', // Jumlah yang dibayarkan pelanggan
            'order_type' => 'nullable|string|in:dine_in,take_away,delivery',
            'table_number' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction(); // Mulai transaksi database
        try {
            $totalAmount = 0;
            $orderItemsData = [];

            // Hitung total dan siapkan data order items
            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                $subtotal = $menuItem->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price, // Harga menu saat pesanan dibuat
                    'subtotal' => $subtotal,
                ];
            }

            // Validasi jumlah pembayaran
            $paidAmount = $request->input('paid_amount');
            if ($paidAmount < $totalAmount) {
                DB::rollBack();
                return response()->json(['message' => 'Jumlah pembayaran kurang dari total tagihan.'], 422); // Unprocessable Entity
            }
            $changeAmount = $paidAmount - $totalAmount;

            // Buat Order baru
            $order = Order::create([
                'user_id' => Auth::id(), // Kasir yang sedang login
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'status' => 'completed', // Langsung completed, atau 'pending' jika ada proses dapur
                'order_type' => $request->order_type,
                'table_number' => $request->table_number,
            ]);

            // Simpan Order Items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);

                // --- INTEGRASI PENGURANGAN STOK BAHAN BAKU (NOMOR 2 DARI LIST AWAL) ---
                // Pastikan MenuItem memiliki relasi ingredients dan ingredients memiliki pivot quantity_needed
                // Ini adalah LOGIKA PENGURANGAN STOK
                $menuItem = MenuItem::with('ingredients')->find($itemData['menu_item_id']);
                foreach ($menuItem->ingredients as $ingredient) {
                    $quantityNeeded = $ingredient->pivot->quantity_needed * $itemData['quantity'];
                    if ($ingredient->stock < $quantityNeeded) {
                        DB::rollBack();
                        // Jika ada kekurangan stok, batalkan seluruh transaksi
                        return response()->json(['message' => 'Stok ' . $ingredient->name . ' tidak mencukupi. Hanya tersedia ' . $ingredient->stock . ' ' . $ingredient->unit . ' dibutuhkan ' . $quantityNeeded . ' ' . $ingredient->unit], 400);
                    }
                    $ingredient->stock -= $quantityNeeded;
                    $ingredient->save();
                }
                // --- AKHIR INTEGRASI PENGURANGAN STOK ---
            }

            DB::commit(); // Komit transaksi jika semua berhasil

            return response()->json([
                'message' => 'Pesanan berhasil dicatat!',
                'change' => number_format($changeAmount, 0, ',', '.'),
                'total' => number_format($totalAmount, 0, ',', '.'),
                'order_id' => $order->id
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Error placing order: ' . $e->getMessage()); // Catat error ke log
            return response()->json(['message' => 'Gagal mencatat pesanan. Silakan coba lagi.'], 500);
        }
    }

    // Kamu bisa menambahkan method lain untuk melihat riwayat transaksi, dll.
    public function history()
    {
        $orders = Order::with('user', 'items.menuItem')
                        ->where('user_id', Auth::id()) // Hanya pesanan kasir yang sedang login
                        ->orderByDesc('created_at')
                        ->paginate(10);

        return view('admin.kasir.history', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        // Pastikan kasir yang login adalah yang membuat order ini, atau admin
        if (Auth::user()->role === 'cashier' && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('user', 'items.menuItem'); // Load relasi

        return view('admin.kasir.show', compact('order'));
    }
}