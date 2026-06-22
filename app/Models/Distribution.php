<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_pangkalan_id', 'user_id', 'tabung_type', 'quantity',
        'type', 'transaction_type', 'customer_type',
        'transaction_date', 'status', 'notes', 'validated_by', 'validated_at',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'validated_at' => 'datetime',
    ];

    public function subPangkalan()
    {
        return $this->belongsTo(SubPangkalan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function approve()
    {
        $this->status = 'approved';
        $this->validated_at = now();
        $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->validated_at = now();
        $this->save();
    }
}
