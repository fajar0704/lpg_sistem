@extends('layouts.admin')

@section('title', 'Monitoring Sub Pangkalan - Sistem LPG')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Monitoring Sub Pangkalan</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau stok, aktivitas harian, dan ringkasan penjualan setiap pengecer (Sub Pangkalan) secara real-time.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-600 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Data Terkini</span>
        </div>
    </div>

    <!-- Summary Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Pengecer -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Sub Pangkalan</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $subPangkalans->count() }}</h3>
                <p class="text-[11px] text-slate-500 font-medium mt-1">Pengecer terdaftar</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <!-- Card 2: Total Stok Isi -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Stok Isi</p>
                <h3 class="text-3xl font-extrabold text-blue-600 mt-2">{{ $subPangkalans->sum('stok_isi') }} <span class="text-xs font-medium text-slate-400">tbg</span></h3>
                <p class="text-[11px] text-slate-500 font-medium mt-1">LPG di seluruh pengecer</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>

        <!-- Card 3: Total Stok Kosong -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Stok Kosong</p>
                <h3 class="text-3xl font-extrabold text-amber-600 mt-2">{{ $subPangkalans->sum('stok_kosong') }} <span class="text-xs font-medium text-slate-400">tbg</span></h3>
                <p class="text-[11px] text-slate-500 font-medium mt-1">Tabung kosong di pengecer</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
        </div>

        <!-- Card 4: Menunggu Konfirmasi -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menunggu Konfirmasi</p>
                <h3 class="text-3xl font-extrabold {{ $pendingConfirmations > 0 ? 'text-rose-600' : 'text-slate-800' }} mt-2">{{ $pendingConfirmations }}</h3>
                <p class="text-[11px] text-slate-500 font-medium mt-1">Pengembalian tabung pending</p>
            </div>
            <div class="w-12 h-12 {{ $pendingConfirmations > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-400' }} rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 {{ $pendingConfirmations > 0 ? 'animate-bounce' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
        </div>
    </div>

    <!-- 1. Monitoring Stok Sub Pangkalan -->
    <section>
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Monitoring Stok Pengecer</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($subPangkalans as $sp)
            <div class="bg-white rounded-2xl p-6 border {{ $sp->is_active ? 'border-blue-100 shadow-blue-900/5 hover:shadow-lg' : 'border-slate-200 opacity-75' }} shadow-sm transition-all duration-300 relative overflow-hidden group">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-gradient-to-br {{ $sp->is_active ? 'from-blue-50 to-indigo-50' : 'from-slate-50 to-gray-50' }} opacity-50 group-hover:scale-110 transition-transform duration-500"></div>

                <div class="relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center shrink-0 border border-slate-100 shadow-sm">
                                @php
                                    $spProfilePhoto = optional($sp->user)->photo;
                                @endphp
                                @if($spProfilePhoto)
                                    <img src="{{ asset('storage/' . $spProfilePhoto) }}" alt="Foto Profile" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full {{ $sp->is_active ? 'bg-gradient-to-tr from-blue-500 to-indigo-600 text-white' : 'bg-slate-300 text-slate-600' }} flex items-center justify-center font-bold text-xl">
                                        {{ substr($sp->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg">{{ $sp->name }}</h4>
                                <p class="text-xs text-slate-500 font-medium tracking-wide">NIB: {{ $sp->code }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-[10px] uppercase font-bold rounded-full {{ $sp->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $sp->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <!-- Identity Info -->
                    <div class="mt-4 pt-3 border-t border-slate-100/60 text-xs text-slate-600 space-y-1.5 font-medium">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-semibold">Pemilik:</span>
                            <span class="text-slate-800 font-bold">{{ $sp->nama_ktp ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-semibold">Telepon:</span>
                            <span class="text-slate-800 font-semibold">{{ $sp->phone ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100/60">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1 font-medium">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Stok Isi
                            </div>
                            <div class="flex items-end gap-1">
                                <span class="text-2xl font-extrabold text-slate-800 leading-none">{{ $sp->stok_isi }}</span>
                                <span class="text-xs font-medium text-slate-500 mb-0.5">tbg</span>
                            </div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100/60">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1 font-medium">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Stok Kosong
                            </div>
                            <div class="flex items-end gap-1">
                                <span class="text-2xl font-extrabold text-slate-800 leading-none">{{ $sp->stok_kosong }}</span>
                                <span class="text-xs font-medium text-slate-500 mb-0.5">tbg</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100 flex justify-between items-center">
                        <div class="text-xs text-slate-500 max-w-[150px] truncate" title="{{ $sp->address }}">
                            <svg class="w-3.5 h-3.5 inline text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $sp->address }}
                        </div>
                        <a href="{{ route('admin.monitoring.detail', $sp->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 group/link">
                            Detail
                            <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center bg-white rounded-2xl border border-slate-200 border-dashed">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-slate-600 font-medium">Belum ada data Sub Pangkalan terdaftar.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- 2 & 3. Monitoring Aktivitas & Penjualan -->
    <section>
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Log Aktivitas</h3>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm mb-6">
            <form id="filter-monitoring-form" action="{{ route('admin.monitoring.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-end">
                <div class="w-full sm:w-72">
                    <label for="sub_pangkalan_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sub Pangkalan</label>
                    <select name="sub_pangkalan_id" id="sub_pangkalan_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-[42px]">
                        <option value="">Semua Sub Pangkalan</option>
                        @foreach($subPangkalans as $sp)
                            <option value="{{ $sp->id }}" {{ request('sub_pangkalan_id') == $sp->id ? 'selected' : '' }}>
                                {{ $sp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-64">
                    <label for="month" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
                    <select name="month" id="month" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-[42px]">
                        <option value="">Semua Bulan</option>
                        @foreach([
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ] as $num => $name)
                            <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 w-full sm:w-64">
                    <button type="submit" class="flex-1 justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition cursor-pointer flex items-center justify-center gap-2 h-[42px] shadow-md shadow-blue-500/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                    <button type="button" id="btn-reset-monitoring" class="flex-1 justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl text-sm transition flex items-center justify-center h-[42px] cursor-pointer">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <div id="activity-log-wrapper" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @include('admin.monitoring.partials.activity-log-table')
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-monitoring-form');
    const subPangkalanSelect = document.getElementById('sub_pangkalan_id');
    const monthSelect = document.getElementById('month');
    const btnReset = document.getElementById('btn-reset-monitoring');

    function fetchActivityLog(customUrl) {
        const queryParams = new URLSearchParams();
        if (subPangkalanSelect && subPangkalanSelect.value) queryParams.set('sub_pangkalan_id', subPangkalanSelect.value);
        if (monthSelect && monthSelect.value) queryParams.set('month', monthSelect.value);

        const fetchUrl = customUrl || `${window.location.pathname}?${queryParams.toString()}`;
        window.history.pushState({ path: fetchUrl }, '', fetchUrl);

        const wrapper = document.getElementById('activity-log-wrapper');
        const container = document.getElementById('activity-log-table-container');

        if (container) {
            container.style.opacity = '0.3';
        }

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            }
            return response.text().then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newEl = doc.getElementById('activity-log-wrapper');
                return { html: newEl ? newEl.innerHTML : html };
            });
        })
        .then(data => {
            if (data && data.html && wrapper) {
                wrapper.innerHTML = data.html;
                bindActivityLogPaginationLinks();
            }
            const updatedContainer = document.getElementById('activity-log-table-container');
            if (updatedContainer) {
                updatedContainer.style.opacity = '1';
            }
        })
        .catch(err => {
            console.error('Gagal memuat log aktivitas:', err);
            const updatedContainer = document.getElementById('activity-log-table-container');
            if (updatedContainer) {
                updatedContainer.style.opacity = '1';
            }
        });
    }

    function bindActivityLogPaginationLinks() {
        const wrapper = document.getElementById('activity-log-wrapper');
        if (!wrapper) return;
        const links = wrapper.querySelectorAll('nav a, .pagination a, nav[aria-label="Pagination Navigation"] a, nav[role="navigation"] a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageUrl = this.getAttribute('href');
                if (pageUrl && pageUrl !== '#') {
                    fetchActivityLog(pageUrl);
                    const mainEl = document.querySelector('main');
                    if (mainEl) {
                        mainEl.scrollTo({ top: wrapper.offsetTop - 20, behavior: 'smooth' });
                    } else {
                        wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchActivityLog();
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', function(e) {
            e.preventDefault();
            if (subPangkalanSelect) subPangkalanSelect.value = '';
            if (monthSelect) monthSelect.value = '';
            fetchActivityLog();
        });
    }

    bindActivityLogPaginationLinks();
});
</script>
@endsection
