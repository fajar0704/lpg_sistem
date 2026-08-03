@extends('layouts.app')

@section('title', 'Login - Sistem LPG')

@section('content')
<div class="relative min-h-screen flex items-center justify-center bg-gradient-to-tr from-slate-900 via-blue-900 to-indigo-950 px-4 overflow-hidden">
    <!-- Decorative Glowing Background Orbs -->
    <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="relative bg-white/95 backdrop-blur-md p-8 sm:p-10 rounded-2xl shadow-2xl border border-white/20 w-full max-w-md transition-all duration-300 hover:shadow-blue-500/10 hover:shadow-2xl">
        <div class="text-center mb-8">
                @php
                    $customLogo = \App\Models\Setting::getValue('login_logo');
                    $logoUrl = $customLogo ? asset('storage/' . $customLogo) : asset('images/elpiji_logo.png');
                    $loginTitle = \App\Models\Setting::getValue('login_title', 'Sistem Pangkalan LPG');
                    $loginSubtitle = \App\Models\Setting::getValue('login_subtitle', 'Silakan masuk untuk mengelola LPG');
                @endphp
                <div class="w-24 h-24 bg-white rounded-full shadow-md border border-slate-100 hover:scale-105 transition-transform duration-300 overflow-hidden flex items-center justify-center p-1 mx-auto mb-5">
                    <img src="{{ $logoUrl }}" alt="Logo Pangkalan" class="w-full h-full rounded-full object-cover">
                </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">{{ $loginTitle }}</h1>
            <p class="text-sm sm:text-base text-slate-500 mt-2">{{ $loginSubtitle }}</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-start gap-2">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="text-sm">
                    @foreach($errors->all() as $error)
                        <p class="font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-start gap-2">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label for="email" class="block text-slate-700 text-sm font-semibold mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                        </svg>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                        class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 @error('email') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror"
                        placeholder="contoh@lpg.com" required autofocus>
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-slate-700 text-sm font-semibold mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password" 
                        class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 @error('password') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror"
                        placeholder="Masukkan password Anda" required>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition duration-150">
                    <label for="remember" class="ml-2 text-sm text-slate-600 font-medium cursor-pointer select-none">Ingat Saya</label>
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/35 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 font-bold text-base sm:text-lg flex justify-center items-center gap-2 cursor-pointer">
                <span>Login ke Sistem</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
            </button>
        </form>
    </div>
</div>
@endsection

