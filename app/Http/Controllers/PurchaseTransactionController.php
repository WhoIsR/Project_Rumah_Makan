<?php

namespace App\Http\Controllers;

use App\Models\PurchaseTransaction;
use App\Models\Supplier;
use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseTransactionController extends Controller
{
    public function index()
    {
        $purchases = PurchaseTransaction::with('supplier')->latest()->paginate(15);
        return view('admin.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $lastPurchase = PurchaseTransaction::latest('id')->first();
        $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;
        $invoiceNumber = 'BB-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        $suppliers = Supplier::orderBy('name')->get();
        $ingredients = Ingredient::with('baseUnit')->orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        return view('admin.purchases.create', compact('suppliers', 'ingredients', 'units', 'invoiceNumber'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'transaction_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'ingredients.*.unit' => 'required|string|exists:units,symbol',
            'ingredients.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            $totalAmount = collect($request->ingredients)->sum(fn($item) => $item['quantity'] * $item['price']);
            $purchase = PurchaseTransaction::create([
                'supplier_id' => $request->supplier_id,
                'transaction_date' => $request->transaction_date,
                'invoice_number' => $request->invoice_number,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
            ]);
            foreach ($request->ingredients as $item) {
                $purchase->details()->create([
                    'ingredient_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'price_per_unit' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }
            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'Transaksi pembelian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan transaksi pembelian: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi.')->withInput();
        }
    }

    /**
     * Menampilkan detail dari satu transaksi pembelian.
     */
     public function show(PurchaseTransaction $purchase)
    {
        $purchase->load('supplier', 'details.ingredient.baseUnit');
        return view('admin.purchases.show', compact('purchase'));
    }

    /**
     * Menampilkan form untuk mengedit transaksi.
     */
    public function edit(PurchaseTransaction $purchase)
    {
        $purchase->load('details.ingredient');
        $suppliers = Supplier::orderBy('name')->get();
        $ingredients = Ingredient::with('baseUnit')->orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        return view('admin.purchases.edit', compact('purchase', 'suppliers', 'ingredients', 'units'));
    }

    /**
     * Memperbarui data transaksi di database.
     */
    public function update(Request $request, PurchaseTransaction $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'transaction_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'ingredients.*.unit' => 'required|string|exists:units,symbol',
            'ingredients.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = collect($request->ingredients)->sum(fn($item) => $item['quantity'] * $item['price']);

            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'transaction_date' => $request->transaction_date,
                'invoice_number' => $request->invoice_number,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
            ]);

            // Hapus detail lama satu per satu agar Observer berjalan
            foreach ($purchase->details as $detail) {
                $detail->delete();
            }

            // Buat kembali detail baru berdasarkan input
            foreach ($request->ingredients as $item) {
                $purchase->details()->create([
                    'ingredient_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'price_per_unit' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'Transaksi pembelian berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui transaksi pembelian: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui transaksi.')->withInput();
        }
    }

    /**
     * Menghapus transaksi dari database.
     */
    public function destroy(PurchaseTransaction $purchase)
    {
        try {
            // Cukup hapus transaksi utama.
            // on-delete cascade akan menghapus detail, dan observer akan mengembalikan stok.
            $purchase->delete();
            return redirect()->route('admin.purchases.index')->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.purchases.index')->with('error', 'Gagal menghapus transaksi.');
        }
    }

    /**
     * Menampilkan halaman khusus untuk mencetak faktur.
     */
    public function print(PurchaseTransaction $purchase)
    {
        // Eager load relasi yang dibutuhkan untuk faktur
        $purchase->load('supplier', 'details.ingredient');

        // Kembalikan view baru yang khusus untuk print
        return view('admin.purchases.print', compact('purchase'));
    }
}
