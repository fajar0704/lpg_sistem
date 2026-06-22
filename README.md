# Sistem Aplikasi Penjualan & Pengelolaan Stok Gas LPG

Aplikasi berbasis web untuk mengelola penjualan dan distribusi stok gas LPG.

## 🚀 Fitur Utama

### Admin Pangkalan
- ✅ Dashboard dengan statistik real-time
- ✅ Manajemen Sub Pangkalan (CRUD)
- ✅ Manajemen Stok LPG
- ✅ Validasi Distribusi (Setujui/Tolak)
- ✅ Laporan Distribusi (Harian/Bulanan/Per Sub Pangkalan)

### User Sub Pangkalan
- ✅ Dashboard Sub Pangkalan
- ✅ Input Distribusi Tabung (Masuk/Keluar)
- ✅ Riwayat Input dengan Status Validasi

## 📋 Teknologi

- **Framework:** Laravel 12
- **UI/CSS:** Tailwind CSS
- **Database:** SQLite/MySQL
- **Mobile:** Fully Responsive Design ✅

## 🔧 Instalasi

1. **Clone atau download proyek ini**

2. **Install dependencies PHP**
   ```bash
   composer install
   ```

3. **Install dependencies Node.js**
   ```bash
   npm install
   ```

4. **Konfigurasi environment**
   - Copy file `.env.example` ke `.env`
   - Sesuaikan konfigurasi database di `.env`

5. **Generate application key** (jika belum)
   ```bash
   php artisan key:generate
   ```

6. **Jalankan migrations dan seeder**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

   Aplikasi akan berjalan di `http://localhost:8000`

## 📱 Akses dari Mobile

1. **Cek IP komputer Anda**
   ```bash
   ipconfig
   ```

2. **Jalankan server dengan host 0.0.0.0**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

3. **Akses dari HP**
   ```
   http://[IP-KOMPUTER]:8000
   ```
   Contoh: `http://192.168.1.100:8000`

**Fitur Mobile:**
- ✅ Responsive layout untuk semua ukuran layar
- ✅ Hamburger menu untuk navigasi mobile
- ✅ Touch-friendly buttons (min 44x44px)
- ✅ Table dengan horizontal scroll
- ✅ Optimized untuk iOS & Android

Lihat [MOBILE-GUIDE.md](MOBILE-GUIDE.md) untuk detail lengkap.

## 👤 Akun Default

### Admin
- **Email:** admin@lpg.com
- **Password:** admin123

### Sub Pangkalan 1
- **Email:** jaya@lpg.com
- **Password:** user123

### Sub Pangkalan 2
- **Email:** makmur@lpg.com
- **Password:** user123

## 📁 Struktur Database

### Tabel Utama
- `users` - Data pengguna (Admin & Sub Pangkalan)
- `sub_pangkalan` - Data Sub Pangkalan
- `stock_lpg` - Stok tabung LPG per tipe (3kg, 12kg, 50kg)
- `distributions` - Transaksi distribusi masuk/keluar
- `reports` - Laporan yang di-generate

## 🔄 Alur Sistem

1. **Sub Pangkalan Login** → Dashboard → Input Distribusi
2. **Data tersimpan** dengan status "Pending"
3. **Admin Login** → Dashboard → Validasi Distribusi
4. **Admin menyetujui/menolak** distribusi
5. **Stok otomatis terupdate** jika disetujui
6. **Admin dapat generate laporan** distribusi

## 🛠️ Development

### Menjalankan development server dengan hot reload
```bash
npm run dev
```

### Menjalankan tests
```bash
php artisan test
```

## 📊 Fitur Laporan

- **Laporan Harian:** Filter berdasarkan tanggal
- **Laporan Bulanan:** Filter berdasarkan bulan
- **Laporan Per Sub Pangkalan:** Filter per Sub Pangkalan tertentu
- **Export:** PDF dan Excel (ready untuk implementasi)

## 🔐 Keamanan

- Role-based access control (Admin & Sub Pangkalan)
- Middleware untuk proteksi route
- Password hashing dengan bcrypt
- CSRF protection
- Input validation

## 📝 Catatan

- Aplikasi menggunakan SQLite sebagai database default
- Untuk production, disarankan menggunakan MySQL
- Export PDF/Excel memerlukan package tambahan:
  - PDF: `barryvdh/laravel-dompdf`
  - Excel: `maatwebsite/excel`

## 📄 License

Proprietary - Sistem LPG

---

**Dibuat dengan ❤️ menggunakan Laravel & Tailwind CSS**
