@extends('layouts.admin')

@section('title', 'Profil Saya - Sistem LPG')

@section('content')
@php
    $loginTitle = \App\Models\Setting::getValue('login_title', 'Sistem Pangkalan LPG');
    $loginSubtitle = \App\Models\Setting::getValue('login_subtitle', 'Silakan masuk untuk mengelola LPG');
    $loginLogo = \App\Models\Setting::getValue('login_logo');
@endphp
<div class="space-y-6">
    <!-- Header Page -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Profil Saya</h2>
                <p class="text-slate-500 text-xs sm:text-sm">Kelola informasi akun administrator dan penyesuaian halaman sistem.</p>
            </div>
        </div>
    </div>

    <!-- Alert Success / Error / Warning -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-sm font-semibold">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-sm font-semibold">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Card (Kiri) --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden space-y-6">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600"></div>
                
                <div class="text-center space-y-4 pt-2">
                    <div class="relative inline-block mx-auto">
                        @if($user->photo)
                            <img id="avatarImage" src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="w-28 h-28 rounded-full object-cover shadow-lg border-2 border-white ring-4 ring-blue-500/10">
                        @else
                            <div id="avatarFallback" class="w-28 h-28 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white text-5xl font-black shadow-lg shadow-blue-500/20 ring-4 ring-blue-500/10 mx-auto">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">{{ $user->name }}</h3>
                        <p class="text-slate-400 text-sm font-medium mt-0.5">{{ $user->email }}</p>
                    </div>

                    @if($user->photo)
                    <div>
                        <form id="delete-photo-form" action="{{ route('admin.profile.delete-photo') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 rounded-xl text-xs font-bold transition duration-200 cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Hapus Foto Profil</span>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Edit Sections (Kanan) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Update Info Profil --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                
                <h3 class="font-extrabold text-slate-800 text-lg tracking-tight mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Ubah Informasi Profil
                </h3>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf 
                    @method('PUT')
                    <input type="hidden" name="section" value="info">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('name') border-rose-500 focus:ring-rose-500/20 @enderror">
                            @error('name')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('email') border-rose-500 focus:ring-rose-500/20 @enderror">
                            @error('email')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Foto Profil Baru (Opsional)</label>
                        <div class="space-y-3">
                            <input type="file" id="photoInput" name="photo" accept="image/*" onchange="previewPhoto(this)"
                                class="w-full px-4 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('photo') border-rose-500/20 @enderror">
                            
                            {{-- Live Image Preview Container --}}
                            <div id="photoPreviewBox" class="hidden flex items-center gap-3 p-3 bg-blue-50/60 border border-blue-100 rounded-xl">
                                <img id="photoPreviewImg" src="#" alt="Pratinjau Foto" class="w-14 h-14 rounded-full object-cover border border-blue-200 shadow-xs">
                                <div>
                                    <span class="block text-xs font-bold text-blue-900">Pratinjau Foto Profil Baru</span>
                                    <span class="text-[11px] text-blue-600 font-medium">Foto siap disimpan setelah klik Simpan Perubahan.</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">Format yang didukung: JPEG, PNG, JPG, WEBP. Ukuran maksimal: 2MB.</p>
                        @error('photo')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer flex items-center gap-2">
                            <span>💾 Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Ganti Password --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-500 to-red-600"></div>
                
                <h3 class="font-extrabold text-slate-800 text-lg tracking-tight mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Ganti Password Akun
                </h3>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
                    @csrf 
                    @method('PUT')
                    <input type="hidden" name="section" value="password">

                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Password Lama</label>
                        <div class="relative" style="position: relative;">
                            <input type="password" id="current_password" name="current_password" required placeholder="Masukkan password lama Anda"
                                class="w-full px-4 py-3 pr-12 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition duration-200 text-slate-800 font-medium @error('current_password') border-rose-500 focus:ring-rose-500/20 @enderror">
                            <button type="button" onclick="togglePasswordVisibility('current_password', 'currentPasswordEye')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-100 cursor-pointer focus:outline-none z-10">
                                <svg id="currentPasswordEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Password Baru</label>
                            <div class="relative" style="position: relative;">
                                <input type="password" id="password" name="password" required placeholder="Min. 6 karakter"
                                    class="w-full px-4 py-3 pr-12 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition duration-200 text-slate-800 font-medium @error('password') border-rose-500 focus:ring-rose-500/20 @enderror">
                                <button type="button" onclick="togglePasswordVisibility('password', 'passwordEye')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-100 cursor-pointer focus:outline-none z-10">
                                    <svg id="passwordEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Konfirmasi Password Baru</label>
                            <div class="relative" style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang password baru"
                                    class="w-full px-4 py-3 pr-12 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition duration-200 text-slate-800 font-medium">
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirmPasswordEye')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-100 cursor-pointer focus:outline-none z-10">
                                    <svg id="confirmPasswordEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-rose-500/20 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer flex items-center gap-2">
                            <span>🔑 Ganti Password</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Pengaturan Halaman Login --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-extrabold text-slate-800 text-lg tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pengaturan Halaman Login
                    </h3>

                    @if($loginLogo)
                    <form action="{{ route('admin.profile.delete-login-logo') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus logo login kustom dan mengembalikan ke logo default Pertamina Elpiji?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-xl text-xs font-bold transition duration-200 cursor-pointer shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Reset Logo Login</span>
                        </button>
                    </form>
                    @endif
                </div>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf 
                    @method('PUT')
                    <input type="hidden" name="section" value="login_settings">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Judul Halaman Login (Title)</label>
                            <input type="text" name="login_title" value="{{ old('login_title', $loginTitle) }}" required
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition duration-200 text-slate-800 font-medium">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Subjudul Halaman Login (Subtitle)</label>
                            <input type="text" name="login_subtitle" value="{{ old('login_subtitle', $loginSubtitle) }}" required
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition duration-200 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Logo Halaman Login (Khusus)</label>
                        @if($loginLogo)
                            <div class="mb-3 flex items-center gap-3 p-3 bg-slate-50 border border-slate-200/80 rounded-xl">
                                <div class="w-14 h-14 rounded-full overflow-hidden border border-slate-200 bg-white p-1 flex items-center justify-center shrink-0 shadow-2xs">
                                    <img src="{{ asset('storage/' . $loginLogo) }}" alt="Logo Login Saat Ini" class="w-full h-full rounded-full object-cover">
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-slate-800">Logo Login Kustom Aktif</span>
                                    <span class="text-[11px] text-slate-500 font-medium">Terlihat di halaman login pengguna saat ini.</span>
                                </div>
                            </div>
                        @else
                            <div class="mb-3 flex items-center gap-3 p-3 bg-slate-50 border border-slate-200/80 rounded-xl">
                                <div class="w-14 h-14 rounded-full overflow-hidden border border-slate-200 bg-white p-1 flex items-center justify-center shrink-0 shadow-2xs">
                                    <img src="{{ asset('images/elpiji_logo.png') }}" alt="Logo Default" class="w-full h-full rounded-full object-cover">
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-slate-800">Logo Default (Pertamina Elpiji)</span>
                                    <span class="text-[11px] text-slate-500 font-medium">Unggah berkas gambar jika ingin menggunakan logo instansi / toko Anda.</span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="space-y-3">
                            <input type="file" id="loginLogoInput" name="login_logo" accept="image/*" onchange="previewLoginLogo(this)"
                                class="w-full px-4 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition duration-200 text-slate-800 font-medium @error('login_logo') border-rose-500/20 @enderror">
                            
                            {{-- Live Image Preview Container Login Logo --}}
                            <div id="loginLogoPreviewBox" class="hidden flex items-center gap-3 p-3 bg-emerald-50/60 border border-emerald-100 rounded-xl">
                                <img id="loginLogoPreviewImg" src="#" alt="Pratinjau Logo Login Baru" class="w-14 h-14 rounded-full object-cover border border-emerald-200 shadow-xs">
                                <div>
                                    <span class="block text-xs font-bold text-emerald-900">Pratinjau Logo Login Baru</span>
                                    <span class="text-[11px] text-emerald-600 font-medium">Logo siap diterapkan ke halaman login setelah disimpan.</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">Format: JPEG, PNG, JPG, WEBP. Maksimal: 2MB.</p>
                        @error('login_logo')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" style="background-color: #10b981;" class="hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-emerald-500/10 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer flex items-center gap-2">
                            <span>💾 Simpan Pengaturan Login</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.959 8.959 0 013.98-.963c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-6.19-6.191a3 3 0 11-4.243-4.243m4.243 4.243L3 3l18 18"></path>`;
        } else {
            input.type = 'password';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
        }
    }

    function previewPhoto(input) {
        const previewBox = document.getElementById('photoPreviewBox');
        const previewImg = document.getElementById('photoPreviewImg');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBox.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            previewBox.classList.add('hidden');
        }
    }

    function previewLoginLogo(input) {
        const previewBox = document.getElementById('loginLogoPreviewBox');
        const previewImg = document.getElementById('loginLogoPreviewImg');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBox.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            previewBox.classList.add('hidden');
        }
    }
</script>
@endsection
