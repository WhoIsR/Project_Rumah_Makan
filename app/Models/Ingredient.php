<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'base_unit_id', // 'unit' dan 'supplier_id' sudah dihapus
    ];

    /**
     * Relasi ke satuan dasar.
     */
    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    /**
     * Menu item yang menggunakan bahan ini.
     */
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_ingredients')
                    ->withPivot('quantity_needed')
                    ->withTimestamps();
    }
}