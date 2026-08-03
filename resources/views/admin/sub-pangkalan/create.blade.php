@extends('layouts.admin')

@section('title', 'Tambah Sub Pangkalan - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div>
        <a href="{{ route('admin.sub-pangkalan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Data Sub Pangkalan
        </a>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span>Tambah Sub Pangkalan Baru</span>
        </h2>
        <p class="text-slate-500 text-sm mt-1">Formulir pendaftaran sub pangkalan (pengecer) baru beserta pembuatan akun login sistem.</p>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 max-w-4xl overflow-hidden">
        <form id="create-sub-pangkalan-form" action="{{ route('admin.sub-pangkalan.store') }}" method="POST" class="divide-y divide-slate-100">
            @csrf

            <!-- SECTION 1: Informasi Pangkalan -->
            <div class="p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-2 pb-1.5">
                    <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Informasi Sub Pangkalan</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Pangkalan -->
                    <div>
                        <label for="name" class="block text-slate-700 text-xs font-semibold mb-2">Nama Sub Pangkalan <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Sub Pangkalan Jaya Mandiri" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('name') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('name')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nomor Induk Berusaha (NIB) -->
                    <div>
                        <label class="block text-slate-700 text-xs font-semibold mb-2">Nomor Induk Berusaha (NIB) <span class="text-rose-500">*</span></label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="Contoh: 1234567890123" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('code') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('code')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label for="phone" class="block text-slate-700 text-xs font-semibold mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Contoh: 0812XXXXXXXX"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('phone') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('phone')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Alamat Pangkalan -->
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-slate-700 text-xs font-semibold mb-2">Alamat Pangkalan</label>
                        <textarea name="address" id="address" rows="2" placeholder="Masukkan alamat lengkap pangkalan..."
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('address') border-rose-500 focus:ring-rose-500/20 @enderror">{{ old('address') }}</textarea>
                        @error('address')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Data Pemilik (Sesuai KTP) -->
            <div class="p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-2 pb-1.5">
                    <div class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Identitas Pemilik (Sesuai KTP)</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- NIK KTP -->
                    <div>
                        <label for="ktp" class="block text-slate-700 text-xs font-semibold mb-2">NIK KTP <span class="text-rose-500">*</span></label>
                        <input type="text" name="ktp" id="ktp" value="{{ old('ktp') }}" placeholder="16 Digit NIK KTP" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('ktp') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('ktp')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_ktp" class="block text-slate-700 text-xs font-semibold mb-2">Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ktp" id="nama_ktp" value="{{ old('nama_ktp') }}" placeholder="Nama Lengkap Pemilik" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('nama_ktp') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('nama_ktp')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-slate-700 text-xs font-semibold mb-2">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota Lahir" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('tempat_lahir') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('tempat_lahir')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-slate-700 text-xs font-semibold mb-2">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('tanggal_lahir') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('tanggal_lahir')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-slate-700 text-xs font-semibold mb-2">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 bg-white font-medium @error('jenis_kelamin') border-rose-500 focus:ring-rose-500/20 @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Alamat KTP -->
                    <div class="sm:col-span-2">
                        <label for="alamat_ktp" class="block text-slate-700 text-xs font-semibold mb-2">Alamat Lengkap KTP <span class="text-rose-500">*</span></label>
                        <textarea name="alamat_ktp" id="alamat_ktp" rows="2" placeholder="Masukkan alamat lengkap sesuai KTP..." required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('alamat_ktp') border-rose-500 focus:ring-rose-500/20 @enderror">{{ old('alamat_ktp') }}</textarea>
                        @error('alamat_ktp')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Kredensial Login Akun -->
            <div class="p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-2 pb-1.5">
                    <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Kredensial Akun Login</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-slate-700 text-xs font-semibold mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Contoh: pangkalan@lpg.com" required autocomplete="off"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('email') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('email')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-slate-700 text-xs font-semibold mb-2">Kata Sandi <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" id="password" value="" placeholder="Minimal 6 karakter" required minlength="6" autocomplete="new-password"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('password') border-rose-500 focus:ring-rose-500/20 @enderror">
                        @error('password')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <!-- Role (Read Only) -->
                    <div>
                        <label for="role" class="block text-slate-700 text-xs font-semibold mb-2">Hak Akses</label>
                        <select name="role" id="role" readonly
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-500 font-medium cursor-not-allowed">
                            <option value="sub_pangkalan" selected>Sub Pangkalan (Pengecer)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Dokumentasi Foto KTP / Pemilik -->
            <div id="camera-section" class="p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-1.5 border-b border-slate-50">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Dokumentasi Foto KTP / Pemilik <span class="text-rose-500">*</span></h3>
                    </div>
                    <span id="camera-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200">
                        Nonaktif
                    </span>
                </div>

                <div class="flex flex-col items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-200 max-w-md mx-auto">
                    <!-- Photo Required Validation Error Banner -->
                    <div id="photo-validation-error" class="{{ $errors->has('photo') ? '' : 'hidden' }} w-full max-w-md p-3 bg-rose-50 border border-rose-200 text-rose-600 font-bold text-xs rounded-xl text-center shadow-xs">
                        ⚠️ Dokumentasi Foto KTP / Pemilik wajib diambil sebelum menyimpan data.
                    </div>

                    <!-- Tombol Aktifkan Kamera (Default Visible) -->
                    <div id="camera-activation-area" class="w-full flex flex-col items-center justify-center py-10 bg-white border border-dashed border-slate-300 rounded-2xl text-center space-y-3">
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-slate-500 font-semibold px-4">Gunakan kamera untuk mengambil foto KTP secara langsung.</p>
                        <div class="flex items-center gap-3 justify-center">
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
                        <div class="bg-slate-950 w-full rounded-2xl relative overflow-hidden flex flex-col items-center justify-center border border-slate-800 shadow-inner group" style="aspect-ratio: 85.6/53.98;">
                            <!-- Placeholder -->
                            <div id="camera-placeholder" class="text-center space-y-2 p-6 text-slate-500 transition duration-300">
                                <svg class="w-12 h-12 mx-auto text-slate-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                                <p class="text-xs font-bold text-slate-400">Menghubungkan Kamera...</p>
                            </div>

                            <!-- Video stream -->
                            <video id="camera-video" autoplay class="w-full h-full object-cover hidden"></video>

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
            </div>
        </form>
    </div>

    <!-- SECTION 5: Dokumentasi Foto Kartu Keluarga (KK) -->
    <div id="kk-camera-section" class="bg-white rounded-2xl shadow-sm border border-slate-100 max-w-4xl overflow-hidden relative mt-6 space-y-6 p-6 sm:p-8">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

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

        <div class="flex flex-col items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-200 max-w-md mx-auto">
            <!-- Photo Required Validation Error Banner -->
            <div id="kk-photo-validation-error" class="{{ $errors->has('kk_photo') ? '' : 'hidden' }} w-full max-w-md p-3 bg-rose-50 border border-rose-200 text-rose-600 font-bold text-xs rounded-xl text-center shadow-xs">
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
                <div class="flex items-center gap-3 justify-center">
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
                <div class="bg-slate-950 w-full rounded-2xl relative overflow-hidden flex flex-col items-center justify-center border border-slate-800 shadow-inner group" style="aspect-ratio: 85.6/53.98;">
                    <!-- Placeholder -->
                    <div id="kk-camera-placeholder" class="text-center space-y-2 p-6 text-slate-500 transition duration-300">
                        <svg class="w-12 h-12 mx-auto text-slate-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        </svg>
                        <p class="text-xs font-bold text-slate-400">Menghubungkan Kamera...</p>
                    </div>

                    <!-- Video stream -->
                    <video id="kk-camera-video" autoplay class="w-full h-full object-cover hidden"></video>

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
            <input type="hidden" name="kk_photo" id="kk_photo_input" form="create-sub-pangkalan-form">
            @error('kk_photo')<p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <!-- Action Form Buttons -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 max-w-4xl overflow-hidden mt-6">
        <div class="p-6 sm:p-8 bg-slate-50/50 flex items-center justify-end gap-3">
            <a href="{{ route('admin.sub-pangkalan.index') }}" class="bg-white hover:bg-slate-100 text-slate-700 font-bold px-5 py-3 rounded-xl transition duration-200 text-sm border border-slate-200">
                Batal
            </a>
            <button type="submit" form="create-sub-pangkalan-form" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-indigo-600/20 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer text-sm">
                Simpan Data Sub Pangkalan
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- KTP CAMERA LOGIC ---
        const video = document.getElementById('camera-video');
        const canvas = document.getElementById('camera-canvas');
        const cameraPlaceholder = document.getElementById('camera-placeholder');
        const snapBtn = document.getElementById('snap-btn');
        const startCameraBtn = document.getElementById('btn-start-camera');
        const retakeBtn = document.getElementById('retake-btn');
        const switchCameraBtn = document.getElementById('switch-camera-btn');
        const photoInput = document.getElementById('photo_input');
        const fallbackFileInput = document.getElementById('fallback-file-input');

        const cameraActivationArea = document.getElementById('camera-activation-area');
        const cameraActiveArea = document.getElementById('camera-active-area');
        const cameraBadge = document.getElementById('camera-badge');
        const cameraDeviceLabel = document.getElementById('camera-device-label');

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
                console.warn('Could not enumerate video devices', err);
            }
        }

        function startCamera() {
            if (stream) {
                stopCameraTracks();
            }

            canvas.classList.add('hidden');
            retakeBtn.classList.add('hidden');
            snapBtn.classList.remove('hidden');
            switchCameraBtn.classList.remove('hidden');
            video.classList.remove('hidden');
            cameraPlaceholder.classList.remove('hidden');

            refreshVideoDevices().then(() => {
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

                navigator.mediaDevices.getUserMedia(constraints)
                    .then(mediaStream => { 
                        stream = mediaStream;
                        video.srcObject = stream; 
                        video.play().then(() => {
                            cameraPlaceholder.classList.add('hidden');
                        }).catch(err => {
                            console.error("Error playing video stream: ", err);
                            cameraPlaceholder.classList.add('hidden');
                        });
                    })
                    .catch(err => { 
                        navigator.mediaDevices.getUserMedia({ video: true })
                            .then(mediaStream => {
                                stream = mediaStream;
                                video.srcObject = stream; 
                                video.play().then(() => {
                                    cameraPlaceholder.classList.add('hidden');
                                }).catch(err => {
                                    console.error("Error playing video stream: ", err);
                                    cameraPlaceholder.classList.add('hidden');
                                });
                            })
                            .catch(fallbackErr => {
                                alert("Gagal mengakses kamera. Pastikan izin kamera telah diberikan.");
                                console.error("Error accessing camera: ", fallbackErr); 
                            });
                    });
            });
        }

        function stopCameraTracks() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            video.srcObject = null;
        }

        startCameraBtn.addEventListener('click', () => {
            cameraActivationArea.classList.add('hidden');
            cameraActiveArea.classList.remove('hidden');
            if (cameraBadge) {
                cameraBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>Live`;
                cameraBadge.className = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200";
            }
            startCamera();
        });

        switchCameraBtn.addEventListener('click', () => {
            if (videoDevices.length > 1) {
                currentDeviceIndex = (currentDeviceIndex + 1) % videoDevices.length;
            } else {
                currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
            }
            startCamera();
        });

        snapBtn.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.classList.remove('hidden');
            video.classList.add('hidden');
            photoInput.value = canvas.toDataURL('image/jpeg', 0.85);
            snapBtn.classList.add('hidden');
            switchCameraBtn.classList.add('hidden');
            retakeBtn.classList.remove('hidden');
            
            if (cameraBadge) {
                cameraBadge.innerHTML = 'Foto Terekam';
                cameraBadge.className = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200";
            }

            const photoErrBox = document.getElementById('photo-validation-error');
            if (photoErrBox) {
                photoErrBox.classList.add('hidden');
            }

            stopCameraTracks();
        });

        retakeBtn.addEventListener('click', () => {
            canvas.classList.add('hidden');
            video.classList.remove('hidden');
            photoInput.value = '';
            retakeBtn.classList.add('hidden');
            if (cameraBadge) {
                cameraBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>Live`;
                cameraBadge.className = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200";
            }
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

                    if (cameraBadge) {
                        cameraBadge.innerHTML = 'Foto Berhasil Diunggah';
                        cameraBadge.className = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200";
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // --- KK CAMERA LOGIC ---
        const videoKK = document.getElementById('kk-camera-video');
        const canvasKK = document.getElementById('kk-camera-canvas');
        const cameraPlaceholderKK = document.getElementById('kk-camera-placeholder');
        const snapBtnKK = document.getElementById('kk-snap-btn');
        const startCameraBtnKK = document.getElementById('kk-btn-start-camera');
        const retakeBtnKK = document.getElementById('kk-retake-btn');
        const switchCameraBtnKK = document.getElementById('kk-switch-camera-btn');
        const photoInputKK = document.getElementById('kk_photo_input');
        const fallbackFileInputKK = document.getElementById('kk-fallback-file-input');

        const cameraActivationAreaKK = document.getElementById('kk-camera-activation-area');
        const cameraActiveAreaKK = document.getElementById('kk-camera-active-area');
        const cameraBadgeKK = document.getElementById('kk-camera-badge');
        const cameraDeviceLabelKK = document.getElementById('kk-camera-device-label');

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
                console.warn('Could not enumerate video devices for KK', err);
            }
        }

        function startCameraKK() {
            if (streamKK) {
                stopCameraTracksKK();
            }

            canvasKK.classList.add('hidden');
            retakeBtnKK.classList.add('hidden');
            snapBtnKK.classList.remove('hidden');
            switchCameraBtnKK.classList.remove('hidden');
            videoKK.classList.remove('hidden');
            cameraPlaceholderKK.classList.remove('hidden');

            refreshVideoDevicesKK().then(() => {
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

                navigator.mediaDevices.getUserMedia(constraints)
                    .then(mediaStream => { 
                        streamKK = mediaStream;
                        videoKK.srcObject = streamKK; 
                        videoKK.play().then(() => {
                            cameraPlaceholderKK.classList.add('hidden');
                        }).catch(err => {
                            console.error("Error playing KK video stream: ", err);
                            cameraPlaceholderKK.classList.add('hidden');
                        });
                    })
                    .catch(err => { 
                        navigator.mediaDevices.getUserMedia({ video: true })
                            .then(mediaStream => {
                                streamKK = mediaStream;
                                videoKK.srcObject = streamKK; 
                                videoKK.play().then(() => {
                                    cameraPlaceholderKK.classList.add('hidden');
                                }).catch(err => {
                                    console.error("Error playing KK video stream: ", err);
                                    cameraPlaceholderKK.classList.add('hidden');
                                });
                            })
                            .catch(fallbackErr => {
                                alert("Gagal mengakses kamera KK. Pastikan izin kamera telah diberikan.");
                                console.error("Error accessing KK camera: ", fallbackErr); 
                            });
                    });
            });
        }

        function stopCameraTracksKK() {
            if (streamKK) {
                streamKK.getTracks().forEach(track => track.stop());
                streamKK = null;
            }
            videoKK.srcObject = null;
        }

        startCameraBtnKK.addEventListener('click', () => {
            cameraActivationAreaKK.classList.add('hidden');
            cameraActiveAreaKK.classList.remove('hidden');
            if (cameraBadgeKK) {
                cameraBadgeKK.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>Live`;
                cameraBadgeKK.className = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200";
            }
            startCameraKK();
        });

        switchCameraBtnKK.addEventListener('click', () => {
            if (videoDevicesKK.length > 1) {
                currentDeviceIndexKK = (currentDeviceIndexKK + 1) % videoDevicesKK.length;
            } else {
                currentFacingModeKK = currentFacingModeKK === 'environment' ? 'user' : 'environment';
            }
            startCameraKK();
        });

        snapBtnKK.addEventListener('click', () => {
            const context = canvasKK.getContext('2d');
            canvasKK.width = videoKK.videoWidth || 640;
            canvasKK.height = videoKK.videoHeight || 480;
            context.drawImage(videoKK, 0, 0, canvasKK.width, canvasKK.height);
            canvasKK.classList.remove('hidden');
            videoKK.classList.add('hidden');
            photoInputKK.value = canvasKK.toDataURL('image/jpeg', 0.85);
            snapBtnKK.classList.add('hidden');
            switchCameraBtnKK.classList.add('hidden');
            retakeBtnKK.classList.remove('hidden');
            
            if (cameraBadgeKK) {
                cameraBadgeKK.innerHTML = 'Foto Terekam';
                cameraBadgeKK.className = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200";
            }

            const photoErrBoxKK = document.getElementById('kk-photo-validation-error');
            if (photoErrBoxKK) {
                photoErrBoxKK.classList.add('hidden');
            }

            stopCameraTracksKK();
        });

        retakeBtnKK.addEventListener('click', () => {
            canvasKK.classList.add('hidden');
            videoKK.classList.remove('hidden');
            photoInputKK.value = '';
            retakeBtnKK.classList.add('hidden');
            if (cameraBadgeKK) {
                cameraBadgeKK.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>Live`;
                cameraBadgeKK.className = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200";
            }
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

                    if (cameraBadgeKK) {
                        cameraBadgeKK.innerHTML = 'Foto Berhasil Diunggah';
                        cameraBadgeKK.className = "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200";
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // --- SUBMIT VALIDATION ---
        document.getElementById('create-sub-pangkalan-form').addEventListener('submit', function(e) {
            const photoVal = photoInput ? photoInput.value : '';
            const photoErrBox = document.getElementById('photo-validation-error');
            const photoValKK = photoInputKK ? photoInputKK.value : '';
            const photoErrBoxKK = document.getElementById('kk-photo-validation-error');
            
            let hasError = false;

            if (!photoVal || photoVal.trim() === '') {
                hasError = true;
                if (photoErrBox) {
                    photoErrBox.classList.remove('hidden');
                }
            }

            if (!photoValKK || photoValKK.trim() === '') {
                hasError = true;
                if (photoErrBoxKK) {
                    photoErrBoxKK.classList.remove('hidden');
                }
            }

            if (hasError) {
                e.preventDefault();
                if (!photoVal || photoVal.trim() === '') {
                    const cameraSec = document.getElementById('camera-section');
                    if (cameraSec) {
                        cameraSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    const cameraSecKK = document.getElementById('kk-camera-section');
                    if (cameraSecKK) {
                        cameraSecKK.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                return false;
            }
        });
    });
</script>
@endsection
