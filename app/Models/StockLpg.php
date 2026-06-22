<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLpg extends Model
{
    use HasFactory;

    protected $table = 'stock_lpg';

    protected $fillable = [
        'tabung_type',
        'initial_stock',
        'current_stock',
        'stok_isi',
        'stok_kosong',
        'stock_in',
        'stock_out',
        'safety_stock',
    ];

    public function isBelowSafety(): bool
    {
        return $this->stok_isi <= $this->safety_stock;
    }

    public function getMaxStockAttribute(): int
    {
        return match ($this->tabung_type) {
            '3kg' => 120,
            '5kg' => 10,
            '12kg' => 20,
            default => 0,
        };
    }

    public function sisaKapasitas(): int
    {
        return max(0, $this->max_stock - $this->stok_isi);
    }

    // Terima dari Pertamina/Agen (tanpa tukar kosong): stok isi pangkalan bertambah
    public function terimaStok(int $quantity): void
    {
        $this->increment('stok_isi', $quantity);
        $this->increment('stock_in', $quantity);
        $this->increment('current_stock', $quantity);
    }

    // Terima dari Agen dengan tukar tabung kosong:
    // stok isi bertambah, stok kosong otomatis berkurang (ditukar ke agen)
    public function terimaStokDariAgen(int $quantity): void
    {
        // Hitung kosong yang bisa dikembalikan ke agen (tidak lebih dari stok kosong saat ini)
        $kosongDikembalikan = min($quantity, $this->stok_kosong);

        $this->increment('stok_isi', $quantity);
        $this->increment('stock_in', $quantity);
        $this->increment('current_stock', $quantity);

        // Kurangi stok kosong karena dikembalikan ke agen sebagai penukar
        if ($kosongDikembalikan > 0) {
            $this->decrement('stok_kosong', $kosongDikembalikan);
        }
    }

    // Kirim ke sub pangkalan: stok isi pangkalan berkurang
    public function kirimKeSubPangkalan(int $quantity): void
    {
        $this->decrement('stok_isi', $quantity);
        $this->increment('stock_out', $quantity);
        $this->decrement('current_stock', $quantity);
    }

    // Jual langsung ke pembeli: stok isi berkurang, kosong bertambah
    public function jualLangsung(int $quantity): void
    {
        $this->decrement('stok_isi', $quantity);
        $this->increment('stok_kosong', $quantity);
        $this->increment('stock_out', $quantity);
        $this->decrement('current_stock', $quantity);
    }

    // Terima tabung kosong dari sub pangkalan saat tukar
    public function terimaKosong(int $quantity): void
    {
        $this->increment('stok_kosong', $quantity);
    }

    public function updateStock(int $quantity, string $type): void
    {
        if ($type === 'in') {
            $this->increment('stock_in', $quantity);
            $this->increment('current_stock', $quantity);
        } else {
            $this->increment('stock_out', $quantity);
            $this->decrement('current_stock', $quantity);
        }
    }
}
