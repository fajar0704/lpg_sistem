@extends('layouts.admin')
@section('title', 'Profil Admin')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">👤 Profil Admin</h2>
    <p class="text-gray-500 text-sm">Kelola informasi akun Anda</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Info Card --}}
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <h3 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h3>
        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
        <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
            Admin Pangkalan
        </span>
        <div class="mt-4 pt-4 border-t text-sm text-gray-500">
            <p>Bergabung: {{ $user->created_at->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Form Edit --}}
    <div class="md:col-span-2 space-y-4">
        {{-- Update Info --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4">✏️ Edit Informasi</h3>
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="section" value="info">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                    💾 Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Ganti Password --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4">🔒 Ganti Password</h3>
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="section" value="password">

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password Lama</label>
                    <input type="password" name="current_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 font-semibold">
                    🔑 Ganti Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
