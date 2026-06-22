<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\StockLpg;
use App\Models\SubPangkalan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'      => 'Admin Pangkalan',
            'email'     => 'admin@lpg.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Sub Pangkalan
        $sp1 = SubPangkalan::create(['name' => 'Sub Pangkalan Jaya Abadi',      'code' => 'SPJ001', 'address' => 'Jl. Raya Utama No. 123', 'phone' => '081234567890', 'is_active' => true]);
        $sp2 = SubPangkalan::create(['name' => 'Sub Pangkalan Makmur Sentosa',  'code' => 'SPM002', 'address' => 'Jl. Merdeka No. 45',     'phone' => '081234567891', 'is_active' => true]);

        User::create(['name' => 'User Jaya Abadi',     'email' => 'jaya@lpg.com',   'password' => Hash::make('user123'), 'role' => 'sub_pangkalan', 'sub_pangkalan_id' => $sp1->id, 'is_active' => true]);
        User::create(['name' => 'User Makmur Sentosa', 'email' => 'makmur@lpg.com', 'password' => Hash::make('user123'), 'role' => 'sub_pangkalan', 'sub_pangkalan_id' => $sp2->id, 'is_active' => true]);

        // Stock LPG
        foreach ([['3kg', 100, 10], ['5kg', 60, 5], ['12kg', 50, 5]] as [$type, $qty, $safety]) {
            StockLpg::create([
                'tabung_type'   => $type,
                'initial_stock' => $qty,
                'current_stock' => $qty,
                'stock_in'      => $qty,
                'stock_out'     => 0,
                'safety_stock'  => $safety,
            ]);
        }

        // Sample Customers
        Customer::insert([
            ['name' => 'Budi Santoso',   'ktp' => '3201010101010001', 'phone' => '081111111111', 'address' => 'Jl. Mawar No. 1',  'category' => 'rumah_tangga', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Siti Rahayu',    'ktp' => '3201010101010002', 'phone' => '081111111112', 'address' => 'Jl. Melati No. 2', 'category' => 'rumah_tangga', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Warung Pak Eko', 'ktp' => '3201010101010003', 'phone' => '081111111113', 'address' => 'Jl. Pasar No. 5',  'category' => 'usaha_mikro',  'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Toko Bu Ani',    'ktp' => '3201010101010004', 'phone' => '081111111114', 'address' => 'Jl. Dagang No. 3', 'category' => 'pengecer',     'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
