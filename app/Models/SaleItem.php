<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'tabung_type', 'quantity'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
