# 🚀 Cara Menjalankan Aplikasi Sistem LPG

## ✅ Aplikasi Sudah Siap!

Semua konfigurasi sudah selesai dan aplikasi siap dijalankan.

## 📋 Yang Sudah Dikonfigurasi:

✅ Database MySQL sudah dibuat otomatis (`sistem_lpg`)
✅ Tabel database sudah dibuat (migrations)
✅ Data awal sudah diisi (seeder)
✅ Assets CSS/JS sudah di-build
✅ Storage link sudah dibuat

## 🎯 Cara Menjalankan:

### 1. Pastikan Laragon MySQL Berjalan
   - Buka Laragon
   - Pastikan MySQL sudah running (hijau)

### 2. Jalankan Server Laravel
   Buka terminal di folder proyek dan jalankan:
   ```bash
   php artisan serve
   ```

### 3. Buka Browser
   Akses: `http://localhost:8000/login`

## 👤 Akun Login:

### Admin Pangkalan
- **Email:** admin@lpg.com
- **Password:** admin123

### Sub Pangkalan 1 (Jaya Abadi)
- **Email:** jaya@lpg.com
- **Password:** user123

### Sub Pangkalan 2 (Makmur Sentosa)
- **Email:** makmur@lpg.com
- **Password:** user123

## 🔄 Jika Ingin Reset Database:

```bash
php artisan migrate:fresh --seed
```

## 🛠️ Jika Ingin Rebuild Assets:

```bash
npm run build
```

Atau untuk development dengan hot reload:
```bash
npm run dev
```

## ✨ Selesai!

Aplikasi siap digunakan. Selamat mencoba! 🎉
