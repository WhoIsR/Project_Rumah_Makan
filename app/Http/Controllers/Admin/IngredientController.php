<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Tambahkan ini
use Illuminate\Support\Facades\Log;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::with('baseUnit')->orderBy('name')->paginate(10);
        return view('admin.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        // Ambil hanya unit dasar (gram, ml, pcs) untuk pilihan
        $units = Unit::whereNull('base_unit_id')->get();
        return view('admin.ingredients.create', compact('units'));
    }

    public function store(Request $request)
    {
        // Validasi sekarang menggunakan base_unit_id
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'stock' => 'required|numeric|min:0',
            'base_unit_id' => 'required|exists:units,id',
        ]);

        Ingredient::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'base_unit_id' => $request->base_unit_id,
        ]);

        return redirect()->route('admin.ingredients.index')
                         ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    /**
     * Menyimpan bahan baku baru melalui permintaan AJAX.
     * Mengembalikan respons JSON.
     */
    public function storeAjax(Request $request)
    {
        // Lakukan validasi data yang masuk
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ingredients,name',
            'base_unit_id' => 'required|exists:units,id',
        ]);

        // Jika validasi gagal, kembalikan error JSON
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        try {
            // Buat bahan baku baru
            $ingredient = Ingredient::create([
                'name' => $request->name,
                'base_unit_id' => $request->base_unit_id,
                'stock' => 0, // Inisialisasi stok 0 untuk bahan baku baru
            ]);

            // Muat relasi baseUnit agar data unit tersedia di respons JSON
            $ingredient->load('baseUnit');

            // Kembalikan bahan baku yang baru dibuat dalam format JSON
            return response()->json($ingredient, 201); // 201 Created
        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menyimpan ke database
            // Anda bisa log error ini untuk debugging lebih lanjut
            Log::error('Error creating ingredient via AJAX: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan bahan baku.'], 500); // 500 Internal Server Error
        }
    }

    public function edit(Ingredient $ingredient)
    {
        // Ambil juga data unit untuk form edit
        $units = Unit::whereNull('base_unit_id')->get();
        return view('admin.ingredients.edit', compact('ingredient', 'units'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        // Validasi sekarang menggunakan base_unit_id
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $ingredient->id,
            'stock' => 'required|numeric|min:0',
            'base_unit_id' => 'required|exists:units,id',
        ]);

        $ingredient->update([
            'name' => $request->name,
            'stock' => $request->stock,
            'base_unit_id' => $request->base_unit_id,
        ]);

        return redirect()->route('admin.ingredients.index')
                         ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->menuItems()->count() > 0) {
            return redirect()->route('admin.ingredients.index')
                             ->with('error', 'Bahan baku tidak bisa dihapus karena masih digunakan oleh menu.');
        }
        $ingredient->delete();
        return redirect()->route('admin.ingredients.index')
                         ->with('success', 'Bahan baku berhasil dihapus.');
    }
}
