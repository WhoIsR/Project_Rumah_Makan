<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')->paginate(10);
        return view('admin.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('admin.ingredients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        Ingredient::create($request->all());

        return redirect()->route('admin.ingredients.index')
                        ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(Ingredient $ingredient)
    {
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $ingredient->id,
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        $ingredient->update($request->all());

        return redirect()->route('admin.ingredients.index')
                        ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(Ingredient $ingredient)
    {
        // Tambahan: Cek apakah bahan ini masih dipakai di menu item
        if ($ingredient->menuItems()->count() > 0) {
            return redirect()->route('admin.ingredients.index')
                            ->with('error', 'Bahan baku tidak bisa dihapus karena masih digunakan oleh menu.');
        }
        $ingredient->delete();
        return redirect()->route('admin.ingredients.index')
                        ->with('success', 'Bahan baku berhasil dihapus.');
    }
}

