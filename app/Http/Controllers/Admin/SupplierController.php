<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'email' => 'nullable|email|unique:suppliers,email',
        ]);

        Supplier::create($validatedData);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'email' => 'nullable|email|unique:suppliers,email,' . $supplier->id, // unique kecuali untuk id saat ini
        ]);

        $supplier->update($validatedData);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani kasus jika ada bahan baku yang terhubung dengan supplier ini
            return redirect()->route('admin.suppliers.index')->with('error', 'Gagal menghapus supplier. Pastikan tidak ada bahan baku yang terhubung dengan supplier ini.');
        }
    }
}
