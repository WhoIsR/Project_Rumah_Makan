<?php

namespace App\Http\Controllers; // Perhatikan namespace Anda, di sini saya asumsikan App\Http\Controllers

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CashierController extends Controller
{
    /**
     * Menampilkan halaman utama POS untuk kasir.
     */
    public function index()
    {
        $categories = Category::with('menuItems')->get();
        $menuItems = MenuItem::all();
        return view('admin.kasir.index', compact('categories', 'menuItems'));
    }

    /**
     * Memproses pesanan baru.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0',
            // PERUBAHAN DI SINI: Hapus 'delivery' dari aturan validasi
            'order_type' => 'nullable|string|in:Makan di Tempat,Bawa Pulang',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $orderItemsData = [];

            // Langkah 1: Validasi semua item dan stok SEBELUM melakukan apapun
            foreach ($request->items as $item) {
                $menuItem = MenuItem::with('ingredients')->find($item['menu_item_id']);

                if (!$menuItem) {
                    throw new Exception("Menu item dengan ID {$item['menu_item_id']} tidak ditemukan.");
                }

                if ($menuItem->ingredients->isEmpty()) {
                    throw new Exception("Resep untuk menu '{$menuItem->name}' belum diatur. Silakan atur resep terlebih dahulu.");
                }

                foreach ($menuItem->ingredients as $ingredient) {
                    $quantityNeeded = $ingredient->pivot->quantity_needed * $item['quantity'];
                    if ($ingredient->stock < $quantityNeeded) {
                        $unitSymbol = $ingredient->baseUnit->symbol ?? 'unit';
                        throw new Exception("Stok {$ingredient->name} tidak mencukupi. Dibutuhkan {$quantityNeeded} {$unitSymbol}, hanya tersedia {$ingredient->stock} {$unitSymbol}.");
                    }
                }

                $subtotal = $menuItem->price * $item['quantity'];
                $totalAmount += $subtotal;
                $orderItemsData[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price,
                    'subtotal' => $subtotal,
                ];
            }

            // Langkah 2: Validasi pembayaran
            $paidAmount = $request->input('paid_amount');
            if ($paidAmount < $totalAmount) {
                throw new Exception('Jumlah pembayaran kurang dari total tagihan.');
            }
            $changeAmount = $paidAmount - $totalAmount;

            // Langkah 3: Buat Order utama
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'status' => 'completed',
                'order_type' => $request->order_type,
            ]);

            // Langkah 4: Simpan Order Items dan kurangi stok
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);

                $menuItem = MenuItem::find($itemData['menu_item_id']);
                // Pastikan relasi ingredients dimuat untuk menuItem ini
                $menuItem->load('ingredients'); 
                foreach ($menuItem->ingredients as $ingredient) {
                    $quantityNeeded = $ingredient->pivot->quantity_needed * $itemData['quantity'];
                    $ingredient->decrement('stock', $quantityNeeded);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dicatat!',
                'change' => number_format($changeAmount, 0, ',', '.'),
                'total' => number_format($totalAmount, 0, ',', '.'),
                'order_id' => $order->id
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error placing order: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function history()
    {
        $orders = Order::with('user', 'items.menuItem')
                        ->orderByDesc('created_at')
                        ->paginate(10);
        return view('admin.kasir.history', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load('user', 'items.menuItem'); 
        return view('admin.kasir.show', compact('order'));
    }

    /**
     * Menampilkan halaman struk untuk dicetak.
     */
    public function printReceipt(Order $order)
    {
        // Eager load relasi yang dibutuhkan untuk struk
        $order->load('user', 'items.menuItem');
        
        return view('admin.kasir.receipt', compact('order'));
    }
}
