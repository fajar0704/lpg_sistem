@extends('layouts.sub-pangkalan')

@section('title', 'Profil Saya - Sub Pangkalan')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
            <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil Saya
        </h2>
        <p class="text-slate-500 text-sm mt-1">Kelola dan perbarui informasi profil sub pangkalan Anda.</p>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
    <div class="max-w-6xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-6xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center gap-3">
        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    @if(session('warning'))
    <div class="max-w-6xl bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('warning') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl">
        {{-- Info Card (Kiri) --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden h-fit space-y-6">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <div class="text-center space-y-4 pt-4">
                @if($user->photo)
                    <div class="space-y-2">
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil Pemilik" class="w-24 h-24 rounded-full object-cover mx-auto shadow-md border border-slate-100">
                        <form action="{{ route('sub-pangkalan.profile.delete-photo') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold transition duration-200 cursor-pointer shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Hapus Foto</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="w-24 h-24 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white text-4xl font-extrabold mx-auto shadow-md shadow-blue-500/10">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">{{ $subPangkalan->nama_ktp }}</h3>
                    <p class="text-slate-400 text-sm font-medium mt-0.5">{{ $subPangkalan->name }}</p>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-full text-xs font-bold uppercase tracking-wider">
                        🏪 Sub Pangkalan
                    </span>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 space-y-4 text-xs font-semibold text-slate-600">
                <div>
                    <span class="block text-slate-400 font-bold uppercase tracking-wider">Informasi Usaha</span>
                    <div class="mt-2 space-y-2 text-slate-800">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Nomor Induk Berusaha (NIB):</span>
                            <span>{{ $subPangkalan->code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Nama Usaha:</span>
                            <span>{{ $subPangkalan->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100/60">
                    <span class="block text-slate-400 font-bold uppercase tracking-wider">Informasi Akun</span>
                    <div class="mt-2 space-y-2 text-slate-800">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Username:</span>
                            <span>{{ $user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">NIK KTP:</span>
                            <span>{{ $subPangkalan->ktp }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Edit (Kanan) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Update Info --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                
                <h3 class="font-extrabold text-slate-800 text-lg tracking-tight mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Ubah Informasi Profil
                </h3>
                
                <form action="{{ route('sub-pangkalan.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf @method('PUT')
                    <input type="hidden" name="section" value="info">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama Pemilik --}}
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Nama Pemilik</label>
                            <input type="text" name="nama_ktp" value="{{ old('nama_ktp', $subPangkalan->nama_ktp) }}" required
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('nama_ktp') border-rose-500 focus:ring-rose-500/20 @enderror">
                            @error('nama_ktp')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                        </div>

                        {{-- Nomor HP --}}
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Nomor HP</label>
                            <input type="text" name="phone" value="{{ old('phone', $subPangkalan->phone) }}" required
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('phone') border-rose-500 focus:ring-rose-500/20 @enderror">
                            @error('phone')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('email') border-rose-500 focus:ring-rose-500/20 @enderror">
                            @error('email')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                        </div>

                        {{-- Username (Read Only) --}}
                        <div>
                            <label class="block text-slate-400 text-sm font-semibold mb-2">Username (Tidak Dapat Diubah)</label>
                            <input type="text" value="{{ $user->name }}" readonly disabled
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-100/80 rounded-xl text-slate-400 font-medium cursor-not-allowed select-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Alamat --}}
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Alamat Usaha</label>
                            <input type="text" name="address" value="{{ old('address', $subPangkalan->address) }}" required
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('address') border-rose-500 focus:ring-rose-500/20 @enderror">
                            @error('address')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                        </div>

                        {{-- Nama Sub Pangkalan (Read Only) --}}
                        <div>
                            <label class="block text-slate-400 text-sm font-semibold mb-2">Nama Sub Pangkalan (Tidak Dapat Diubah)</label>
                            <input type="text" value="{{ $subPangkalan->name }}" readonly disabled
                                class="w-full px-4 py-3 border border-slate-200 bg-slate-100/80 rounded-xl text-slate-400 font-medium cursor-not-allowed select-none">
                        </div>
                    </div>

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Foto Profil (Opsional)</label>
                        <div class="flex items-center gap-3">
                            <input type="file" name="photo" accept="image/*"
                                class="w-full px-4 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('photo') border-rose-500/20 @enderror">
                            @if($user->photo)
                                <button type="submit" form="delete-photo-form" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-xl font-bold text-xs shrink-0 transition duration-200 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Hapus Foto</span>
                                </button>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Format: JPEG, PNG, JPG, WEBP. Maks: 2MB.</p>
                        @error('photo')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                            💾 Simpan Perubahan
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
                    Informasi Akun Login (Keamanan)
                </h3>
                
                <form action="{{ route('sub-pangkalan.profile.update') }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <input type="hidden" name="section" value="password">

                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Password Lama</label>
                        <div class="relative" style="position: relative;">
                            <input type="password" id="current_password" name="current_password" required placeholder="Masukkan password lama Anda"
                                class="w-full px-4 py-3 pr-12 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('current_password') border-rose-500 focus:ring-rose-500/20 @enderror">
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
                                    class="w-full px-4 py-3 pr-12 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium @error('password') border-rose-500 focus:ring-rose-500/20 @enderror">
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
                                    class="w-full px-4 py-3 pr-12 border border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium">
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
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-rose-500/20 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                            🔑 Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="delete-photo-form" action="{{ route('sub-pangkalan.profile.delete-photo') }}" method="POST" class="hidden" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
    @csrf
    @method('DELETE')
</form>

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
</script>
@endsection
