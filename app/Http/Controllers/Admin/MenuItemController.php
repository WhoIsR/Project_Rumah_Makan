<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::orderBy('name')->paginate(10);
        return view('admin.menu-items.index', compact('menuItems'));
    }

    public function create()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        // =======================================================
        // ==> PERBAIKAN DI SINI: Tambahkan 'categories' ke kurir
        // =======================================================
        return view('admin.menu-items.create', compact('ingredients', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi, pastikan category_id ada dan valid
        $request->validate([
            'name' => 'required|string|max:255|unique:menu_items,name',
            'category_id' => 'nullable|exists:categories,id', // Diubah jadi nullable, jadi tidak wajib
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'item_ingredients' => 'nullable|array',
            'item_ingredients.*.id' => 'required_with:item_ingredients|exists:ingredients,id',
            'item_ingredients.*.quantity' => 'required_with:item_ingredients|integer|min:1',
        ]);

        // ====================================================================
        // ==> PERBAIKAN DI SINI: Ambil 'category_id' dari request
        // ====================================================================
        $menuItemData = $request->only(['name', 'description', 'price', 'category_id']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu_images', 'public');
            $menuItemData['image_path'] = $path;
        }

        $menuItem = MenuItem::create($menuItemData);

        if ($request->has('item_ingredients')) {
            $ingredientsToSync = [];
            foreach ($request->item_ingredients as $ingredientInput) {
                if (!empty($ingredientInput['id']) && !empty($ingredientInput['quantity'])) {
                    $ingredientsToSync[$ingredientInput['id']] = ['quantity_needed' => $ingredientInput['quantity']];
                }
            }
            $menuItem->ingredients()->sync($ingredientsToSync);
        }

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function show(MenuItem $menuItem)
    {
        $menuItem->load('ingredients');
        return view('admin.menu-items.show', compact('menuItem'));
    }

    public function edit(MenuItem $menuItem)
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $menuItem->load('ingredients');

        // =======================================================
        // ==> PERBAIKAN DI SINI: Tambahkan 'categories' ke kurir
        // =======================================================
        return view('admin.menu-items.edit', compact('menuItem', 'ingredients', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:menu_items,name,' . $menuItem->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'remove_existing_image' => 'boolean', // Ini adalah input hidden yang baru
            'item_ingredients' => 'nullable|array',
            'item_ingredients.*.id' => 'required_with:item_ingredients|exists:ingredients,id',
            'item_ingredients.*.quantity' => 'required_with:item_ingredients|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Handle Image Upload / Removal
            if ($request->boolean('remove_existing_image')) {
                // Hapus gambar lama jika ada dan diminta
                if ($menuItem->image_path) {
                    Storage::delete($menuItem->image_path);
                }
                $menuItem->image_path = null;
            } elseif ($request->hasFile('image')) {
                // Upload gambar baru jika ada
                // Hapus gambar lama jika ada dan ada gambar baru yang diupload
                if ($menuItem->image_path) {
                    Storage::delete($menuItem->image_path);
                }
                $imagePath = $request->file('image')->store('menu_images', 'public');
                $menuItem->image_path = $imagePath;
            }
            // Jika tidak ada request image baru DAN tidak ada request hapus, image_path tetap sama

            $menuItem->name = $validatedData['name'];
            $menuItem->description = $validatedData['description'];
            $menuItem->price = $validatedData['price'];
            $menuItem->category_id = $validatedData['category_id'];
            $menuItem->save(); // Simpan perubahan pada menu item

            // Sinkronisasi bahan baku yang dibutuhkan (many-to-many)
            $syncData = [];
            if (isset($validatedData['item_ingredients'])) {
                foreach ($validatedData['item_ingredients'] as $ingredientData) {
                    $syncData[$ingredientData['id']] = ['quantity_needed' => $ingredientData['quantity']];
                }
            }
            $menuItem->ingredients()->sync($syncData); // Sync akan menambah, menghapus, atau update relasi

            DB::commit();
            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating menu item: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui menu: ' . $e->getMessage());
        }
    }


    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image_path) {
            Storage::disk('public')->delete($menuItem->image_path);
        }
        $menuItem->delete();

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
