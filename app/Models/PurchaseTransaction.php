<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'total_amount',
        'notes',
        'transaction_date',
    ];

    /**
     * Relasi ke supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi ke detail pembelian.
     */
    public function details()
    {
        return $this->hasMany(PurchaseTransactionDetail::class);
    }
}