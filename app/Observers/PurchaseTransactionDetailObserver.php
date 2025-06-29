<?php

namespace App\Observers;

use App\Models\PurchaseTransactionDetail;
use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Support\Facades\Log;

class PurchaseTransactionDetailObserver
{
    public function created(PurchaseTransactionDetail $detail): void
    {
        try {
            $ingredient = $detail->ingredient;
            if (!$ingredient) {
                Log::error('Observer Gagal: Ingredient tidak ditemukan untuk detail ID: ' . $detail->id);
                return;
            }
            $quantityInBaseUnit = $this->convertToBaseUnit($detail->quantity, $detail->unit, $ingredient);
            $ingredient->increment('stock', $quantityInBaseUnit);
        } catch (\Exception $e) {
            Log::error('Observer Gagal Menambah Stok (created): ' . $e->getMessage());
        }
    }

    public function updated(PurchaseTransactionDetail $detail): void
    {
        try {
            $ingredient = $detail->ingredient;
            $oldQuantity = $detail->getOriginal('quantity');
            $newQuantity = $detail->quantity;
            $oldUnitSymbol = $detail->getOriginal('unit');
            $newUnitSymbol = $detail->unit;
            $oldQuantityInBaseUnit = $this->convertToBaseUnit($oldQuantity, $oldUnitSymbol, $ingredient);
            $newQuantityInBaseUnit = $this->convertToBaseUnit($newQuantity, $newUnitSymbol, $ingredient);
            $difference = $newQuantityInBaseUnit - $oldQuantityInBaseUnit;
            $ingredient->increment('stock', $difference);
        } catch (\Exception $e) {
            Log::error('Observer Gagal Mengupdate Stok (updated): ' . $e->getMessage());
        }
    }

    public function deleted(PurchaseTransactionDetail $detail): void
    {
        try {
            $ingredient = $detail->ingredient;
            $quantityInBaseUnit = $this->convertToBaseUnit($detail->quantity, $detail->unit, $ingredient);
            $ingredient->decrement('stock', $quantityInBaseUnit);
        } catch (\Exception $e) {
            Log::error('Observer Gagal Mengurangi Stok (deleted): ' . $e->getMessage());
        }
    }

    private function convertToBaseUnit(float $quantity, string $unitSymbol, Ingredient $ingredient): float
    {
        if (!$ingredient->baseUnit) {
            throw new \Exception("Bahan baku '{$ingredient->name}' tidak memiliki satuan dasar (base unit).");
        }
        if ($unitSymbol === $ingredient->baseUnit->symbol) {
            return $quantity;
        }
        $inputUnit = Unit::where('symbol', $unitSymbol)->first();
        if (!$inputUnit || !$inputUnit->base_unit_id || $inputUnit->base_unit_id !== $ingredient->base_unit_id) {
            throw new \Exception("Konversi dari {$unitSymbol} ke {$ingredient->baseUnit->symbol} tidak valid.");
        }
        return $quantity * $inputUnit->conversion_factor;
    }
}