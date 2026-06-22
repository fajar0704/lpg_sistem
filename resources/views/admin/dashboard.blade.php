@extends('layouts.admin')

@section('title', 'Dashboard Admin - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header & Time Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-950 rounded-2xl p-6 sm:p-8 shadow-xl">
        <!-- Decorative Glow Orbs -->
        <div class="absolute -top-24 -right-20 w-80 h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-20 w-80 h-80 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    Admin Pangkalan LPG
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-2">
                    Selamat Datang Kembali, {{ auth()->user()->name }}! 
                    <span class="animate-bounce inline-block text-2xl">👋</span>
                </h2>
                <p class="text-slate-300 text-sm sm:text-base mt-1">Kelola stok, penjualan, dan pantau distribusi sub pangkalan secara efisien.</p>
            </div>
            <div class="shrink-0 flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-3 rounded-xl border border-white/10 text-white shadow-sm">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div class="text-left">
                    <p class="text-xs text-slate-400 font-medium">Hari Ini</p>
                    <p class="text-sm font-semibold">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Stok Menipis -->
    @if($stockAlerts->isNotEmpty())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-sm transition-all duration-300 hover:shadow-md">
        <div class="flex items-start gap-3">
            <div class="p-2.5 bg-rose-500 text-white rounded-xl shadow-md shadow-rose-500/20 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="w-full">
                <h4 class="font-bold text-rose-800 text-base mb-1">Peringatan Stok Menipis!</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    @foreach($stockAlerts as $a)
                    <div class="bg-white/80 border border-rose-100 rounded-lg p-2.5 flex justify-between items-center text-sm shadow-xs">
                        <span class="font-semibold text-slate-700">{{ $a->tabung_type }}</span>
                        <span class="text-rose-600 font-medium bg-rose-100/50 px-2 py-0.5 rounded-md">
                            Sisa: <strong class="font-bold">{{ $a->stok_isi }}</strong> / {{ $a->safety_stock }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stat Cards Grid -->
    <div>
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Stok & Ringkasan Pangkalan
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Total Stok Isi -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Stok Isi</p>
                        <p class="text-2xl font-extrabold text-emerald-600 mt-2">{{ $totalStokIsi }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Stok Kosong -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Stok Kosong</p>
                        <p class="text-2xl font-extrabold text-amber-500 mt-2">{{ $totalStokKosong }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-500 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Jual Hari Ini -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Jual Hari Ini</p>
                        <p class="text-2xl font-extrabold text-blue-600 mt-2">{{ $totalJualLangsung }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Pelanggan -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Pelanggan</p>
                        <p class="text-2xl font-extrabold text-violet-600 mt-2">{{ $totalCustomers }}</p>
                    </div>
                    <div class="p-3 bg-violet-50 text-violet-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pengajuan Pending -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Pengajuan</p>
                        <p class="text-2xl font-extrabold text-amber-600 mt-2">{{ $pendingCount }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Kapasitas -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Stok</p>
                        <p class="text-2xl font-extrabold text-indigo-600 mt-2">{{ $totalStokIsi + $totalStokKosong }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Kapasitas: 150</p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Stok per Tipe Tabung -->
    <div>
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Detail Stok per Tipe Tabung
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($stocks as $stock)
            @php 
                $persen = $stock->max_stock > 0 ? round((($stock->stok_isi + $stock->stok_kosong) / $stock->max_stock) * 100) : 0; 
                $isBelowSafety = $stock->isBelowSafety();
            @endphp
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ $isBelowSafety ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                        <h4 class="font-bold text-slate-800 text-lg">{{ $stock->tabung_type }}</h4>
                    </div>
                    @if($isBelowSafety)
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-rose-50 text-rose-700 px-2.5 py-1 rounded-full border border-rose-100">
                        ⚠️ Menipis
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full border border-emerald-100">
                        ✓ Aman
                    </span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                    <div class="bg-emerald-50/50 border border-emerald-100/50 rounded-xl p-3 text-center">
                        <p class="text-slate-500 text-xs font-semibold mb-1">Stok Isi</p>
                        <p class="font-extrabold text-emerald-600 text-2xl">{{ $stock->stok_isi }}</p>
                    </div>
                    <div class="bg-amber-50/50 border border-amber-100/50 rounded-xl p-3 text-center">
                        <p class="text-slate-500 text-xs font-semibold mb-1">Stok Kosong</p>
                        <p class="font-extrabold text-amber-500 text-2xl">{{ $stock->stok_kosong }}</p>
                    </div>
                </div>

                @if($stock->max_stock > 0)
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs text-slate-500 font-medium">
                        <span>Kapasitas Simpan</span>
                        <span class="font-bold text-slate-700">{{ $stock->stok_isi + $stock->stok_kosong }} / {{ $stock->max_stock }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-500 {{ $isBelowSafety ? 'bg-gradient-to-r from-rose-500 to-red-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }}"
                            style="width: {{ min($persen, 100) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Riwayat Tabel Penjualan & Distribusi Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Penjualan Langsung Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Penjualan Langsung Terbaru</h3>
                </div>
                <a href="{{ route('admin.penjualan.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                    Semua
                    <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                            <th class="px-5 py-3.5 text-left">Tanggal</th>
                            <th class="px-5 py-3.5 text-left">Pembeli</th>
                            <th class="px-5 py-3.5 text-left">Kategori</th>
                            <th class="px-5 py-3.5 text-left">Tipe</th>
                            <th class="px-5 py-3.5 text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($recentPenjualan as $p)
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="px-5 py-3.5 text-slate-500 font-medium">{{ $p->transaction_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 text-slate-800 font-semibold">{{ $p->nama_pembeli ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $p->customer_type === 'rumah_tangga' ? 'bg-sky-50 text-sky-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $p->customer_type === 'rumah_tangga' ? '🏠 Rumah Tangga' : '🏪 Usaha Mikro' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-700 font-medium">{{ $p->tabung_type }}</td>
                            <td class="px-5 py-3.5 text-center text-slate-900 font-extrabold">{{ $p->quantity }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400 font-medium">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0H4"></path>
                                </svg>
                                Belum ada penjualan langsung hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Distribusi ke Sub Pangkalan Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Distribusi Sub Pangkalan</h3>
                </div>
                <a href="{{ route('admin.distribution.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                    Semua
                    <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-max">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                            <th class="px-5 py-3.5 text-left">Tanggal</th>
                            <th class="px-5 py-3.5 text-left">Sub Pangkalan</th>
                            <th class="px-5 py-3.5 text-left">Transaksi</th>
                            <th class="px-5 py-3.5 text-left">Tipe</th>
                            <th class="px-5 py-3.5 text-center">Jumlah</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($recentDistributions as $dist)
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="px-5 py-3.5 text-slate-500 font-medium">{{ $dist->transaction_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 text-slate-800 font-semibold">{{ $dist->subPangkalan->name }}</td>
                            <td class="px-5 py-3.5">
                                @if($dist->transaction_type === 'receive')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100/50">
                                        📥 Terima Isi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100/50">
                                        🔄 Tukar Kosong
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-700 font-medium">{{ $dist->tabung_type }}</td>
                            <td class="px-5 py-3.5 text-center text-slate-900 font-extrabold">{{ $dist->quantity }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @if($dist->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800">
                                    Disetujui
                                </span>
                                @elseif($dist->status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800">
                                    Pending
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800">
                                    Ditolak
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0H4"></path>
                                </svg>
                                Belum ada pengiriman distribusi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
