<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPangkalan extends Model
{
    use HasFactory;

    protected $table = 'sub_pangkalan';

    protected $fillable = [
        'name', 'code', 'address', 'phone', 'is_active', 'stok_isi', 'stok_kosong', 'ktp', 'photo', 'kk_photo',
        'nama_ktp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_ktp'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Tambah stok isi saat terima LPG dari pangkalan
    public function terimaLpg(int $jumlah): void
    {
        $this->increment('stok_isi', $jumlah);
    }

    // Saat penjualan: isi berkurang, kosong bertambah
    public function jual(int $jumlah): void
    {
        $this->decrement('stok_isi', $jumlah);
        $this->increment('stok_kosong', $jumlah);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(SubPangkalanTransaction::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
