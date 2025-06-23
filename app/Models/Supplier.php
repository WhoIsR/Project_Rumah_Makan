<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_person',
        'phone_number',
        'address',
        'email', // Biarkan kolom email tetap ada
    ];

    /**
     * Bahan baku yang disediakan oleh supplier ini.
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}