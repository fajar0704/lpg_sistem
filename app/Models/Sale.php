<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'customer_id', 'user_id',
        'sub_pangkalan_id', 'sold_by', 'sale_date', 'total_quantity',
    ];

    protected $casts = ['sale_date' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subPangkalan()
    {
        return $this->belongsTo(SubPangkalan::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateInvoice(): string
    {
        $last = self::latest()->first();
        $number = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;
        return 'INV-' . now()->format('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
