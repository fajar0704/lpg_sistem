<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockBatch extends Model
{
    protected $fillable = [
        'tabung_type', 'quantity_in', 'quantity_remaining',
        'received_date', 'created_by',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isHabis(): bool
    {
        return $this->quantity_remaining == 0;
    }

    public function getStatusAttribute(): string
    {
        if ($this->quantity_remaining == 0) {
            return 'Habis';
        } elseif ($this->quantity_remaining < $this->quantity_in) {
            return 'Aktif';
        }
        return 'Baru';
    }
}
