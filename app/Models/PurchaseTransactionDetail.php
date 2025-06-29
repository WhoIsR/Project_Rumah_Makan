<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_transaction_id',
        'ingredient_id',
        'quantity',
        'unit',
        'price_per_unit',
        'subtotal',
    ];

    /**
     * Relasi ke bahan baku.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Relasi ke transaksi utama.
     */
    public function purchaseTransaction()
    {
        return $this->belongsTo(PurchaseTransaction::class);
    }
}