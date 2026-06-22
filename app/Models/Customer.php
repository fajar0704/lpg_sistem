<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ktp', 'phone', 'address', 'category', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'rumah_tangga' => 'Rumah Tangga',
            'usaha_mikro'  => 'Usaha Mikro',
            'pengecer'     => 'Pengecer',
            default        => $this->category,
        };
    }

    public function penjualanLangsung()
    {
        return $this->hasMany(PenjualanLangsung::class, 'customer_id');
    }

    public function getMaxQuota(string $tabungType): int
    {
        if ($tabungType !== '3kg') {
            return 999; // Unlimited for non-subsidized
        }
        return $this->category === 'rumah_tangga' ? 4 : ($this->category === 'usaha_mikro' ? 10 : 999);
    }

    public function getUsedQuotaThisMonth(string $tabungType): int
    {
        return $this->penjualanLangsung()
            ->where('tabung_type', $tabungType)
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('quantity');
    }

    public function getLastTransactionDate()
    {
        $last = $this->penjualanLangsung()->latest('transaction_date')->first();
        return $last ? $last->transaction_date->diffForHumans() : 'Belum ada transaksi';
    }
}
