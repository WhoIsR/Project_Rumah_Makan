<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone_number',
        'address',
        'email',
    ];

    /**
     * Bahan baku yang bisa disediakan oleh supplier ini. (Relasi Many-to-Many)
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_supplier')
                    ->withPivot('price', 'unit', 'last_supplied_at') // PERUBAHAN DI SINI
                    ->withTimestamps();
    }
}