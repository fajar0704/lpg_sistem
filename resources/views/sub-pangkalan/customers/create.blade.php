@extends('layouts.sub-pangkalan')

@section('title', 'Tambah Pelanggan Baru - Sistem LPG')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div>
        <a href="{{ route('sub-pangkalan.customers.index') }}" class="inline-flex items-center gap-1 text-slate-500 hover:text-blue-600 transition font-bold text-xs uppercase tracking-wider mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar
        </a>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Tambah Pelanggan Baru</h2>
        <p class="text-slate-500 text-sm mt-1">Daftarkan pelanggan baru dengan data lengkap sesuai KTP beserta foto dokumentasi.</p>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <form action="{{ route('sub-pangkalan.customers.store') }}" method="POST" id="customer-form">
            @csrf

            <!-- SECTION 1: Data Identitas KTP -->
            <div class="p-6 sm:p-8 space-y-6 border-b border-slate-100">
                <div class="flex items-center gap-2 pb-1.5 border-b border-slate-50">
                    <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Informasi Identitas Sesuai KTP</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- NIK KTP -->
                    <div>
                        <label for="ktp" class="block text-slate-700 text-xs font-semibold mb-2">Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span></label>
                        <input type="text" name="ktp" id="ktp" value="{{ old('ktp') }}" placeholder="Harus 16 digit angka" required pattern="\d{16}" maxlength="16" autocomplete="off"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-mono font-bold @error('ktp') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('ktp')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-slate-700 text-xs font-semibold mb-2">Nama Lengkap Sesuai KTP <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-semibold @error('name') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('name')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="md:col-span-2">
                        <label for="phone" class="block text-slate-700 text-xs font-semibold mb-2">Nomor Telepon (WhatsApp) <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('phone') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('phone')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Alamat Domisili -->
                <div>
                    <label for="address" class="block text-slate-700 text-xs font-semibold mb-2">Alamat Lengkap</label>
                    <textarea name="address" id="address" rows="3" placeholder="Masukkan alamat lengkap domisili pelanggan..."
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('address') border-rose-500 focus:ring-rose-500/20 @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- SECTION 2: Kamera / Dokumentasi Foto KTP -->
            <div class="px-4 py-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-2 pb-1.5 border-b border-slate-50">
                    <div class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Dokumentasi Foto KTP / Pelanggan</h3>
                </div>

                <div class="flex flex-col items-center gap-4 p-4 sm:p-6 bg-slate-50 rounded-2xl border border-slate-200 w-full max-w-md mx-auto">
                    <!-- Tombol Aktifkan Kamera (Default Visible) -->
                    <div id="camera-activation-area" class="w-full flex flex-col items-center justify-center py-10 bg-white border border-dashed border-slate-300 rounded-2xl text-center space-y-3">
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-slate-500 font-semibold px-4">Gunakan kamera untuk mengambil foto KTP secara langsung.</p>
                        <div class="flex flex-wrap items-center gap-2.5 justify-center w-full px-2">
                            <button type="button" id="btn-start-camera" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 transition duration-200 shadow-md shadow-blue-500/10 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Aktifkan Kamera</span>
                            </button>
                            <span class="text-xs text-slate-400">atau</span>
                            <label for="fallback-file-input" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs cursor-pointer transition">
                                Pilih Foto
                            </label>
                        </div>
                    </div>

                    <!-- Camera Active Area (Hidden by Default) -->
                    <div id="camera-active-area" class="hidden w-full space-y-3">
                        <!-- Video & Canvas Viewport Container -->
                        <div class="bg-slate-950 aspect-video w-full rounded-2xl relative overflow-hidden flex flex-col items-center justify-center border border-slate-800 shadow-inner group">
                            <!-- Placeholder -->
                            <div id="camera-placeholder" class="text-center space-y-2 p-6 text-slate-500 transition duration-300">
                                <svg class="w-12 h-12 mx-auto text-slate-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                                <p class="text-xs font-bold text-slate-400">Menghubungkan Kamera...</p>
                            </div>

                            <!-- Video stream -->
                            <video id="camera-video" autoplay playsinline class="w-full h-full object-cover hidden"></video>

                            <!-- Canvas Captured Frame Preview -->
                            <canvas id="camera-canvas" class="w-full h-full object-cover hidden absolute inset-0 z-10 border-4 border-emerald-500 rounded-2xl"></canvas>

                            <!-- Floating Small Icon Controls -->
                            <div id="camera-controls-overlay" class="absolute z-20 flex items-center justify-center gap-3" style="bottom: 16px; left: 50%; transform: translateX(-50%); width: max-content;">
                                <!-- Beralih Kamera (Small Icon Button) -->
                                <button type="button" id="switch-camera-btn" title="Beralih Kamera (Laptop/HP)" class="w-10 h-10 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 active:scale-95 shadow-lg border border-white/20 backdrop-blur-md cursor-pointer group">
                                    <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </button>

                                <!-- Ambil Foto (Transparent Shutter Button) -->
                                <button type="button" id="snap-btn" title="Ambil Foto" class="w-12 h-12 rounded-full bg-transparent border-2 border-white text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 active:scale-95 cursor-pointer shadow-lg hover:bg-white/15">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Ulangi Foto (Berada di luar/dibawah bingkai kamera) -->
                        <div class="flex justify-center mt-3">
                            <button type="button" id="retake-btn" class="hidden px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white flex items-center gap-1.5 transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-md font-bold text-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"></path>
                                </svg>
                                <span>Ulangi Foto</span>
                            </button>
                        </div>

                        <!-- Device Label & File Fallback -->
                        <div class="flex items-center justify-between text-xs text-slate-500 w-full px-1 pt-1">
                            <span id="camera-device-label" class="font-medium">Kamera Laptop / HP</span>
                            <label for="fallback-file-input" class="text-blue-600 hover:underline cursor-pointer font-semibold">
                                Pilih Foto File
                            </label>
                            <input type="file" id="fallback-file-input" accept="image/*" capture="environment" class="hidden">
                        </div>
                    </div>

                    <!-- Hidden Input for Photo Base64 -->
                    <input type="hidden" name="photo" id="photo_input">
                    @error('photo')<p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- SECTION 3: Kamera / Dokumentasi Foto Kartu Keluarga (KK) -->
            <div id="kk-camera-section" class="px-4 py-6 sm:p-8 space-y-6 border-t border-slate-100">
                <div class="flex items-center justify-between pb-1.5 border-b border-slate-50">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Dokumentasi Foto KK <span class="text-rose-500">*</span></h3>
                    </div>
                    <span id="kk-camera-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200">
                        Nonaktif
                    </span>
                </div>

                <div class="flex flex-col items-center gap-4 p-4 sm:p-6 bg-slate-50 rounded-2xl border border-slate-200 w-full max-w-md mx-auto">
                    <!-- Photo Required Validation Error Banner -->
                    <div id="kk-photo-validation-error" class="hidden w-full max-w-md p-3 bg-rose-50 border border-rose-200 text-rose-600 font-bold text-xs rounded-xl text-center shadow-xs">
                        ⚠️ Dokumentasi Foto KK wajib diambil sebelum menyimpan data.
                    </div>

                    <!-- Tombol Aktifkan Kamera (Default Visible) -->
                    <div id="kk-camera-activation-area" class="w-full flex flex-col items-center justify-center py-10 bg-white border border-dashed border-slate-300 rounded-2xl text-center space-y-3">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-slate-500 font-semibold px-4">Gunakan kamera untuk mengambil foto KK secara langsung.</p>
                        <div class="flex flex-wrap items-center gap-2.5 justify-center w-full px-2">
                            <button type="button" id="kk-btn-start-camera" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 transition duration-200 shadow-md shadow-blue-500/10 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Aktifkan Kamera KK</span>
                            </button>
                            <span class="text-xs text-slate-400">atau</span>
                            <label for="kk-fallback-file-input" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs cursor-pointer transition">
                                Pilih Foto
                            </label>
                        </div>
                    </div>

                    <!-- Camera Active Area (Hidden by Default) -->
                    <div id="kk-camera-active-area" class="hidden w-full space-y-3">
                        <!-- Video & Canvas Viewport Container -->
                        <div class="bg-slate-950 aspect-video w-full rounded-2xl relative overflow-hidden flex flex-col items-center justify-center border border-slate-800 shadow-inner group">
                            <!-- Placeholder -->
                            <div id="kk-camera-placeholder" class="text-center space-y-2 p-6 text-slate-500 transition duration-300">
                                <svg class="w-12 h-12 mx-auto text-slate-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                                <p class="text-xs font-bold text-slate-400">Menghubungkan Kamera...</p>
                            </div>

                            <!-- Video stream -->
                            <video id="kk-camera-video" autoplay playsinline class="w-full h-full object-cover hidden"></video>

                            <!-- Canvas Captured Frame Preview -->
                            <canvas id="kk-camera-canvas" class="w-full h-full object-cover hidden absolute inset-0 z-10 border-4 border-emerald-500 rounded-2xl"></canvas>

                            <!-- Floating Small Icon Controls -->
                            <div id="kk-camera-controls-overlay" class="absolute z-20 flex items-center justify-center gap-3" style="bottom: 16px; left: 50%; transform: translateX(-50%); width: max-content;">
                                <!-- Beralih Kamera (Small Icon Button) -->
                                <button type="button" id="kk-switch-camera-btn" title="Beralih Kamera (Laptop/HP)" class="w-10 h-10 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 active:scale-95 shadow-lg border border-white/20 backdrop-blur-md cursor-pointer group">
                                    <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </button>

                                <!-- Ambil Foto (Transparent Shutter Button) -->
                                <button type="button" id="kk-snap-btn" title="Ambil Foto" class="w-12 h-12 rounded-full bg-transparent border-2 border-white text-white flex items-center justify-center transition-all duration-200 transform hover:scale-110 active:scale-95 cursor-pointer shadow-lg hover:bg-white/15">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Ulangi Foto (Berada di luar/dibawah bingkai kamera) -->
                        <div class="flex justify-center mt-3">
                            <button type="button" id="kk-retake-btn" class="hidden px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white flex items-center gap-1.5 transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-md font-bold text-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"></path>
                                </svg>
                                <span>Ulangi Foto</span>
                            </button>
                        </div>

                        <!-- Device Label & File Fallback -->
                        <div class="flex items-center justify-between text-xs text-slate-500 w-full px-1 pt-1">
                            <span id="kk-camera-device-label" class="font-medium">Kamera Laptop / HP</span>
                            <label for="kk-fallback-file-input" class="text-blue-600 hover:underline cursor-pointer font-semibold">
                                Pilih Foto File
                            </label>
                            <input type="file" id="kk-fallback-file-input" accept="image/*" capture="environment" class="hidden">
                        </div>
                    </div>

                    <!-- Hidden Input for Photo Base64 -->
                    <input type="hidden" name="kk_photo" id="kk_photo_input">
                    @error('kk_photo')<p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 sm:p-8 bg-slate-50/50 flex items-center justify-end gap-3">
                <a href="{{ route('sub-pangkalan.customers.index') }}" class="bg-white hover:bg-slate-100 text-slate-700 font-bold px-5 py-3 rounded-xl transition duration-200 text-sm border border-slate-200">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-indigo-600/20 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer text-sm">
                    Simpan Pelanggan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- KTP Camera ---
        const video = document.getElementById('camera-video');
        const canvas = document.getElementById('camera-canvas');
        const cameraPlaceholder = document.getElementById('camera-placeholder');
        const snapBtn = document.getElementById('snap-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const switchCameraBtn = document.getElementById('switch-camera-btn');
        const photoInput = document.getElementById('photo_input');
        const cameraDeviceLabel = document.getElementById('camera-device-label');
        const fallbackFileInput = document.getElementById('fallback-file-input');

        const btnStartCamera = document.getElementById('btn-start-camera');
        const cameraActivationArea = document.getElementById('camera-activation-area');
        const cameraActiveArea = document.getElementById('camera-active-area');
        const cameraBadge = document.getElementById('camera-badge');
        
        let stream = null;
        let videoDevices = [];
        let currentDeviceIndex = 0;
        let currentFacingMode = 'environment';

        async function refreshVideoDevices() {
            try {
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    videoDevices = devices.filter(d => d.kind === 'videoinput');
                }
            } catch (err) {
                console.warn('Could not enumerate devices', err);
            }
        }

        async function startCamera() {
            canvas.classList.add('hidden');
            retakeBtn.classList.add('hidden');
            snapBtn.classList.remove('hidden');
            switchCameraBtn.classList.remove('hidden');

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
                    cameraBadge.textContent = 'Aktif';
                    cameraBadge.classList.replace('bg-rose-50', 'bg-emerald-50');
                    cameraBadge.classList.replace('text-rose-600', 'text-emerald-600');
                    cameraBadge.classList.replace('border-rose-200', 'border-emerald-200');
                }

                await refreshVideoDevices();
                if (videoDevices.length > 0 && videoDevices[currentDeviceIndex] && cameraDeviceLabel) {
                    cameraDeviceLabel.textContent = videoDevices[currentDeviceIndex].label || cameraDeviceLabel.textContent;
                }
            } catch (err) {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    cameraPlaceholder.classList.add('hidden');
                    if (cameraBadge) {
                        cameraBadge.textContent = 'Aktif';
                        cameraBadge.classList.replace('bg-rose-50', 'bg-emerald-50');
                        cameraBadge.classList.replace('text-rose-600', 'text-emerald-600');
                        cameraBadge.classList.replace('border-rose-200', 'border-emerald-200');
                    }
                } catch (fallbackErr) {
                    console.error("Camera access error:", fallbackErr);
                    video.classList.add('hidden');
                    cameraPlaceholder.classList.remove('hidden');
                    cameraPlaceholder.innerHTML = `
                        <p class="text-xs font-bold text-rose-500">Kamera Tidak Aktif / Akses Ditolak</p>
                        <p class="text-[10px] text-slate-400">Gunakan opsi "Pilih Foto File" di bawah.</p>
                    `;
                }
            }
        }

        function stopCameraTracks() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            video.srcObject = null;
            if (cameraBadge) {
                cameraBadge.textContent = 'Nonaktif';
                cameraBadge.classList.replace('bg-emerald-50', 'bg-rose-50');
                cameraBadge.classList.replace('text-emerald-600', 'text-rose-600');
                cameraBadge.classList.replace('border-emerald-200', 'border-rose-200');
            }
        }

        switchCameraBtn.addEventListener('click', async () => {
            if (videoDevices.length > 1) {
                currentDeviceIndex = (currentDeviceIndex + 1) % videoDevices.length;
            } else {
                currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
            }
            await startCamera();
        });

        snapBtn.addEventListener('click', () => {
            if (stream && video.videoWidth) {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                canvas.classList.remove('hidden');
                video.classList.add('hidden');
                
                const base64Data = canvas.toDataURL('image/jpeg', 0.85);
                photoInput.value = base64Data;
                
                snapBtn.classList.add('hidden');
                switchCameraBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                
                stopCameraTracks();
            }
        });

        retakeBtn.addEventListener('click', () => {
            canvas.classList.add('hidden');
            photoInput.value = '';
            retakeBtn.classList.add('hidden');
            startCamera();
        });

        // Click to Activate Camera
        btnStartCamera.addEventListener('click', () => {
            cameraActivationArea.classList.add('hidden');
            cameraActiveArea.classList.remove('hidden');
            startCamera();
        });

        fallbackFileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                cameraActivationArea.classList.add('hidden');
                cameraActiveArea.classList.remove('hidden');

                const reader = new FileReader();
                reader.onload = (event) => {
                    const base64Data = event.target.result;
                    photoInput.value = base64Data;
                    
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
                    
                    snapBtn.classList.add('hidden');
                    switchCameraBtn.classList.add('hidden');
                    retakeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // --- KK Camera ---
        const videoKK = document.getElementById('kk-camera-video');
        const canvasKK = document.getElementById('kk-camera-canvas');
        const cameraPlaceholderKK = document.getElementById('kk-camera-placeholder');
        const snapBtnKK = document.getElementById('kk-snap-btn');
        const retakeBtnKK = document.getElementById('kk-retake-btn');
        const switchCameraBtnKK = document.getElementById('kk-switch-camera-btn');
        const photoInputKK = document.getElementById('kk_photo_input');
        const cameraDeviceLabelKK = document.getElementById('kk-camera-device-label');
        const fallbackFileInputKK = document.getElementById('kk-fallback-file-input');

        const btnStartCameraKK = document.getElementById('kk-btn-start-camera');
        const cameraActivationAreaKK = document.getElementById('kk-camera-activation-area');
        const cameraActiveAreaKK = document.getElementById('kk-camera-active-area');
        const kkCameraBadge = document.getElementById('kk-camera-badge');
        const kkCameraSection = document.getElementById('kk-camera-section');
        const kkPhotoValidationError = document.getElementById('kk-photo-validation-error');
        const customerForm = document.getElementById('customer-form');

        let streamKK = null;
        let videoDevicesKK = [];
        let currentDeviceIndexKK = 0;
        let currentFacingModeKK = 'environment';

        async function refreshVideoDevicesKK() {
            try {
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    videoDevicesKK = devices.filter(d => d.kind === 'videoinput');
                }
            } catch (err) {
                console.warn('Could not enumerate devices KK', err);
            }
        }

        async function startCameraKK() {
            canvasKK.classList.add('hidden');
            retakeBtnKK.classList.add('hidden');
            snapBtnKK.classList.remove('hidden');
            switchCameraBtnKK.classList.remove('hidden');

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
                if (kkCameraBadge) {
                    kkCameraBadge.textContent = 'Aktif';
                    kkCameraBadge.classList.replace('bg-rose-50', 'bg-emerald-50');
                    kkCameraBadge.classList.replace('text-rose-600', 'text-emerald-600');
                    kkCameraBadge.classList.replace('border-rose-200', 'border-emerald-200');
                }

                await refreshVideoDevicesKK();
                if (videoDevicesKK.length > 0 && videoDevicesKK[currentDeviceIndexKK] && cameraDeviceLabelKK) {
                    cameraDeviceLabelKK.textContent = videoDevicesKK[currentDeviceIndexKK].label || cameraDeviceLabelKK.textContent;
                }
            } catch (err) {
                try {
                    streamKK = await navigator.mediaDevices.getUserMedia({ video: true });
                    videoKK.srcObject = streamKK;
                    videoKK.classList.remove('hidden');
                    cameraPlaceholderKK.classList.add('hidden');
                    if (kkCameraBadge) {
                        kkCameraBadge.textContent = 'Aktif';
                        kkCameraBadge.classList.replace('bg-rose-50', 'bg-emerald-50');
                        kkCameraBadge.classList.replace('text-rose-600', 'text-emerald-600');
                        kkCameraBadge.classList.replace('border-rose-200', 'border-emerald-200');
                    }
                } catch (fallbackErr) {
                    console.error("Camera KK access error:", fallbackErr);
                    videoKK.classList.add('hidden');
                    cameraPlaceholderKK.classList.remove('hidden');
                    cameraPlaceholderKK.innerHTML = `
                        <p class="text-xs font-bold text-rose-500">Kamera Tidak Aktif / Akses Ditolak</p>
                        <p class="text-[10px] text-slate-400">Gunakan opsi "Pilih Foto File" di bawah.</p>
                    `;
                }
            }
        }

        function stopCameraTracksKK() {
            if (streamKK) {
                streamKK.getTracks().forEach(track => track.stop());
                streamKK = null;
            }
            videoKK.srcObject = null;
            if (kkCameraBadge) {
                kkCameraBadge.textContent = 'Nonaktif';
                kkCameraBadge.classList.replace('bg-emerald-50', 'bg-rose-50');
                kkCameraBadge.classList.replace('text-emerald-600', 'text-rose-600');
                kkCameraBadge.classList.replace('border-emerald-200', 'border-rose-200');
            }
        }

        switchCameraBtnKK.addEventListener('click', async () => {
            if (videoDevicesKK.length > 1) {
                currentDeviceIndexKK = (currentDeviceIndexKK + 1) % videoDevicesKK.length;
            } else {
                currentFacingModeKK = currentFacingModeKK === 'environment' ? 'user' : 'environment';
            }
            await startCameraKK();
        });

        snapBtnKK.addEventListener('click', () => {
            if (streamKK && videoKK.videoWidth) {
                const context = canvasKK.getContext('2d');
                canvasKK.width = videoKK.videoWidth;
                canvasKK.height = videoKK.videoHeight;
                context.drawImage(videoKK, 0, 0, canvasKK.width, canvasKK.height);
                
                canvasKK.classList.remove('hidden');
                videoKK.classList.add('hidden');
                
                const base64Data = canvasKK.toDataURL('image/jpeg', 0.85);
                photoInputKK.value = base64Data;
                
                snapBtnKK.classList.add('hidden');
                switchCameraBtnKK.classList.add('hidden');
                retakeBtnKK.classList.remove('hidden');
                
                stopCameraTracksKK();
            }
        });

        retakeBtnKK.addEventListener('click', () => {
            canvasKK.classList.add('hidden');
            photoInputKK.value = '';
            retakeBtnKK.classList.add('hidden');
            startCameraKK();
        });

        btnStartCameraKK.addEventListener('click', () => {
            cameraActivationAreaKK.classList.add('hidden');
            cameraActiveAreaKK.classList.remove('hidden');
            startCameraKK();
        });

        fallbackFileInputKK.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                cameraActivationAreaKK.classList.add('hidden');
                cameraActiveAreaKK.classList.remove('hidden');

                const reader = new FileReader();
                reader.onload = (event) => {
                    const base64Data = event.target.result;
                    photoInputKK.value = base64Data;
                    
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
                    
                    snapBtnKK.classList.add('hidden');
                    switchCameraBtnKK.classList.add('hidden');
                    retakeBtnKK.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // --- Form Submit Validation ---
        customerForm.addEventListener('submit', (e) => {
            // Validate photo KTP (wajib)
            if (!photoInput.value) {
                e.preventDefault();
                alert('Dokumentasi Foto KTP wajib diambil sebelum menyimpan data.');
                return;
            }
            // Validate photo KK (wajib)
            if (!photoInputKK.value) {
                e.preventDefault();
                kkPhotoValidationError.classList.remove('hidden');
                kkCameraSection.scrollIntoView({ behavior: 'smooth' });
                return;
            }
            kkPhotoValidationError.classList.add('hidden');
        });
    });
</script>
@endsection
