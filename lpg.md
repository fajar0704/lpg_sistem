Ringkasan Proyek: Sistem Aplikasi Penjualan & Pengelolaan Stok Gas LPG
📋 Informasi Umum
Nama Proyek: Sistem Aplikasi Penjualan dan Pengelolaan Stok Gas LPG Berbasis Web
Framework: Laravel
UI/CSS: Tailwind CSS
Database: Laragon (MySQL/MariaDB)
Lokasi Proyek: d:\Visual Code\sistem_Lpg

👥 Aktor Sistem
1️⃣ Admin Pangkalan (Full Control)
Pengelola utama dengan akses penuh ke semua fitur sistem.

2️⃣ User Sub Pangkalan (Input Data)
Pengguna dengan hak akses terbatas hanya untuk menginput data distribusi tabung.

🎯 Fitur Admin Pangkalan
A. Autentikasi & Manajemen Akses
✅ Login Admin
✅ Logout
✅ Manajemen Sub Pangkalan (Tambah, Edit, Nonaktifkan)

B. Dashboard Admin
📊 Total Sub Pangkalan terdaftar
📦 Total stok tabung LPG
📥 Total distribusi masuk
📝 Riwayat input terbaru (real-time)

C. Manajemen Data Sub Pangkalan
👁️ Lihat daftar Sub Pangkalan
📋 Detail aktivitas tiap Sub Pangkalan
📜 Riwayat input tabung per Sub Pangkalan

D. Manajemen Stok Gas LPG
📊 Data stok awal
📥 Stok masuk (input dari Sub Pangkalan)
📤 Stok keluar (penyaluran)
✨ Stok akhir otomatis (kalkulasi sistem)

E. Monitoring & Validasi Data
✔️ Menerima data input dari Sub Pangkalan
🔍 Validasi data (setujui/tolak)
🏷️ Penandaan status (Valid / Pending / Ditolak)

F. Laporan
📅 Laporan distribusi (harian/bulanan)
🏢 Laporan per Sub Pangkalan
📥 Ekspor laporan (PDF / Excel)
🎯 Fitur User Sub Pangkalan

A. Autentikasi
🔐 Login dengan Username & Password
🛡️ Autentikasi berbasis Role (Sub Pangkalan)

B. Dashboard Sub Pangkalan
🏪 Identitas Sub Pangkalan
📝 Riwayat input tabung
📊 Status data (Diterima / Pending)

C. Input Jumlah Tabung ⭐ (Fitur Utama)
📥 Input jumlah tabung diterima/disalurkan
📅 Input tanggal
💾 Simpan data
➡️ Data otomatis masuk ke sistem Admin Pangkalan

🔄 Alur Sistem Kerja
Sub Pangkalan Login 
  ↓
Dashboard Sub Pangkalan
  ↓
Input Jumlah Tabung
  ↓
Klik Simpan
  ↓
Data Tersimpan di Database
  ↓
Data Muncul di:
  ├─ Dashboard Admin
  ├─ Manajemen Stok Admin
  └─ Laporan Admin
D. Logout
🚪 Mengakhiri sesi pengguna

🚫 Batasan & Keamanan
Sub Pangkalan	Admin Pangkalan
❌ Tidak bisa mengubah stok utama	✅ Akses penuh ke semua fitur
❌ Tidak bisa akses fitur Admin	✅ Validasi & manajemen data
✅ Hanya bisa input data	✅ Generate laporan
✅ Lihat status data sendiri	✅ Monitor real-time