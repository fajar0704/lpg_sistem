<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOutflow extends Model
{
    protected $fillable = [
        'stock_batch_id', 'tabung_type', 'quantity',
        'transaction_date', 'source', 'sourceable_type', 'sourceable_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(StockBatch::class, 'stock_batch_id');
    }

    public function sourceable()
    {
        return $this->morphTo();
    }
}
