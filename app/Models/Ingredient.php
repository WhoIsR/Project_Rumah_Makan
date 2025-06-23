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
        'unit',
        'supplier_id',
    ];

    /**
     * Menu item yang menggunakan bahan ini.
     */
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_ingredients')
                    ->withPivot('quantity_needed')
                    ->withTimestamps();
    }

    /**
     * Supplier yang menyediakan bahan ini.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
