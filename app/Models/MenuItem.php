<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image_path',
    ];

    /**
     * Bahan-bahan yang dibutuhkan untuk menu item ini.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'menu_item_ingredients')
                    ->withPivot('quantity_needed') // Penting untuk mengambil jumlah yang dibutuhkan
                    ->withTimestamps(); // Jika tabel pivot pakai timestamps
    }

    public function category()
    {
        // 'category_id' adalah foreign key di tabel menu_items
        return $this->belongsTo(Category::class, 'category_id');
    }
}

