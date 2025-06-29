<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KasirController extends Controller
{
    /**
     * Mengambil data awal untuk halaman kasir (menu dan meja).
     */
    public function getData()
    {
        try {
            $menuItems = MenuItem::where('is_available', true)->orderBy('name')->get();

            return response()->json([
                'menuItems' => $menuItems,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch POS data: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memuat data.'], 500);
        }
    }

    /**
     * Menyimpan pesanan baru.
     */
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'order_type' => 'required|in:dine-in,takeaway',
            'customer_name' => 'required_if:order_type|nullable|string|max:255',
            'customer_phone' => 'required_if:order_type|nullable|string|max:20',
            'customer_address' => 'required_if:order_type|nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($validated, $request) {
                // Ambil menu items dari DB untuk mendapatkan harga asli (security)
                $menuItemIds = collect($validated['items'])->pluck('id');
                $menuItems = MenuItem::find($menuItemIds);

                $totalAmount = collect($validated['items'])->reduce(function ($carry, $item) use ($menuItems) {
                    $menuItem = $menuItems->firstWhere('id', $item['id']);
                    return $carry + ($menuItem->price * $item['quantity']);
                }, 0);
                
                // Tambahkan pajak (misal 10%)
                $totalAmountWithTax = $totalAmount * 1.10;

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total_amount' => $totalAmountWithTax,
                    'status' => 'pending', // Status awal, akan diupdate setelah bayar
                    'order_type' => $validated['order_type'],
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_address' => $validated['customer_address'],
                ]);

                foreach ($validated['items'] as $item) {
                    $menuItem = $menuItems->firstWhere('id', $item['id']);
                    $order->items()->create([
                        'menu_item_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $menuItem->price,
                    ]);
                }

                return $order;
            });

            return response()->json([
                'message' => 'Pesanan berhasil dibuat!',
                'order_id' => $order->id
            ], 201);

        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal membuat pesanan, terjadi kesalahan server.'], 500);
        }
    }
}
