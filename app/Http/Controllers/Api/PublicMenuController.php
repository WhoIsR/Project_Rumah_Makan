<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk URL gambar

class PublicMenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu item beserta relasi bahan-bahannya
        // Eager load ingredients dan pivot data quantity_needed
        $menuItems = MenuItem::with('ingredients')->orderBy('name')->get();

        $formattedMenus = $menuItems->map(function ($menuItem) {
            $isAvailable = true; // Asumsi awal: menu tersedia

            // Cek ketersediaan setiap bahan yang dibutuhkan
            if ($menuItem->ingredients->isEmpty()) {
                // Jika menu tidak memiliki bahan terdaftar, anggap tersedia (atau bisa diatur sebagai tidak tersedia)
                // $isAvailable = false; // Tergantung logika bisnis
            } else {
                foreach ($menuItem->ingredients as $ingredient) {
                    // $ingredient adalah model Ingredient yang terkait
                    // $ingredient->pivot adalah data dari tabel pivot (menu_item_ingredients)
                    if ($ingredient->stock < $ingredient->pivot->quantity_needed) {
                        $isAvailable = false; // Jika satu bahan kurang, menu tidak tersedia
                        break; // Tidak perlu cek bahan lain untuk menu ini
                    }
                }
            }

            // Dapatkan URL gambar jika ada dan pastikan storage link sudah dibuat
            $imageUrl = null;
            if ($menuItem->image_path) {
                // Pastikan APP_URL di .env sudah benar untuk path gambar yang valid
                $imageUrl = Storage::disk('public')->exists($menuItem->image_path) ? Storage::url($menuItem->image_path) : null;
            }


            return [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'description' => $menuItem->description,
                'price' => (float) $menuItem->price, // Pastikan tipe data sesuai
                'image_url' => $imageUrl, // URL lengkap ke gambar
                'is_available' => $isAvailable, // Ini kuncinya!
                // Kamu bisa tambahkan data lain jika perlu, misal kategori
            ];
        });

        return response()->json($formattedMenus);
    }
}
