@extends('layouts.sub-pangkalan')

@section('title', 'Dashboard Pengecer - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header & Time Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-emerald-900 to-teal-950 rounded-2xl p-6 sm:p-8 shadow-xl">
        <!-- Decorative Glow Orbs -->
        <div class="absolute -top-24 -right-20 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-20 w-80 h-80 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Portal Sub Pangkalan Aktif
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-2">
                    Halo, {{ $subPangkalan->name }}! 
                    <span class="animate-bounce inline-block text-2xl">👋</span>
                </h2>
                <p class="text-slate-300 text-sm sm:text-base mt-1">Pantau stok LPG Anda, catat penjualan ke pelanggan, dan lakukan penukaran tabung kosong.</p>
            </div>
            <div class="shrink-0 flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-3 rounded-xl border border-white/10 text-white shadow-sm">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <p class="text-4xl font-extrabold text-emerald-600 mt-2">{{ $subPangkalan->stok_isi }}</p>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Siap dijual langsung ke pelanggan terdaftar</p>
                </div>
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl shadow-inner">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Utama -->
    <div>
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Menu Aksi Cepat
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Ajukan Terima LPG -->
            <a href="{{ route('sub-pangkalan.input.create') }}"
                class="group bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </div>
                    <p class="font-bold text-slate-800 text-lg">Ajukan Terima LPG</p>
                    <p class="text-xs text-slate-500 mt-1">Minta kiriman atau laporkan penerimaan tabung isi baru dari pangkalan utama.</p>
                </div>
                <div class="mt-4 flex items-center text-xs font-bold text-blue-600 group-hover:translate-x-1 transition duration-200">
                    Buka Formulir →
                </div>
            </a>

            <!-- Jual ke Pelanggan -->
            <a href="{{ route('sub-pangkalan.sell.create') }}"
                class="group bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="font-bold text-slate-800 text-lg">Jual ke Pelanggan</p>
                    <p class="text-xs text-slate-500 mt-1">Catat transaksi penjualan tabung isi ke konsumen rumah tangga atau usaha mikro.</p>
                </div>
                <div class="mt-4 flex items-center text-xs font-bold text-emerald-600 group-hover:translate-x-1 transition duration-200">
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

    <!-- Riwayat Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Riwayat Transaksi Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar log aktivitas penjualan, penerimaan, dan penukaran tabung.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="toggleFilter()" id="toggleFilterBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 text-xs font-bold transition cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span id="btnText">Filter</span>
                    </button>
                    <a href="{{ route('sub-pangkalan.history') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Semua Riwayat
                    </a>
                </div>
            </div>
            
            <!-- Active Filters Indicators -->
            @if(request()->anyFilled(['transaction_type', 'status', 'tabung_type', 'date_from', 'date_to']))
            <div class="flex flex-wrap gap-2 mt-4 pt-3 border-t border-slate-100">
                <span class="text-xs text-slate-400 font-medium self-center">Filter Aktif:</span>
                @if(request('transaction_type'))
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100/50 rounded-full text-[11px] font-semibold">
                        @if(request('transaction_type') === 'receive') 📥 Terima LPG
                        @elseif(request('transaction_type') === 'sell') 🛒 Jual
                        @else 🔄 Tukar Kosong @endif
                        <a href="{{ request()->fullUrlWithQuery(['transaction_type' => null]) }}" class="hover:text-emerald-900 ml-1">×</a>
                    </span>
                @endif
                @if(request('status'))
                    @php 
                        $statusColor = request('status') === 'approved' ? 'emerald' : (request('status') === 'pending' ? 'amber' : 'rose');
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 border border-{{ $statusColor }}-100/50 rounded-full text-[11px] font-semibold">
                        @if(request('status') === 'approved') Disetujui
                        @elseif(request('status') === 'pending') Menunggu
                        @else Ditolak @endif
                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="hover:text-{{ $statusColor }}-900 ml-1">×</a>
                    </span>
                @endif
                @if(request('tabung_type'))
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100/50 rounded-full text-[11px] font-semibold">
                        Tabung {{ request('tabung_type') }}
                        <a href="{{ request()->fullUrlWithQuery(['tabung_type' => null]) }}" class="hover:text-indigo-900 ml-1">×</a>
                    </span>
                @endif
                @if(request('date_from') || request('date_to'))
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-full text-[11px] font-semibold">
                        📅 {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : '...' }} - {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : 'Sekarang' }}
                        <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'date_to' => null]) }}" class="hover:text-slate-900 ml-1">×</a>
                    </span>
                @endif
            </div>
            @endif
        </div>

        <!-- Collapsible Filter Form -->
        <div id="filterSection" class="hidden border-b border-slate-100 bg-slate-50/30">
            <form method="GET" action="{{ route('sub-pangkalan.dashboard') }}" class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label for="transaction_type" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Jenis Transaksi</label>
                        <select name="transaction_type" id="transaction_type" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm bg-white">
                            <option value="">Semua Jenis</option>
                            <option value="receive" {{ request('transaction_type') === 'receive' ? 'selected' : '' }}>📥 Terima LPG</option>
                            <option value="sell" {{ request('transaction_type') === 'sell' ? 'selected' : '' }}>🛒 Jual</option>
                            <option value="exchange" {{ request('transaction_type') === 'exchange' ? 'selected' : '' }}>🔄 Tukar Kosong</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm bg-white">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Disetujui</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label for="tabung_type" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipe Tabung</label>
                        <select name="tabung_type" id="tabung_type" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm bg-white">
                            <option value="">Semua Tipe</option>
                            @foreach($tabungTypes as $type)
                                <option value="{{ $type }}" {{ request('tabung_type') === $type ? 'selected' : '' }}>Tabung {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Dari</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm bg-white">
                    </div>
                    <div>
                        <label for="date_to" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Sampai</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm bg-white">
                    </div>
                </div>
                <div class="mt-5 flex gap-2">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition shadow-md shadow-emerald-600/10 cursor-pointer">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('sub-pangkalan.dashboard') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Filter info -->
        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/20 flex justify-between items-center">
            <p class="text-xs text-slate-500 font-medium">
                Menampilkan <span class="font-bold text-slate-700">{{ $filteredCount }}</span> transaksi terbaru
                @if(request()->anyFilled(['transaction_type', 'status', 'tabung_type', 'date_from', 'date_to']))
                    <span class="text-emerald-600">(Difilter)</span>
                @endif
            </p>
        </div>

        <script>
            function toggleFilter() {
                const filterSection = document.getElementById('filterSection');
                const btnText = document.getElementById('btnText');
                
                if (filterSection.classList.contains('hidden')) {
                    filterSection.classList.remove('hidden');
                    btnText.textContent = 'Sembunyikan Filter';
                } else {
                    filterSection.classList.add('hidden');
                    btnText.textContent = 'Filter';
                }
            }
            
            // Show filter if there are active filters on load
            @if(request()->anyFilled(['transaction_type', 'status', 'tabung_type', 'date_from', 'date_to']))
                document.addEventListener('DOMContentLoaded', function() {
                    toggleFilter();
                });
            @endif
        </script>

        <!-- Transaction Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                        <th class="px-5 py-3.5 text-left">Tanggal</th>
                        <th class="px-5 py-3.5 text-left">Jenis Transaksi</th>
                        <th class="px-5 py-3.5 text-left">Tipe Tabung</th>
                        <th class="px-5 py-3.5 text-center">Jumlah</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
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
                                    🛒 Jual ({{ $dist->customer_type === 'rumah_tangga' ? 'RT' : 'Usaha' }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100/50">
                                    🔄 Tukar Kosong
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-slate-700 font-semibold">{{ $dist->tabung_type }}</td>
                        <td class="px-5 py-3.5 text-center text-slate-900 font-extrabold">{{ $dist->quantity }}</td>
                        <td class="px-5 py-3.5 text-center">
                            @if($dist->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800">
                                Disetujui
                            </span>
                            @elseif($dist->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800">
                                Menunggu
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800">
                                Ditolak
                            </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($dist->transaction_type === 'receive' && $dist->status === 'pending')
                                <form action="{{ route('sub-pangkalan.distribution.confirm', $dist) }}" method="POST" onsubmit="return confirm('Konfirmasi terima LPG?')" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm hover:shadow transition cursor-pointer">
                                        Terima
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-400 font-medium">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium">
                            <div class="max-w-xs mx-auto">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0H4"></path>
                                </svg>
                                @if(request()->anyFilled(['transaction_type', 'status', 'tabung_type', 'date_from', 'date_to']))
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
    </div>
</div>
@endsection

