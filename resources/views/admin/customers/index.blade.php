@extends('layouts.admin')

@section('title', 'Pelanggan Umum - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Tab Navigation -->
    <div class="border-b border-slate-200">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            <a href="{{ route('admin.customers.index') }}" class="border-b-2 py-4 px-1 font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }} whitespace-nowrap">
                👥 Pelanggan Umum
            </a>
            <a href="{{ route('admin.sub-pangkalan.index') }}" class="border-b-2 py-4 px-1 font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.sub-pangkalan.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-300' }} whitespace-nowrap">
                🏢 Sub Pangkalan (Pengecer)
            </a>
        </nav>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Manajemen Pelanggan Umum</span>
            </h2>
            <p class="text-slate-500 text-sm mt-1">Daftar dan kelola data pelanggan umum (Rumah Tangga & Usaha Mikro).</p>
        </div>
        <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-5 py-3 rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-indigo-600/30 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Pelanggan</span>
        </a>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Pelanggan -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Pelanggan</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $totalCustomers }} <span class="text-xs font-medium text-slate-400">Orang</span></p>
            </div>
            <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Rumah Tangga -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Rumah Tangga</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $rumahTanggaCount }} <span class="text-xs font-medium text-slate-400">Orang</span></p>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
        </div>

        <!-- Usaha Mikro (UMKM) -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Usaha Mikro (UMKM)</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $usahaMikroCount }} <span class="text-xs font-medium text-slate-400">Orang</span></p>
            </div>
            <div class="p-3.5 bg-purple-50 text-purple-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>

        <!-- Konsumen Umum -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Konsumen Umum (Pembeli Non Subsidi)</p>
                <p class="text-2xl font-extrabold text-slate-800">{{ $konsumenUmumCount }} <span class="text-xs font-medium text-slate-400">Orang</span></p>
            </div>
            <div class="p-3.5 bg-teal-50 text-teal-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-50">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h3 class="text-sm font-bold text-slate-800">Filter Pelanggan</h3>
        </div>

        <form id="filter-customer-form" action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-end">
            <!-- Search -->
            <div class="w-full sm:w-72">
                <label for="search" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama / NIK</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK..."
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 placeholder-slate-400 font-semibold h-[42px]">
            </div>

            <!-- Kategori -->
            <div class="w-full sm:w-64">
                <label for="category" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Pelanggan</label>
                <select name="category" id="category" onchange="if(this.value === 'pengecer') window.location.href='{{ route('admin.sub-pangkalan.index') }}'"
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 font-semibold h-[42px]">
                    <option value="">Semua Kategori</option>
                    <option value="rumah_tangga" {{ request('category') === 'rumah_tangga' ? 'selected' : '' }}>🏠 Rumah Tangga</option>
                    <option value="usaha_mikro" {{ request('category') === 'usaha_mikro' ? 'selected' : '' }}>🏪 Usaha Mikro</option>
                    <option value="pengecer" {{ request('category') === 'pengecer' ? 'selected' : '' }}>🏢 Sub Pangkalan (Pengecer)</option>
                    <option value="konsumen_umum" {{ request('category') === 'konsumen_umum' ? 'selected' : '' }}>🏢 Konsumen Umum (Pembeli Non Subsidi)</option>
                </select>
            </div>

            <!-- Action Buttons (Sejajar) -->
            <div class="flex gap-2 w-full sm:w-64">
                <button type="submit" class="flex-1 justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition cursor-pointer flex items-center justify-center gap-1.5 h-[42px] shadow-md shadow-blue-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Terapkan</span>
                </button>
                <button type="button" id="btn-reset-customer" class="flex-1 justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl text-sm transition flex items-center justify-center h-[42px] cursor-pointer">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div id="customer-table-wrapper" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @include('admin.customers.partials.customer-table-list')
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-customer-form');
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category');
    const btnReset = document.getElementById('btn-reset-customer');

    function fetchCustomers(customUrl) {
        const queryParams = new URLSearchParams();
        if (searchInput && searchInput.value.trim()) queryParams.set('search', searchInput.value.trim());
        if (categorySelect && categorySelect.value) queryParams.set('category', categorySelect.value);

        const fetchUrl = customUrl || `${window.location.pathname}?${queryParams.toString()}`;
        window.history.pushState({ path: fetchUrl }, '', fetchUrl);

        const wrapper = document.getElementById('customer-table-wrapper');
        const container = document.getElementById('customer-table-container');

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
                const newEl = doc.getElementById('customer-table-wrapper');
                return { html: newEl ? newEl.innerHTML : html };
            });
        })
        .then(data => {
            if (data && data.html && wrapper) {
                wrapper.innerHTML = data.html;
                bindCustomerPaginationLinks();
            }
            const updatedContainer = document.getElementById('customer-table-container');
            if (updatedContainer) {
                updatedContainer.style.opacity = '1';
            }
        })
        .catch(err => {
            console.error('Gagal memuat data pelanggan:', err);
            const updatedContainer = document.getElementById('customer-table-container');
            if (updatedContainer) {
                updatedContainer.style.opacity = '1';
            }
        });
    }

    function bindCustomerPaginationLinks() {
        const wrapper = document.getElementById('customer-table-wrapper');
        if (!wrapper) return;
        const links = wrapper.querySelectorAll('.pagination a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageUrl = this.getAttribute('href');
                if (pageUrl) {
                    fetchCustomers(pageUrl);
                    wrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        });
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchCustomers();
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', function(e) {
            e.preventDefault();
            if (searchInput) searchInput.value = '';
            if (categorySelect) categorySelect.value = '';
            fetchCustomers();
        });
    }

    bindCustomerPaginationLinks();
});
</script>
@endsection
