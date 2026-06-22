<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanLangsung extends Model
{
    protected $table = 'penjualan_langsung';

    protected $fillable = [
        'user_id', 'customer_id', 'tabung_type', 'quantity',
        'customer_type', 'nama_pembeli', 'no_ktp', 'transaction_date', 'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
