@extends('layouts.admin')

@section('title', 'Edit Pelanggan - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div>
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Pelanggan
        </a>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
            <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Data Pelanggan
        </h2>
        <p class="text-slate-500 text-sm mt-1">Perbarui informasi dan dokumen foto identitas pelanggan pangkalan LPG.</p>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl">
        <!-- Kolom Kiri: Form Identitas Pelanggan -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

            <form id="edit-customer-form" action="{{ route('admin.customers.update', $customer) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <h3 class="text-base font-extrabold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Identitas
                </h3>

                <!-- Hidden Input for Photo Base64 -->
                <input type="hidden" name="photo" id="photo_input">
                <input type="hidden" name="kk_photo" id="kk_photo_input">

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('name') border-rose-500 focus:ring-rose-500/20 @enderror">
                    @error('name')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Nomor KTP (NIK) -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2">No. KTP / NIK <span class="text-rose-500">*</span></label>
                    <input type="text" name="ktp" value="{{ old('ktp', $customer->ktp) }}" required placeholder="Masukkan 16 digit NIK" maxlength="16" autocomplete="off"
                        class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('ktp') border-rose-500 focus:ring-rose-500/20 @enderror">
                    @error('ktp')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Telepon -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2">No. Telepon <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required placeholder="Contoh: 081234567890"
                        class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('phone') border-rose-500 focus:ring-rose-500/20 @enderror">
                    @error('phone')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Alamat Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address', $customer->address) }}" required placeholder="Nama jalan, RT/RW, nomor rumah"
                        class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('address') border-rose-500 focus:ring-rose-500/20 @enderror">
                    @error('address')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Kategori Pelanggan -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Kategori <span class="text-rose-500">*</span></label>
                    <select name="category" required
                        class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('category') border-rose-500 focus:ring-rose-500/20 @enderror">
                        <option value="">Pilih Kategori</option>
                        <option value="rumah_tangga" {{ old('category', $customer->category) === 'rumah_tangga' ? 'selected' : '' }}>🏠 Rumah Tangga</option>
                        <option value="usaha_mikro"  {{ old('category', $customer->category) === 'usaha_mikro'  ? 'selected' : '' }}>🏪 UMKM (Usaha Mikro)</option>
                        <option value="konsumen_umum" {{ old('category', $customer->category) === 'konsumen_umum' ? 'selected' : '' }}>🏢 Konsumen Umum (Pembeli Non Subsidi)</option>
                    </select>
                    @error('category')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <!-- Action Buttons -->
                <div class="pt-5 border-t border-slate-100 flex items-center gap-3">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-3 rounded-xl transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Foto Tersimpan & Kamera Live ON -->
        <div class="space-y-6 flex flex-col">
            <!-- Widget Foto KTP Saat Ini -->
            <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                <h3 class="font-extrabold text-slate-800 text-base mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Foto KTP Saat Ini
                </h3>

                @if($customer->photo)
                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-50 p-2 text-center shadow-xs">
                        <img src="{{ asset('storage/' . $customer->photo) }}" alt="Foto KTP {{ $customer->name }}" class="w-full max-h-48 object-contain rounded-lg mx-auto">
                    </div>
                @else
                    <div class="p-6 rounded-xl border-2 border-dashed border-slate-200 text-center bg-slate-50/50">
                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-xs font-bold text-slate-400">Belum ada foto KTP yang tersimpan</p>
                    </div>
                @endif
            </div>

            <!-- Widget Kamera Webcam (Otomatis Aktif ON) -->
            <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-slate-800 text-base tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Ambil Foto Baru
                        </h3>
                        <span id="camera-status-badge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                            Nonaktif
                        </span>
                    </div>

                    <!-- Tombol Aktifkan Kamera (Default Visible) -->
                    <div id="camera-activation-area" class="flex flex-col items-center justify-center py-10 bg-slate-50 border border-dashed border-slate-300 rounded-2xl shadow-sm text-center space-y-3">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-slate-500 font-semibold">Gunakan kamera untuk mengambil foto KTP secara langsung.</p>
                        <div class="flex items-center gap-3">
                            <button type="button" id="btn-start-camera" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 transition duration-200 shadow-md shadow-blue-500/10 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Aktifkan Kamera</span>
                            </button>
                            <span class="text-xs text-slate-400">atau</span>
                            <label for="fallback-file-input" class="px-5 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs cursor-pointer transition">
                                Pilih Foto File
                            </label>
                        </div>
                    </div>

                    <!-- Camera Active Area (Hidden by Default) -->
                    <div id="camera-active-area" class="hidden space-y-3">
                        <!-- Viewport Box Kamera -->
                        <div class="bg-slate-950 aspect-video rounded-2xl relative overflow-hidden flex flex-col items-center justify-center border border-slate-800 shadow-inner group">
                            <!-- Placeholder jika Kamera Dimatikan / Error -->
                            <div id="camera-placeholder" class="text-center space-y-2 p-6 text-slate-500 transition duration-300">
                                <svg class="w-12 h-12 mx-auto text-slate-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs font-bold text-slate-400">Menghubungkan Kamera...</p>
                            </div>

                            <!-- Video Stream -->
                            <video id="camera-stream" autoplay playsinline class="w-full h-full object-cover hidden"></video>

                            <!-- Canvas Captured Frame Preview -->
                            <canvas id="camera-canvas" class="w-full h-full object-cover hidden absolute inset-0 z-10 border-4 border-emerald-500 rounded-2xl"></canvas>

                            <!-- Floating Small Icon Controls overlay at bottom center -->
                            <div id="camera-controls-overlay" class="absolute z-20 flex items-center justify-center gap-3" style="bottom: 16px; left: 50%; transform: translateX(-50%); width: max-content;">
                                <!-- Beralih Kamera (Small Icon Button) -->
                                <button type="button" id="btn-toggle-camera" title="Beralih Kamera (Laptop/HP)" class="w-10 h-10 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 active:scale-95 shadow-lg border border-white/20 backdrop-blur-md cursor-pointer group">
                                    <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </button>

                                <!-- Ambil Foto (Transparent Shutter Button) -->
                                <button type="button" id="btn-capture-photo" title="Ambil Foto" class="w-12 h-12 rounded-full bg-transparent border-2 border-white text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 active:scale-95 cursor-pointer shadow-lg hover:bg-white/15">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Ulangi Foto (Berada di luar/dibawah bingkai kamera) -->
                        <div class="flex justify-center mt-3">
                            <button type="button" id="btn-retake-photo" class="hidden px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white flex items-center gap-1.5 transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-md font-bold text-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"></path>
                                </svg>
                                <span>Ulangi Foto</span>
                            </button>
                        </div>

                        <!-- Helper label below video screen -->
                        <div class="flex items-center justify-between text-xs text-slate-500 px-1 pt-1">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                <span id="camera-device-label">Kamera Laptop / HP</span>
                            </div>
                            <label for="fallback-file-input" class="text-blue-600 hover:underline cursor-pointer font-semibold">
                                Pilih Foto File
                            </label>
                            <input type="file" id="fallback-file-input" accept="image/*" capture="environment" class="hidden">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widget Foto KK Saat Ini -->
            <div id="kk-current-photo-widget" class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden mt-6">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <h3 class="font-extrabold text-slate-800 text-base mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Foto KK Saat Ini
                </h3>

                <div id="kk-current-photo-container">
                    @if($customer->kk_photo)
                        <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-50 p-2 text-center shadow-xs">
                            <img src="{{ asset('storage/' . $customer->kk_photo) }}" alt="Foto KK {{ $customer->name }}" class="w-full max-h-48 object-contain rounded-lg mx-auto">
                        </div>
                    @else
                        <div class="p-6 rounded-xl border-2 border-dashed border-slate-200 text-center bg-slate-50/50">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs font-bold text-slate-400">Belum ada foto KK yang tersimpan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Widget Kamera Rekam KK -->
            <div id="kk-camera-widget" class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between mt-6">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-slate-800 text-base tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Ambil Foto KK Baru
                        </h3>
                        <span id="kk-camera-status-badge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                            Nonaktif
                        </span>
                    </div>

                    <!-- Tombol Aktifkan Kamera KK (Default Visible) -->
                    <div id="kk-camera-activation-area" class="flex flex-col items-center justify-center py-10 bg-slate-50 border border-dashed border-slate-300 rounded-2xl shadow-sm text-center space-y-3">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-slate-500 font-semibold">Gunakan kamera untuk mengambil foto KK secara langsung.</p>
                        <div class="flex items-center gap-3">
                            <button type="button" id="kk-btn-start-camera" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 transition duration-200 shadow-md shadow-blue-500/10 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Aktifkan Kamera KK</span>
                            </button>
                            <span class="text-xs text-slate-400">atau</span>
                            <label for="kk-fallback-file-input" class="px-5 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs cursor-pointer transition">
                                Pilih Foto File
                            </label>
                        </div>
                    </div>

                    <!-- Camera Active Area (Hidden by Default) -->
                    <div id="kk-camera-active-area" class="hidden space-y-3">
                        <!-- Viewport Box Kamera -->
                        <div class="bg-slate-950 aspect-video rounded-2xl relative overflow-hidden flex flex-col items-center justify-center border border-slate-800 shadow-inner group">
                            <!-- Placeholder View -->
                            <div id="kk-camera-placeholder" class="text-center space-y-2 p-6 text-slate-500 transition duration-300">
                                <svg class="w-12 h-12 mx-auto text-slate-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs font-bold text-slate-400">Menghubungkan Kamera...</p>
                            </div>

                            <!-- Video Stream -->
                            <video id="kk-camera-stream" autoplay playsinline class="w-full h-full object-cover hidden"></video>

                            <!-- Canvas Captured Frame Preview -->
                            <canvas id="kk-camera-canvas" class="w-full h-full object-cover hidden absolute inset-0 z-10 border-4 border-emerald-500 rounded-2xl"></canvas>

                            <!-- Floating Small Icon Controls overlay at bottom center -->
                            <div id="kk-camera-controls-overlay" class="absolute z-20 flex items-center justify-center gap-3" style="bottom: 16px; left: 50%; transform: translateX(-50%); width: max-content;">
                                <!-- Beralih Kamera (Small Icon Button) -->
                                <button type="button" id="kk-btn-toggle-camera" title="Beralih Kamera (Laptop/HP)" class="w-10 h-10 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 active:scale-95 shadow-lg border border-white/20 backdrop-blur-md cursor-pointer group">
                                    <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </button>

                                <!-- Ambil Foto (Transparent Shutter Button) -->
                                <button type="button" id="kk-btn-capture-photo" title="Ambil Foto" class="w-12 h-12 rounded-full bg-transparent border-2 border-white text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 active:scale-95 cursor-pointer shadow-lg hover:bg-white/15">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Ulangi Foto (Berada di luar/dibawah bingkai kamera) -->
                        <div class="flex justify-center mt-3">
                            <button type="button" id="kk-btn-retake-photo" class="hidden px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white flex items-center gap-1.5 transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-md font-bold text-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"></path>
                                </svg>
                                <span>Ulangi Foto</span>
                            </button>
                        </div>

                        <!-- Helper label below video screen -->
                        <div class="flex items-center justify-between text-xs text-slate-500 px-1 pt-1">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                <span id="kk-camera-device-label">Kamera Laptop / HP</span>
                            </div>
                            <label for="kk-fallback-file-input" class="text-blue-600 hover:underline cursor-pointer font-semibold">
                                Pilih Foto File
                            </label>
                            <input type="file" id="kk-fallback-file-input" accept="image/*" capture="environment" class="hidden">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- KTP CAMERA VARIABLES & SELECTORS ---
        let stream = null;
        let videoDevices = [];
        let currentDeviceIndex = 0;
        let currentFacingMode = 'environment';

        const video = document.getElementById('camera-stream');
        const canvas = document.getElementById('camera-canvas');
        const btnCapture = document.getElementById('btn-capture-photo');
        const btnRetake = document.getElementById('btn-retake-photo');
        const btnToggle = document.getElementById('btn-toggle-camera');
        const cameraPlaceholder = document.getElementById('camera-placeholder');
        const photoInput = document.getElementById('photo_input');
        const cameraBadge = document.getElementById('camera-status-badge');
        const cameraDeviceLabel = document.getElementById('camera-device-label');
        const fallbackFileInput = document.getElementById('fallback-file-input');

        const btnStartCamera = document.getElementById('btn-start-camera');
        const cameraActivationArea = document.getElementById('camera-activation-area');
        const cameraActiveArea = document.getElementById('camera-active-area');

        // --- KK CAMERA VARIABLES & SELECTORS ---
        let streamKK = null;
        let videoDevicesKK = [];
        let currentDeviceIndexKK = 0;
        let currentFacingModeKK = 'environment';

        const videoKK = document.getElementById('kk-camera-stream');
        const canvasKK = document.getElementById('kk-camera-canvas');
        const btnCaptureKK = document.getElementById('kk-btn-capture-photo');
        const btnRetakeKK = document.getElementById('kk-btn-retake-photo');
        const btnToggleKK = document.getElementById('kk-btn-toggle-camera');
        const cameraPlaceholderKK = document.getElementById('kk-camera-placeholder');
        const photoInputKK = document.getElementById('kk_photo_input');
        const cameraBadgeKK = document.getElementById('kk-camera-status-badge');
        const cameraDeviceLabelKK = document.getElementById('kk-camera-device-label');
        const fallbackFileInputKK = document.getElementById('kk-fallback-file-input');

        const btnStartCameraKK = document.getElementById('kk-btn-start-camera');
        const cameraActivationAreaKK = document.getElementById('kk-camera-activation-area');
        const cameraActiveAreaKK = document.getElementById('kk-camera-active-area');

        // --- CATEGORY CONDITIONAL TOGGLING ---
        const categorySelect = document.querySelector('select[name="category"]');
        const kkCurrentPhotoWidget = document.getElementById('kk-current-photo-widget');
        const kkCameraWidget = document.getElementById('kk-camera-widget');

        function toggleKKWidgets() {
            if (categorySelect.value === 'konsumen_umum') {
                if (kkCurrentPhotoWidget) kkCurrentPhotoWidget.classList.add('hidden');
                if (kkCameraWidget) kkCameraWidget.classList.add('hidden');
                // Reset KK
                photoInputKK.value = '';
                stopCameraTracksKK();
                videoKK.classList.add('hidden');
                canvasKK.classList.add('hidden');
                cameraActiveAreaKK.classList.add('hidden');
                cameraActivationAreaKK.classList.remove('hidden');
                if (cameraBadgeKK) {
                    cameraBadgeKK.innerHTML = 'Nonaktif';
                    cameraBadgeKK.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200';
                }
            } else {
                if (kkCurrentPhotoWidget) kkCurrentPhotoWidget.classList.remove('hidden');
                if (kkCameraWidget) kkCameraWidget.classList.remove('hidden');
            }
        }

        categorySelect.addEventListener('change', toggleKKWidgets);
        toggleKKWidgets(); // run on load

        // --- KTP CAMERA LOGIC ---
        async function refreshVideoDevices() {
            try {
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    videoDevices = devices.filter(d => d.kind === 'videoinput');
                }
            } catch (err) {
                console.warn('Could not enumerate video devices', err);
            }
        }

        async function initCamera() {
            canvas.classList.add('hidden');
            btnRetake.classList.add('hidden');
            btnCapture.classList.remove('hidden');
            btnToggle.classList.remove('hidden');

            stopCameraTracks();
            await refreshVideoDevices();

            let constraints = { video: true };
            if (videoDevices.length > 0 && videoDevices[currentDeviceIndex] && videoDevices[currentDeviceIndex].deviceId) {
                constraints = {
                    video: { deviceId: { exact: videoDevices[currentDeviceIndex].deviceId } }
                };
                if (cameraDeviceLabel) {
                    cameraDeviceLabel.textContent = videoDevices[currentDeviceIndex].label || `Kamera ${currentDeviceIndex + 1}`;
                }
            } else {
                constraints = {
                    video: { facingMode: { ideal: currentFacingMode } }
                };
                if (cameraDeviceLabel) {
                    cameraDeviceLabel.textContent = currentFacingMode === 'environment' ? 'Kamera Belakang (Mobile/Webcam)' : 'Kamera Depan (Mobile)';
                }
            }

            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
                video.classList.remove('hidden');
                cameraPlaceholder.classList.add('hidden');

                if (cameraBadge) {
                    cameraBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live';
                    cameraBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200';
                }
                await refreshVideoDevices();
            } catch (err) {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    cameraPlaceholder.classList.add('hidden');
                    if (cameraBadge) {
                        cameraBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live';
                        cameraBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200';
                    }
                } catch (fallbackErr) {
                    video.classList.add('hidden');
                    cameraPlaceholder.classList.remove('hidden');
                    if (cameraBadge) {
                        cameraBadge.innerHTML = 'Nonaktif';
                        cameraBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200';
                    }
                }
            }
        }

        btnToggle.addEventListener('click', async () => {
            if (videoDevices.length > 1) {
                currentDeviceIndex = (currentDeviceIndex + 1) % videoDevices.length;
            } else {
                currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
            }
            await initCamera();
        });

        btnCapture.addEventListener('click', () => {
            if (stream && video.videoWidth) {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                if (photoInput) {
                    photoInput.value = canvas.toDataURL('image/jpeg', 0.85);
                }
                canvas.classList.remove('hidden');
                stopCameraTracks();
                video.classList.add('hidden');
                if (cameraBadge) {
                    cameraBadge.innerHTML = 'Foto Berhasil Diambil';
                    cameraBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-200';
                }
                btnCapture.classList.add('hidden');
                btnToggle.classList.add('hidden');
                btnRetake.classList.remove('hidden');
            }
        });

        btnRetake.addEventListener('click', () => {
            canvas.classList.add('hidden');
            if (photoInput) photoInput.value = '';
            initCamera();
        });

        btnStartCamera.addEventListener('click', () => {
            cameraActivationArea.classList.add('hidden');
            cameraActiveArea.classList.remove('hidden');
            initCamera();
        });

        fallbackFileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                cameraActivationArea.classList.add('hidden');
                cameraActiveArea.classList.remove('hidden');
                const reader = new FileReader();
                reader.onload = (event) => {
                    const base64Data = event.target.result;
                    if (photoInput) photoInput.value = base64Data;
                    
                    const img = new Image();
                    img.onload = () => {
                        const context = canvas.getContext('2d');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        context.drawImage(img, 0, 0);
                        canvas.classList.remove('hidden');
                    };
                    img.src = base64Data;

                    stopCameraTracks();
                    video.classList.add('hidden');
                    cameraPlaceholder.classList.add('hidden');
                    btnCapture.classList.add('hidden');
                    btnToggle.classList.add('hidden');
                    btnRetake.classList.remove('hidden');

                    if (cameraBadge) {
                        cameraBadge.innerHTML = 'Foto Berhasil Diunggah';
                        cameraBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-200';
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        function stopCameraTracks() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            video.srcObject = null;
        }

        // --- KK CAMERA LOGIC ---
        async function refreshVideoDevicesKK() {
            try {
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    videoDevicesKK = devices.filter(d => d.kind === 'videoinput');
                }
            } catch (err) {
                console.warn('Could not enumerate video devices', err);
            }
        }

        async function initCameraKK() {
            canvasKK.classList.add('hidden');
            btnRetakeKK.classList.add('hidden');
            btnCaptureKK.classList.remove('hidden');
            btnToggleKK.classList.remove('hidden');

            stopCameraTracksKK();
            await refreshVideoDevicesKK();

            let constraints = { video: true };
            if (videoDevicesKK.length > 0 && videoDevicesKK[currentDeviceIndexKK] && videoDevicesKK[currentDeviceIndexKK].deviceId) {
                constraints = {
                    video: { deviceId: { exact: videoDevicesKK[currentDeviceIndexKK].deviceId } }
                };
                if (cameraDeviceLabelKK) {
                    cameraDeviceLabelKK.textContent = videoDevicesKK[currentDeviceIndexKK].label || `Kamera ${currentDeviceIndexKK + 1}`;
                }
            } else {
                constraints = {
                    video: { facingMode: { ideal: currentFacingModeKK } }
                };
                if (cameraDeviceLabelKK) {
                    cameraDeviceLabelKK.textContent = currentFacingModeKK === 'environment' ? 'Kamera Belakang (Mobile/Webcam)' : 'Kamera Depan (Mobile)';
                }
            }

            try {
                streamKK = await navigator.mediaDevices.getUserMedia(constraints);
                videoKK.srcObject = streamKK;
                videoKK.classList.remove('hidden');
                cameraPlaceholderKK.classList.add('hidden');

                if (cameraBadgeKK) {
                    cameraBadgeKK.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live';
                    cameraBadgeKK.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200';
                }
                await refreshVideoDevicesKK();
            } catch (err) {
                try {
                    streamKK = await navigator.mediaDevices.getUserMedia({ video: true });
                    videoKK.srcObject = streamKK;
                    videoKK.classList.remove('hidden');
                    cameraPlaceholderKK.classList.add('hidden');
                    if (cameraBadgeKK) {
                        cameraBadgeKK.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live';
                        cameraBadgeKK.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200';
                    }
                } catch (fallbackErr) {
                    videoKK.classList.add('hidden');
                    cameraPlaceholderKK.classList.remove('hidden');
                    if (cameraBadgeKK) {
                        cameraBadgeKK.innerHTML = 'Nonaktif';
                        cameraBadgeKK.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200';
                    }
                }
            }
        }

        btnToggleKK.addEventListener('click', async () => {
            if (videoDevicesKK.length > 1) {
                currentDeviceIndexKK = (currentDeviceIndexKK + 1) % videoDevicesKK.length;
            } else {
                currentFacingModeKK = currentFacingModeKK === 'environment' ? 'user' : 'environment';
            }
            await initCameraKK();
        });

        btnCaptureKK.addEventListener('click', () => {
            if (streamKK && videoKK.videoWidth) {
                const context = canvasKK.getContext('2d');
                canvasKK.width = videoKK.videoWidth;
                canvasKK.height = videoKK.videoHeight;
                context.drawImage(videoKK, 0, 0, canvasKK.width, canvasKK.height);
                if (photoInputKK) {
                    photoInputKK.value = canvasKK.toDataURL('image/jpeg', 0.85);
                }
                canvasKK.classList.remove('hidden');
                stopCameraTracksKK();
                videoKK.classList.add('hidden');
                if (cameraBadgeKK) {
                    cameraBadgeKK.innerHTML = 'Foto Berhasil Diambil';
                    cameraBadgeKK.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-200';
                }
                btnCaptureKK.classList.add('hidden');
                btnToggleKK.classList.add('hidden');
                btnRetakeKK.classList.remove('hidden');
            }
        });

        btnRetakeKK.addEventListener('click', () => {
            canvasKK.classList.add('hidden');
            if (photoInputKK) photoInputKK.value = '';
            initCameraKK();
        });

        btnStartCameraKK.addEventListener('click', () => {
            cameraActivationAreaKK.classList.add('hidden');
            cameraActiveAreaKK.classList.remove('hidden');
            initCameraKK();
        });

        fallbackFileInputKK.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                cameraActivationAreaKK.classList.add('hidden');
                cameraActiveAreaKK.classList.remove('hidden');
                const reader = new FileReader();
                reader.onload = (event) => {
                    const base64Data = event.target.result;
                    if (photoInputKK) photoInputKK.value = base64Data;
                    
                    const img = new Image();
                    img.onload = () => {
                        const context = canvasKK.getContext('2d');
                        canvasKK.width = img.width;
                        canvasKK.height = img.height;
                        context.drawImage(img, 0, 0);
                        canvasKK.classList.remove('hidden');
                    };
                    img.src = base64Data;

                    stopCameraTracksKK();
                    videoKK.classList.add('hidden');
                    cameraPlaceholderKK.classList.add('hidden');
                    btnCaptureKK.classList.add('hidden');
                    btnToggleKK.classList.add('hidden');
                    btnRetakeKK.classList.remove('hidden');

                    if (cameraBadgeKK) {
                        cameraBadgeKK.innerHTML = 'Foto Berhasil Diunggah';
                        cameraBadgeKK.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-200';
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        function stopCameraTracksKK() {
            if (streamKK) {
                streamKK.getTracks().forEach(track => track.stop());
                streamKK = null;
            }
            videoKK.srcObject = null;
        }
    });
</script>
@endsection
