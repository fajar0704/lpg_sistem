<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ktp', 'phone', 'address', 'category', 'is_active', 'photo', 'kk_photo', 'sub_pangkalan_id'];

    protected $casts = ['is_active' => 'boolean'];

    public function subPangkalan()
    {
        return $this->belongsTo(SubPangkalan::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'rumah_tangga'  => 'Rumah Tangga',
            'usaha_mikro'   => 'Usaha Mikro',
            'pengecer'      => 'Pengecer',
            'konsumen_umum' => 'Konsumen Umum (Non Subsidi)',
            default         => $this->category,
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
        return $this->category === 'rumah_tangga' ? 5 : ($this->category === 'usaha_mikro' ? 10 : 999);
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
        $lastDirect = $this->penjualanLangsung()
            ->latest('transaction_date')
            ->latest('created_at')
            ->first();

        $lastSubTx = \App\Models\SubPangkalanTransaction::where('customer_id', $this->id)
            ->where('transaction_type', 'sell')
            ->latest('transaction_date')
            ->latest('created_at')
            ->first();

        $last = null;
        if ($lastDirect && $lastSubTx) {
            $last = $lastDirect->created_at > $lastSubTx->created_at ? $lastDirect : $lastSubTx;
        } else {
            $last = $lastDirect ?? $lastSubTx;
        }

        if (!$last) {
            return 'Belum ada transaksi';
        }

        $dateStr = \Carbon\Carbon::parse($last->transaction_date)->translatedFormat('d M Y');
        $timeStr = \Carbon\Carbon::parse($last->created_at)->timezone('Asia/Jakarta')->format('H:i');
        return "{$dateStr} ({$timeStr} WIB)";
    }
}
