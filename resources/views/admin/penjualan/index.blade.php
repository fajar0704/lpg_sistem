@extends('layouts.admin')

@section('title', 'Riwayat Penjualan - Sistem LPG')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center justify-between text-sm font-semibold">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center justify-between text-sm font-semibold">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>Riwayat Penjualan Langsung</span>
            </h2>
            <p class="text-slate-500 text-sm mt-1">Daftar transaksi penjualan langsung ke pembeli di pangkalan.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            @if($penjualan->count() > 0 || $totalSalesCount > 0)
            <form action="{{ route('admin.penjualan.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh riwayat penjualan langsung? Action ini tidak dapat dibatalkan.');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 font-bold px-4 py-3 rounded-xl border border-rose-200 transition-all duration-200 cursor-pointer text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Kosongkan Riwayat</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Hari Ini -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Terjual Hari Ini</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $todaySales }} <span class="text-xs font-medium text-slate-400">Tabung</span></p>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Terjual Bulan Ini</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $monthSales }} <span class="text-xs font-medium text-slate-400">Tabung</span></p>
            </div>
            <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Transaksi</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalSalesCount }} <span class="text-xs font-medium text-slate-400">Kali</span></p>
            </div>
            <div class="p-3.5 bg-indigo-50 text-indigo-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Rincian Ukuran Tabung -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300 flex flex-col justify-center">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">Total Terjual Per Ukuran</p>
            <div class="flex items-center justify-between text-xs font-semibold text-slate-700 space-x-1.5">
                <div class="flex-1 text-center bg-emerald-50/50 py-1.5 px-1 rounded-lg border border-emerald-100">
                    <span class="block text-[10px] text-emerald-700">3 Kg</span>
                    <span class="text-slate-800 font-extrabold">{{ $sales3kg }}</span>
                </div>
                <div class="flex-1 text-center bg-amber-50/50 py-1.5 px-1 rounded-lg border border-amber-100">
                    <span class="block text-[10px] text-amber-700">5 Kg</span>
                    <span class="text-slate-800 font-extrabold">{{ $sales5kg }}</span>
                </div>
                <div class="flex-1 text-center bg-rose-50/50 py-1.5 px-1 rounded-lg border border-rose-100">
                    <span class="block text-[10px] text-rose-700">12 Kg</span>
                    <span class="text-slate-800 font-extrabold">{{ $sales12kg }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-50">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h3 class="text-sm font-bold text-slate-800">Filter Pencarian</h3>
        </div>

        <form id="filterForm" action="{{ route('admin.penjualan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <!-- Search -->
            <div>
                <label for="search" class="block text-xs font-semibold text-slate-500 mb-1.5">Nama / NIK</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari pembeli..."
                    class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium">
            </div>

            <!-- Kategori -->
            <div>
                <label for="category" class="block text-xs font-semibold text-slate-500 mb-1.5">Kategori Pelanggan</label>
                <select name="category" id="category"
                    class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white font-medium">
                    <option value="">Semua Kategori</option>
                    <option value="rumah_tangga" {{ request('category') === 'rumah_tangga' ? 'selected' : '' }}>🏠 Rumah Tangga</option>
                    <option value="usaha_mikro" {{ request('category') === 'usaha_mikro' ? 'selected' : '' }}>🏪 UMKM (Usaha Mikro)</option>
                    <option value="pengecer" {{ request('category') === 'pengecer' ? 'selected' : '' }}>🏢 Sub Pangkalan (Pengecer)</option>
                    <option value="konsumen_umum" {{ request('category') === 'konsumen_umum' ? 'selected' : '' }}>🏢 Konsumen Umum (Pembeli Non Subsidi)</option>
                </select>
            </div>

            <!-- Tipe Tabung -->
            <div>
                <label for="tabung_type" class="block text-xs font-semibold text-slate-500 mb-1.5">Tipe Tabung</label>
                <select name="tabung_type" id="tabung_type"
                    class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white font-medium">
                    <option value="">Semua Ukuran</option>
                    <option value="3kg" {{ request('tabung_type') === '3kg' ? 'selected' : '' }}>3 Kg</option>
                    <option value="5kg" {{ request('tabung_type') === '5kg' ? 'selected' : '' }}>5 Kg</option>
                    <option value="12kg" {{ request('tabung_type') === '12kg' ? 'selected' : '' }}>12 Kg</option>
                </select>
            </div>

            <!-- Bulan -->
            <div>
                <label for="month" class="block text-xs font-semibold text-slate-500 mb-1.5">Bulan</label>
                <select name="month" id="month"
                    class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white font-medium">
                    <option value="">Semua Bulan</option>
                    @foreach([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ] as $mNum => $mName)
                        <option value="{{ $mNum }}" {{ request('month') == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun & Action Buttons -->
            <div>
                <label for="year" class="block text-xs font-semibold text-slate-500 mb-1.5">Tahun</label>
                <div class="flex gap-2">
                    <select name="year" id="year"
                        class="flex-1 text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white font-medium">
                        <option value="">Semua Tahun</option>
                        @php
                            $currentYr = (int) date('Y');
                            $startYr = 2024;
                        @endphp
                        @for($y = $currentYr; $y >= $startYr; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold p-2.5 rounded-xl transition duration-200 shrink-0 cursor-pointer shadow-sm" title="Filter Pencarian">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <a id="resetBtn" href="{{ route('admin.penjualan.index') }}" class="flex bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 font-bold p-2 rounded-lg transition duration-200 shrink-0 items-center justify-center group self-center" title="Reset Filter">
                        <svg class="w-4 h-4 transition-transform duration-500 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div id="table-container" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-opacity duration-200">
        <div class="overflow-x-auto">
            <table class="w-full min-w-max text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pembeli (KTP)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe Tabung</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Jumlah</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Dicatat Oleh</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($penjualan as $p)
                    <tr class="hover:bg-slate-50/40 transition duration-150">
                        <!-- Tanggal -->
                        <td class="px-6 py-4 font-semibold text-slate-700 whitespace-nowrap">
                            {{ $p->transaction_date->format('d M Y') }}
                        </td>
                        
                        <!-- Pembeli -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-800">{{ $p->nama_pembeli }}</div>
                            <div class="text-xs font-mono text-slate-400 mt-0.5">{{ $p->no_ktp }}</div>
                        </td>
 
                        <!-- Kategori -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->customer_type === 'rumah_tangga')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    🏠 Rumah Tangga
                                </span>
                            @elseif($p->customer_type === 'usaha_mikro')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                    🏪 Usaha Mikro
                                </span>
                            @elseif($p->customer_type === 'pengecer')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    🏢 Sub Pangkalan
                                </span>
                            @elseif($p->customer_type === 'konsumen_umum')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    🏢 Konsumen Umum
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-100">
                                    ❓ Lainnya
                                </span>
                            @endif
                        </td>
 
                        <!-- Tipe Tabung -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->tabung_type === '3kg')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Tabung 3 Kg
                                </span>
                            @elseif($p->tabung_type === '5kg')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                    Tabung 5 Kg
                                </span>
                            @elseif($p->tabung_type === '12kg')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                    Tabung 12 Kg
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-50 text-slate-700 border border-slate-100">
                                    {{ $p->tabung_type }}
                                </span>
                            @endif
                        </td>
 
                        <!-- Jumlah -->
                        <td class="px-6 py-4 font-extrabold text-slate-800 text-right whitespace-nowrap">
                            {{ $p->quantity }} <span class="text-xs font-semibold text-slate-400">Tabung</span>
                        </td>
 
                        <!-- Dicatat Oleh -->
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-[10px] font-bold border border-slate-200">
                                    {{ strtoupper(substr($p->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="font-medium text-xs">{{ $p->user->name ?? 'Admin' }}</span>
                            </div>
                        </td>
 
                        <!-- Catatan -->
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate">
                            {{ $p->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="font-bold text-slate-700 text-sm">Tidak Ada Riwayat Penjualan</span>
                                <span class="text-xs text-slate-400 mt-1 max-w-xs">Data penjualan kosong atau tidak ada transaksi yang cocok dengan filter pencarian Anda.</span>
                                
                                @if(request()->anyFilled(['search', 'category', 'tabung_type', 'start_date', 'end_date']))
                                <a href="{{ route('admin.penjualan.index') }}" class="mt-4 text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                    Reset Semua Filter
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($penjualan->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
            {{ $penjualan->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const inputs = form.querySelectorAll('input, select');
        const container = document.getElementById('table-container');
        const resetBtn = document.getElementById('resetBtn');

        let timeout = null;

        function fetchResults(url) {
            container.style.transition = 'opacity 0.25s ease-in-out, transform 0.25s ease-in-out';
            container.style.opacity = '0.3';
            container.style.transform = 'translateY(6px)';

            // Auto scroll directly to top of table
            const mainEl = document.querySelector('main');
            if (mainEl) {
                mainEl.scrollTo({ top: container.offsetTop - 20, behavior: 'smooth' });
            } else {
                container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('table-container');
                
                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                }
                
                setTimeout(() => {
                    container.style.opacity = '1';
                    container.style.transform = 'translateY(0)';
                }, 50);
                
                // Update URL history
                window.history.pushState({ path: url }, '', url);
                
                // Re-bind pagination links
                bindPagination();
            })
            .catch(error => {
                console.error('Error:', error);
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            });
        }

        function updateFilter() {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const url = `${form.action}?${params.toString()}`;
            fetchResults(url);
        }


        form.addEventListener('submit', function(e) {
            e.preventDefault();
            updateFilter();
        });
        
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                inputs.forEach(input => input.value = '');
                updateFilter();
            });
        }

        function bindPagination() {
            const links = container.querySelectorAll('nav a, .pagination a, nav[aria-label="Pagination Navigation"] a, nav[role="navigation"] a');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (this.href && this.href !== '#') {
                        fetchResults(this.href);
                    }
                });
            });
        }

        bindPagination();
        
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.path) {
                fetchResults(e.state.path);
            }
        });
    });
</script>
@endsection
