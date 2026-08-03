@extends('layouts.sub-pangkalan')

@section('title', 'Dashboard Pengecer - Sistem LPG')

@section('content')
<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in {
    animation: fadeIn 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
</style>
<div class="space-y-6">
    <!-- Welcome Header & Time Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 rounded-2xl p-6 sm:p-8 shadow-xl">
        <!-- Decorative Glow Orbs -->
        <div class="absolute -top-24 -right-20 w-80 h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-20 w-80 h-80 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    Sub Pangkalan Aktif
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-2">
                    Halo, {{ $subPangkalan->name }}! 
                    <span class="animate-bounce inline-block text-2xl">👋</span>
                </h2>
                <p class="text-slate-300 text-sm sm:text-base mt-1">Pantau stok LPG Anda, catat penjualan ke pelanggan, dan lakukan penukaran tabung kosong.</p>
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

    <!-- Stok Tabung Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Stok Tabung Isi -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Stok Tabung Isi</p>
                    <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ $subPangkalan->stok_isi }}</p>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Siap dijual langsung ke pelanggan terdaftar</p>
                </div>
                <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stok Tabung Kosong -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Stok Tabung Kosong</p>
                    <p class="text-4xl font-extrabold text-amber-500 mt-2">{{ $subPangkalan->stok_kosong }}</p>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Harus ditukar ke pangkalan untuk isi ulang</p>
                </div>
                <div class="p-4 bg-amber-50 text-amber-500 rounded-2xl shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Utama -->
    <div>
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Menu Aksi Cepat
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Data Pelanggan -->
            <a href="{{ route('sub-pangkalan.customers.index') }}"
                class="group bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="font-bold text-slate-800 text-lg">Data Pelanggan</p>
                    <p class="text-xs text-slate-500 mt-1">Daftarkan pelanggan baru sesuai KTP dan kelola profil identitas mereka.</p>
                </div>
                <div class="mt-4 flex items-center text-xs font-bold text-blue-600 group-hover:translate-x-1 transition duration-200">
                    Buka Menu →
                </div>
            </a>

            <!-- Jual ke Pelanggan -->
            <a href="{{ route('sub-pangkalan.sell.create') }}"
                class="group bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="font-bold text-slate-800 text-lg">Jual ke Pelanggan</p>
                    <p class="text-xs text-slate-500 mt-1">Catat transaksi penjualan tabung isi ke konsumen rumah tangga atau usaha mikro.</p>
                </div>
                <div class="mt-4 flex items-center text-xs font-bold text-blue-600 group-hover:translate-x-1 transition duration-200">
                    Buka Formulir (Stok: {{ $subPangkalan->stok_isi }}) →
                </div>
            </a>

            <!-- Tukar Tabung Kosong -->
            <a href="{{ route('sub-pangkalan.exchange.create') }}"
                class="group bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <p class="font-bold text-slate-800 text-lg">Tukar Tabung Kosong</p>
                    <p class="text-xs text-slate-500 mt-1">Ajukan penukaran tabung kosong yang menumpuk untuk ditukar dengan tabung isi di pangkalan.</p>
                </div>
                <div class="mt-4 flex items-center text-xs font-bold text-amber-600 group-hover:translate-x-1 transition duration-200">
                    Buka Formulir (Kosong: {{ $subPangkalan->stok_kosong }}) →
                </div>
            </a>
        </div>
    </div>

    <!-- Card Wrapper Utama untuk Aktivitas & Riwayat Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <!-- Tab Headers -->
        <div class="border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-5 pt-4 sm:pt-4 pb-4 gap-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Aktivitas & Riwayat Transaksi</h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Pantau seluruh log aktivitas transaksi Anda secara terpusat.</p>
                </div>
            </div>
            <!-- Tab Controls -->
            <div class="flex border-t border-slate-100/60 overflow-x-auto">
                <button onclick="switchTab('tab-penjualan')" id="btn-tab-penjualan" class="tab-btn px-6 py-3.5 text-xs sm:text-sm font-bold text-blue-600 border-b-2 border-blue-600 transition flex items-center gap-2 whitespace-nowrap focus:outline-none cursor-pointer">
                    🛒 Penjualan Pelanggan
                </button>
                <button onclick="switchTab('tab-pasokan')" id="btn-tab-pasokan" class="tab-btn px-6 py-3.5 text-xs sm:text-sm font-bold text-slate-500 hover:text-slate-700 border-b-2 border-transparent transition flex items-center gap-2 whitespace-nowrap focus:outline-none cursor-pointer">
                    📥 Penerimaan Tabung Isi
                </button>
                <button onclick="switchTab('tab-tukar')" id="btn-tab-tukar" class="tab-btn px-6 py-3.5 text-xs sm:text-sm font-bold text-slate-500 hover:text-slate-700 border-b-2 border-transparent transition flex items-center gap-2 whitespace-nowrap focus:outline-none cursor-pointer">
                    🔄 Tukar Tabung Kosong
                </button>
            </div>
        </div>

        <!-- TAB CONTENT 1: Penjualan Pelanggan -->
        <div id="tab-penjualan" class="tab-content block">
            <!-- Premium Filter Card Section (Persistent & Horizontal) -->
            <div class="p-5 border-b border-slate-100 bg-slate-50/10">
                <form id="filter-form" method="GET" action="{{ route('sub-pangkalan.dashboard') }}" class="flex flex-col sm:flex-row gap-4 items-slate sm:items-end">
                    <!-- Cari Pelanggan -->
                    <div class="w-full sm:w-72">
                        <label for="search" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari Pelanggan</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama / NIK..."
                            class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 placeholder-slate-400 font-semibold h-[42px]">
                    </div>

                    <!-- Bulan -->
                    <div class="w-full sm:w-56">
                        <label for="month" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
                        <select name="month" id="month" class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 font-semibold h-[42px]">
                            <option value="">Semua Bulan</option>
                            @foreach([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ] as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div class="w-full sm:w-52">
                        <label for="date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}"
                            class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 font-semibold h-[42px]">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 w-full sm:w-60">
                        <button type="submit" class="flex-1 justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition cursor-pointer flex items-center justify-center h-[42px] shadow-md shadow-blue-500/10">
                            Filter
                        </button>
                        <a href="{{ route('sub-pangkalan.dashboard') }}" id="btn-reset" class="flex-1 justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl text-sm transition flex items-center justify-center h-[42px]">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Filter info & Active Filters Indicators -->
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/20 flex flex-col gap-2">
                <div class="flex justify-between items-center w-full">
                    <p class="text-xs text-slate-500 font-medium">
                        Menampilkan <span class="font-bold text-slate-700">{{ $filteredCount }}</span> transaksi terbaru
                        @if(request()->anyFilled(['search', 'month', 'date']))
                            <span class="text-blue-600">(Difilter)</span>
                        @endif
                    </p>
                    <a href="{{ route('sub-pangkalan.history') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Semua Riwayat
                    </a>
                </div>
                
                @if(request()->anyFilled(['search', 'month', 'date']))
                <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-100">
                    <span class="text-xs text-slate-400 font-medium self-center">Filter Aktif:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-full text-[11px] font-semibold">
                            👤 "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-slate-900 ml-1">×</a>
                        </span>
                    @endif
                    @if(request('month'))
                        @php
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100/50 rounded-full text-[11px] font-semibold">
                            📅 {{ $months[request('month')] ?? '' }}
                            <a href="{{ request()->fullUrlWithQuery(['month' => null]) }}" class="hover:text-blue-900 ml-1">×</a>
                        </span>
                    @endif
                    @if(request('date'))
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100/50 rounded-full text-[11px] font-semibold">
                            📅 {{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}
                            <a href="{{ request()->fullUrlWithQuery(['date' => null]) }}" class="hover:text-indigo-900 ml-1">×</a>
                        </span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Transaction Table -->
            <div id="transaction-table-container" class="transition-opacity duration-300 ease-in-out">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                                <th class="px-5 py-3.5 text-left">Tanggal</th>
                                <th class="px-5 py-3.5 text-left">Jenis Transaksi</th>
                                <th class="px-5 py-3.5 text-left">Tipe Tabung</th>
                                <th class="px-5 py-3.5 text-center">Jumlah</th>
                                <th class="px-5 py-3.5 text-center">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($recentDistributions as $dist)
                            <tr class="hover:bg-slate-50/40 transition">
                                <td class="px-5 py-3.5 text-slate-500 font-medium">{{ $dist->transaction_date->format('d/m/Y') }}</td>
                                <td class="px-5 py-3.5">
                                    @if($dist->transaction_type === 'receive')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100/50">
                                            📥 Terima LPG
                                        </span>
                                    @elseif($dist->transaction_type === 'sell')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100/50">
                                            🛒 Jual ({{ $dist->customer->name ?? ($dist->customer_type === 'rumah_tangga' ? 'RT' : 'Usaha') }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100/50">
                                            🔄 Tukar Kosong
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 font-semibold">{{ $dist->tabung_type }}</td>
                                <td class="px-5 py-3.5 text-center text-slate-900 font-extrabold">{{ $dist->quantity }}</td>
                                <td class="px-5 py-3.5 text-center text-slate-600 font-medium">{{ $dist->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400 font-medium">
                                    <div class="max-w-xs mx-auto">
                                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0H4"></path>
                                        </svg>
                                        @if(request()->anyFilled(['search', 'month', 'date']))
                                            <p class="text-slate-600 font-semibold text-sm">Tidak ada transaksi yang cocok</p>
                                            <p class="text-xs text-slate-400 mt-1">Ubah filter pencarian Anda atau reset filter untuk kembali.</p>
                                        @else
                                            <p class="text-slate-600 font-semibold text-sm">Belum ada transaksi</p>
                                            <p class="text-xs text-slate-400 mt-1">Aktivitas penukaran, penjualan, atau terima LPG akan muncul di sini.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($recentDistributions->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $recentDistributions->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- TAB CONTENT 2: Penerimaan Tabung Isi -->
        <div id="tab-pasokan" class="tab-content hidden">
            <!-- Filter Section untuk Penerimaan Tabung Isi -->
            <div class="p-5 border-b border-slate-100 bg-slate-50/10">
                <form id="filter-refill-form" method="GET" action="{{ route('sub-pangkalan.dashboard') }}" class="flex flex-col sm:flex-row gap-4 items-slate sm:items-end">
                    <input type="hidden" name="active_tab" value="tab-pasokan">
                    
                    <!-- Filter Bulan -->
                    <div class="w-full sm:w-56">
                        <label for="refill_month" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
                        <select name="refill_month" id="refill_month" class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 font-semibold h-[42px]">
                            <option value="">Semua Bulan</option>
                            @foreach([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ] as $num => $name)
                                <option value="{{ $num }}" {{ request('refill_month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Tanggal -->
                    <div class="w-full sm:w-52">
                        <label for="refill_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal</label>
                        <input type="date" name="refill_date" id="refill_date" value="{{ request('refill_date') }}"
                            class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 font-semibold h-[42px]">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 w-full sm:w-60">
                        <button type="submit" class="flex-1 justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition cursor-pointer flex items-center justify-center h-[42px] shadow-md shadow-blue-500/10">
                            Filter
                        </button>
                        <button type="button" id="btn-reset-refill" class="flex-1 justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl text-sm transition flex items-center justify-center h-[42px] cursor-pointer">
                            Reset
                        </button>
                    </div>
                </form>
            </div>

            <div id="refill-table-wrapper" class="transition-opacity duration-300 ease-in-out">
                @include('sub-pangkalan.partials.refill-table-list')
            </div>
        </div>

        <!-- TAB CONTENT 3: Tukar Tabung Kosong -->
        <div id="tab-tukar" class="tab-content hidden">
            <!-- Filter Section untuk Tukar Tabung Kosong -->
            <div class="p-5 border-b border-slate-100 bg-slate-50/10">
                <form id="filter-exchange-form" method="GET" action="{{ route('sub-pangkalan.dashboard') }}" class="flex flex-col sm:flex-row gap-4 items-slate sm:items-end">
                    <input type="hidden" name="active_tab" value="tab-tukar">
                    
                    <!-- Filter Status -->
                    <div class="w-full sm:w-56">
                        <label for="exc_status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                        <select name="exc_status" id="exc_status" class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition duration-200 font-semibold h-[42px]">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('exc_status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ request('exc_status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="w-full sm:w-56">
                        <label for="exc_month" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
                        <select name="exc_month" id="exc_month" class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition duration-200 font-semibold h-[42px]">
                            <option value="">Semua Bulan</option>
                            @foreach([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ] as $num => $name)
                                <option value="{{ $num }}" {{ request('exc_month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 w-full sm:w-60">
                        <button type="submit" class="flex-1 justify-center bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-xl text-sm transition cursor-pointer flex items-center justify-center h-[42px] shadow-md shadow-amber-500/10">
                            Filter
                        </button>
                        <button type="button" id="btn-reset-exchange" class="flex-1 justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl text-sm transition flex items-center justify-center h-[42px] cursor-pointer">
                            Reset
                        </button>
                    </div>
                </form>
            </div>

            <div id="exchange-table-wrapper" class="transition-opacity duration-300 ease-in-out">
                @include('sub-pangkalan.partials.exchange-table-list')
            </div>
        </div>
    </div>
</div>

<script>
// Switch Tab Function
function switchTab(tabId) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('block', 'animate-fade-in');
    });
    
    // Show active tab content
    const activeTab = document.getElementById(tabId);
    if (activeTab) {
        activeTab.classList.remove('hidden');
        activeTab.classList.add('block', 'animate-fade-in');
    }
    
    // Reset tab buttons style
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-blue-600', 'border-blue-600');
        btn.classList.add('text-slate-500', 'hover:text-slate-700', 'border-transparent');
    });
    
    // Style active tab button
    const activeBtn = document.getElementById(`btn-${tabId}`);
    if (activeBtn) {
        activeBtn.classList.add('text-blue-600', 'border-blue-600');
        activeBtn.classList.remove('text-slate-500', 'hover:text-slate-700', 'border-transparent');
    }

    
    // Keep active tab in URL query param so pagination loads the right tab
    const url = new URL(window.location.href);
    url.searchParams.set('active_tab', tabId);
    window.history.replaceState({}, '', url.toString());
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const monthSelect = document.getElementById('month');
    const dateInput = document.getElementById('date');
    const btnReset = document.getElementById('btn-reset');
    const filterForm = document.getElementById('filter-form');

    // Parse active tab from URL on page load
    const urlParams = new URLSearchParams(window.location.search);
    let initialTab = urlParams.get('active_tab');
    if (!initialTab) {
        if (urlParams.has('refill_page')) initialTab = 'tab-pasokan';
        else if (urlParams.has('exc_page')) initialTab = 'tab-tukar';
        else initialTab = 'tab-penjualan';
    }
    switchTab(initialTab);

    function applyFilter() {
        const queryParams = new URLSearchParams();
        if (searchInput.value.trim()) queryParams.set('search', searchInput.value.trim());
        if (monthSelect.value) queryParams.set('month', monthSelect.value);
        if (dateInput.value) queryParams.set('date', dateInput.value);
        queryParams.set('active_tab', 'tab-penjualan'); // Reset to tab penjualan

        const url = `${window.location.pathname}?${queryParams.toString()}`;
        window.history.pushState({ path: url }, '', url);

        const container = document.getElementById('transaction-table-container');
        container.style.opacity = '0.5';

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const newTable = doc.getElementById('transaction-table-container');
                if (newTable) {
                    container.innerHTML = newTable.innerHTML;
                }
                
                const filterInfoDiv = document.querySelector('.flex.flex-col.gap-2');
                const newFilterInfoDiv = doc.querySelector('.flex.flex-col.gap-2');
                if (filterInfoDiv && newFilterInfoDiv) {
                    filterInfoDiv.innerHTML = newFilterInfoDiv.innerHTML;
                }

                container.style.opacity = '1';
                bindPaginationLinks('transaction-table-container');
            })
            .catch(err => {
                console.error(err);
                container.style.opacity = '1';
            });
    }

    function bindPaginationLinks(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const paginationLinks = container.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.getAttribute('href'));
                
                const currentTab = new URLSearchParams(window.location.search).get('active_tab') || 'tab-penjualan';
                url.searchParams.set('active_tab', currentTab);
                
                window.history.pushState({ path: url.toString() }, '', url.toString());
                container.style.opacity = '0.3';

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => data.html);
                    }
                    return response.text().then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById(containerId);
                        return newContent ? newContent.innerHTML : html;
                    });
                })
                .then(htmlContent => {
                    if (htmlContent) {
                        container.innerHTML = htmlContent;
                    }
                    container.style.opacity = '1';
                    bindPaginationLinks(containerId);
                    container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                })
                .catch(err => {
                    console.error(err);
                    container.style.opacity = '1';
                });
            });
        });
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilter();
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.value = '';
            monthSelect.value = '';
            dateInput.value = '';
            applyFilter();
        });
    }

    // Initial binding for transaction table
    bindPaginationLinks('transaction-table-container');

    // AJAX Filter Handler for Tukar Tabung Kosong
    const excStatusSelect = document.getElementById('exc_status');
    const excMonthSelect = document.getElementById('exc_month');
    const filterExchangeForm = document.getElementById('filter-exchange-form');
    const btnResetExchange = document.getElementById('btn-reset-exchange');

    function fetchExchanges(customUrl) {
        const queryParams = new URLSearchParams();
        if (excStatusSelect && excStatusSelect.value) queryParams.set('exc_status', excStatusSelect.value);
        if (excMonthSelect && excMonthSelect.value) queryParams.set('exc_month', excMonthSelect.value);
        queryParams.set('active_tab', 'tab-tukar');
        queryParams.set('target', 'exchange');

        const fetchUrl = customUrl || `${window.location.pathname}?${queryParams.toString()}`;
        window.history.pushState({ path: fetchUrl }, '', fetchUrl);

        const container = document.getElementById('exchange-table-wrapper');
        if (container) container.style.opacity = '0.4';

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => data.html);
            }
            return response.text().then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newEl = doc.getElementById('exchange-table-wrapper');
                return newEl ? newEl.innerHTML : html;
            });
        })
        .then(htmlContent => {
            if (container && htmlContent) {
                container.innerHTML = htmlContent;
                bindPaginationLinks('exchange-table-wrapper');
            }
            if (container) container.style.opacity = '1';
        })
        .catch(err => {
            console.error('Gagal memuat tukar tabung kosong:', err);
            if (container) container.style.opacity = '1';
        });
    }

    if (filterExchangeForm) {
        filterExchangeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchExchanges();
        });
    }

    if (btnResetExchange) {
        btnResetExchange.addEventListener('click', function(e) {
            e.preventDefault();
            if (excStatusSelect) excStatusSelect.value = '';
            if (excMonthSelect) excMonthSelect.value = '';
            fetchExchanges();
        });
    }

    bindPaginationLinks('exchange-table-wrapper');

    // AJAX Filter Handler for Penerimaan Tabung Isi
    const refillMonthSelect = document.getElementById('refill_month');
    const refillDateInput = document.getElementById('refill_date');
    const filterRefillForm = document.getElementById('filter-refill-form');
    const btnResetRefill = document.getElementById('btn-reset-refill');

    function fetchRefills(customUrl) {
        const queryParams = new URLSearchParams();
        if (refillMonthSelect && refillMonthSelect.value) queryParams.set('refill_month', refillMonthSelect.value);
        if (refillDateInput && refillDateInput.value) queryParams.set('refill_date', refillDateInput.value);
        queryParams.set('active_tab', 'tab-pasokan');
        queryParams.set('target', 'refill');

        const fetchUrl = customUrl || `${window.location.pathname}?${queryParams.toString()}`;
        window.history.pushState({ path: fetchUrl }, '', fetchUrl);

        const container = document.getElementById('refill-table-wrapper');
        if (container) container.style.opacity = '0.4';

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => data.html);
            }
            return response.text().then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newEl = doc.getElementById('refill-table-wrapper');
                return newEl ? newEl.innerHTML : html;
            });
        })
        .then(htmlContent => {
            if (container && htmlContent) {
                container.innerHTML = htmlContent;
                bindPaginationLinks('refill-table-wrapper');
            }
            if (container) container.style.opacity = '1';
        })
        .catch(err => {
            console.error('Gagal memuat penerimaan tabung:', err);
            if (container) container.style.opacity = '1';
        });
    }

    if (filterRefillForm) {
        filterRefillForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchRefills();
        });
    }

    if (btnResetRefill) {
        btnResetRefill.addEventListener('click', function(e) {
            e.preventDefault();
            if (refillMonthSelect) refillMonthSelect.value = '';
            if (refillDateInput) refillDateInput.value = '';
            fetchRefills();
        });
    }

    bindPaginationLinks('refill-table-wrapper');
});
</script>
@endsection

