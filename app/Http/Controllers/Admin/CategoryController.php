<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori.
     * Ini yang dipanggil oleh route 'admin.categories.index'.
     */
    public function index()
    {
        // Ambil semua data kategori, plus hitung berapa banyak menu di tiap kategori
        $categories = Category::withCount('menuItems')->orderBy('name')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan kategori baru yang dikirim dari form.
     */
    public function store(Request $request)
    {
        // Validasi input, pastikan nama diisi dan unik (tidak boleh sama)
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Buat kategori baru di database
        Category::create($request->all());

        // Alihkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     */
    public function edit(Category $category)
    {
        // Tampilkan halaman 'edit.blade.php' dan kirim data kategori yang mau diedit
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        // Validasi input, nama boleh sama dengan nama lama, tapi harus unik dari yang lain
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // Update data kategori
        $category->update($request->all());

        // Alihkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        // Pengecekan cerdas: Jangan biarkan kategori dihapus jika masih ada menu yang memakainya.
        if ($category->menuItems()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh beberapa menu.');
        }

        // Hapus kategori
        $category->delete();

        // Alihkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function getMenuItems(Category $category)
    {
        // Eager load relasi menuItems untuk kategori ini
        $menuItems = $category->menuItems()->get();

        // Mengembalikan data sebagai respons JSON
        return response()->json($menuItems);
    }
}