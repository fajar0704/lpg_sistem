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
            <p class="text-slate-500 text-sm mt-1">Daftar dan kelola data pelanggan umum.</p>
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

        <form id="filter-customer-form" action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-end flex-wrap">
            <!-- Search -->
            <div class="w-full sm:w-72">
                <label for="search" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama / NIK</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK..."
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 placeholder-slate-400 font-semibold h-[42px]">
            </div>

            <!-- Asal Pendaftaran -->
            <div class="w-full sm:w-64">
                <label for="source" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Data Pelanggan</label>
                <select name="source" id="source"
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 font-semibold h-[42px]">
                    <option value="pangkalan" {{ request('source', 'pangkalan') === 'pangkalan' ? 'selected' : '' }}>🏠 Pelanggan Pangkalan Utama</option>
                    <option value="sub_pangkalan" {{ request('source') === 'sub_pangkalan' ? 'selected' : '' }}>🏢 Pelanggan Sub Pangkalan (Pengecer)</option>
                </select>
            </div>

            <!-- Kategori -->
            <div class="w-full sm:w-64" id="category-filter-wrapper">
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

            <!-- Filter Pengecer -->
            <div class="w-full sm:w-64" id="sub-pangkalan-filter-wrapper" style="display: none;">
                <label for="sub_pangkalan_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filter Pengecer</label>
                <select name="sub_pangkalan_id" id="sub_pangkalan_id"
                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 font-semibold h-[42px]">
                    <option value="">Semua Pengecer</option>
                    @foreach($subPangkalansList as $sp)
                        <option value="{{ $sp->id }}" {{ request('sub_pangkalan_id') == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                    @endforeach
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
    const sourceSelect = document.getElementById('source');
    const categorySelect = document.getElementById('category');
    const categoryFilterWrapper = document.getElementById('category-filter-wrapper');
    const subPangkalanSelect = document.getElementById('sub_pangkalan_id');
    const subPangkalanFilterWrapper = document.getElementById('sub-pangkalan-filter-wrapper');
    const btnReset = document.getElementById('btn-reset-customer');

    function toggleFilters() {
        if (sourceSelect) {
            if (sourceSelect.value === 'sub_pangkalan') {
                if (categoryFilterWrapper) categoryFilterWrapper.style.display = 'none';
                if (categorySelect) categorySelect.value = '';
                if (subPangkalanFilterWrapper) subPangkalanFilterWrapper.style.display = 'block';
            } else {
                if (categoryFilterWrapper) categoryFilterWrapper.style.display = 'block';
                if (subPangkalanFilterWrapper) subPangkalanFilterWrapper.style.display = 'none';
                if (subPangkalanSelect) subPangkalanSelect.value = '';
            }
        }
    }

    if (sourceSelect) {
        sourceSelect.addEventListener('change', toggleFilters);
    }
    toggleFilters();

    function fetchCustomers(customUrl) {
        const queryParams = new URLSearchParams();
        if (searchInput && searchInput.value.trim()) queryParams.set('search', searchInput.value.trim());
        if (sourceSelect && sourceSelect.value) queryParams.set('source', sourceSelect.value);
        if (categorySelect && categorySelect.value) queryParams.set('category', categorySelect.value);
        if (subPangkalanSelect && subPangkalanSelect.value) queryParams.set('sub_pangkalan_id', subPangkalanSelect.value);

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
            if (sourceSelect) sourceSelect.value = 'pangkalan';
            if (categorySelect) categorySelect.value = '';
            if (subPangkalanSelect) subPangkalanSelect.value = '';
            toggleFilters();
            fetchCustomers();
        });
    }

    bindCustomerPaginationLinks();

    window.openCustomerModal = function(name, category, ktp, phone, address, photoUrl, kkPhotoUrl) {
        document.getElementById('modal-name').textContent = name;
        document.getElementById('modal-ktp').textContent = ktp;
        document.getElementById('modal-phone').textContent = phone;
        document.getElementById('modal-address').textContent = address;
        
        const photoImg = document.getElementById('modal-photo');
        const photoContainer = document.getElementById('modal-photo-container');
        const noPhotoContainer = document.getElementById('modal-no-photo');
        
        if (photoUrl) {
            photoImg.src = photoUrl;
            photoContainer.classList.remove('hidden');
            noPhotoContainer.classList.add('hidden');
        } else {
            photoImg.src = '';
            photoContainer.classList.add('hidden');
            noPhotoContainer.classList.remove('hidden');
        }

        const kkPhotoImg = document.getElementById('modal-kk-photo');
        const kkPhotoContainer = document.getElementById('modal-kk-photo-container');
        const noKkPhotoContainer = document.getElementById('modal-no-kk-photo');

        if (kkPhotoUrl) {
            kkPhotoImg.src = kkPhotoUrl;
            kkPhotoContainer.classList.remove('hidden');
            noKkPhotoContainer.classList.add('hidden');
        } else {
            kkPhotoImg.src = '';
            kkPhotoContainer.classList.add('hidden');
            noKkPhotoContainer.classList.remove('hidden');
        }
        
        document.getElementById('customer-modal').classList.remove('hidden');
    }
    
    window.closeCustomerModal = function() {
        document.getElementById('customer-modal').classList.add('hidden');
    }

    window.closeCustomerModalOutside = function(event) {
        if (event.target.id === 'customer-modal') {
            closeCustomerModal();
        }
    }

    let currentScale = 1;
    const minScale = 0.5;
    const maxScale = 4.0;

    window.openPhotoLightbox = function(imgElementId) {
        const imgEl = document.getElementById(imgElementId);
        const photoSrc = imgEl ? imgEl.src : '';
        if (!photoSrc) return;
        
        currentScale = 1.0;
        const img = document.getElementById('lightbox-img');
        img.src = photoSrc;
        img.style.transform = `scale(${currentScale})`;
        
        updateZoomText();
        document.getElementById('photo-lightbox').classList.remove('hidden');
    }

    window.closePhotoLightbox = function() {
        document.getElementById('photo-lightbox').classList.add('hidden');
        document.getElementById('lightbox-img').src = '';
    }

    window.adjustZoom = function(factor) {
        const img = document.getElementById('lightbox-img');
        if (!img) return;

        let targetScale = currentScale + factor;
        if (targetScale < minScale) targetScale = minScale;
        if (targetScale > maxScale) targetScale = maxScale;

        currentScale = targetScale;
        img.style.transform = `scale(${currentScale})`;
        updateZoomText();
    }

    window.resetZoom = function() {
        currentScale = 1.0;
        const img = document.getElementById('lightbox-img');
        if (img) {
            img.style.transform = `scale(${currentScale})`;
        }
        updateZoomText();
    }

    function updateZoomText() {
        const textEl = document.getElementById('zoom-percentage');
        if (textEl) {
            textEl.textContent = `${Math.round(currentScale * 100)}%`;
        }
    }

    const imgEl = document.getElementById('lightbox-img');
    if (imgEl) {
        imgEl.addEventListener('wheel', function(e) {
            e.preventDefault();
            const direction = e.deltaY < 0 ? 0.25 : -0.25;
            adjustZoom(direction);
        }, { passive: false });

        let initialTouchDistance = 0;
        let initialScale = 1.0;
        let lastTapTime = 0;
        let tapCount = 0;

        function getTouchDistance(e) {
            return Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
        }

        imgEl.addEventListener('touchstart', function(e) {
            if (e.touches.length === 2) {
                initialTouchDistance = getTouchDistance(e);
                initialScale = currentScale;
            } else if (e.touches.length === 1) {
                const currentTime = new Date().getTime();
                const tapLength = currentTime - lastTapTime;
                
                if (tapLength < 300) {
                    tapCount++;
                } else {
                    tapCount = 1;
                }
                
                lastTapTime = currentTime;

                if (tapCount === 3) {
                    e.preventDefault();
                    if (currentScale < 2.0) {
                        currentScale = 2.5;
                    } else {
                        currentScale = 1.0;
                    }
                    imgEl.style.transform = `scale(${currentScale})`;
                    updateZoomText();
                    tapCount = 0;
                }
            }
        }, { passive: false });

        imgEl.addEventListener('touchmove', function(e) {
            if (e.touches.length === 2 && initialTouchDistance > 0) {
                e.preventDefault();
                const currentDistance = getTouchDistance(e);
                const factor = currentDistance / initialTouchDistance;
                
                let targetScale = initialScale * factor;
                if (targetScale < minScale) targetScale = minScale;
                if (targetScale > maxScale) targetScale = maxScale;

                currentScale = targetScale;
                imgEl.style.transform = `scale(${currentScale})`;
                updateZoomText();
            }
        }, { passive: false });

        imgEl.addEventListener('touchend', function(e) {
            if (e.touches.length < 2) {
                initialTouchDistance = 0;
            }
        });
    }
});
</script>

<!-- Modal Detail Pelanggan -->
<div id="customer-modal" onclick="closeCustomerModalOutside(event)" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl w-11/12 md:w-full max-w-2xl border border-slate-100 shadow-xl flex flex-col max-h-[85vh] md:max-h-[90vh] overflow-hidden relative">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-extrabold text-slate-800 text-base md:text-lg tracking-tight">Detail Pelanggan (Hanya Lihat)</h3>
            <button type="button" onclick="closeCustomerModal()" class="text-slate-400 hover:text-slate-600 transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-4 md:p-6 overflow-y-auto space-y-4 text-xs md:text-sm">
            <div class="flex flex-col sm:flex-row items-center gap-4 p-3 md:p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div class="text-center sm:text-left overflow-hidden w-full">
                    <h4 id="modal-name" class="font-bold text-slate-800 text-base md:text-lg truncate"></h4>
                </div>
            </div>

            <!-- Dokumentasi Foto KTP & KK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Foto KTP -->
                <div class="bg-white p-3 rounded-xl border border-slate-100 flex flex-col items-center">
                    <span class="block text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Foto KTP</span>
                    <div id="modal-photo-container" class="w-full h-32 rounded-xl overflow-hidden border border-slate-200 shadow-sm flex items-center justify-center bg-slate-50 hidden">
                        <img id="modal-photo" src="" alt="Foto KTP" class="max-w-full max-h-full object-contain cursor-pointer hover:opacity-90 transition duration-200" onclick="openPhotoLightbox('modal-photo')" title="Klik untuk memperbesar foto KTP">
                    </div>
                    <div id="modal-no-photo" class="w-full h-32 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 text-xs text-center p-2 font-medium border border-slate-200/50">
                        Tidak ada foto KTP
                    </div>
                </div>

                <!-- Foto KK -->
                <div class="bg-white p-3 rounded-xl border border-slate-100 flex flex-col items-center">
                    <span class="block text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Foto Kartu Keluarga (KK)</span>
                    <div id="modal-kk-photo-container" class="w-full h-32 rounded-xl overflow-hidden border border-slate-200 shadow-sm flex items-center justify-center bg-slate-50 hidden">
                        <img id="modal-kk-photo" src="" alt="Foto KK" class="max-w-full max-h-full object-contain cursor-pointer hover:opacity-90 transition duration-200" onclick="openPhotoLightbox('modal-kk-photo')" title="Klik untuk memperbesar foto KK">
                    </div>
                    <div id="modal-no-kk-photo" class="w-full h-32 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 text-xs text-center p-2 font-medium border border-slate-200/50">
                        Tidak ada foto KK
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 pt-2">
                <div class="bg-white p-3 rounded-xl border border-slate-100">
                    <span class="block text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">NIK (KTP)</span>
                    <span id="modal-ktp" class="font-mono font-bold text-slate-800 text-sm md:text-base"></span>
                </div>

                <div class="bg-white p-3 rounded-xl border border-slate-100">
                    <span class="block text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nomor Telepon</span>
                    <span id="modal-phone" class="font-medium text-slate-700"></span>
                </div>

                <div class="bg-white p-3 rounded-xl border border-slate-100 md:col-span-2">
                    <div>
                        <span class="block text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Alamat Domisili</span>
                        <span id="modal-address" class="text-slate-600 block mt-0.5 leading-relaxed font-medium"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Zoom Foto -->
<div id="photo-lightbox" onclick="closePhotoLightbox()" style="z-index: 100;" class="fixed inset-0 bg-slate-950/85 flex flex-col items-center justify-center p-4 hidden backdrop-blur-xs select-none">
    <button type="button" onclick="closePhotoLightbox()" class="absolute top-4 right-4 text-white/80 hover:text-white transition p-2 hover:bg-white/10 rounded-full cursor-pointer focus:outline-none">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    <div class="max-w-[90vw] max-h-[75vh] relative flex items-center justify-center overflow-hidden mb-6" onclick="event.stopPropagation()">
        <img id="lightbox-img" src="" alt="Zoom Foto Pelanggan" class="max-w-full max-h-[75vh] rounded-xl object-contain shadow-2xl border border-white/10" style="transition: transform 0.15s ease-out; transform-origin: center;">
    </div>
    
    <!-- Zoom Controls -->
    <div class="flex items-center gap-4 bg-transparent text-white/85" onclick="event.stopPropagation()">
        <button type="button" onclick="adjustZoom(-0.25)" class="p-2 hover:bg-white/10 rounded-full transition cursor-pointer hover:text-white" title="Perkecil (Zoom Out)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
            </svg>
        </button>
        <button type="button" onclick="resetZoom()" class="px-3 py-1.5 hover:bg-white/10 rounded-lg transition text-xs font-semibold uppercase tracking-wider cursor-pointer border border-white/20 hover:text-white" title="Reset Zoom">
            <span id="zoom-percentage">100%</span>
        </button>
        <button type="button" onclick="adjustZoom(0.25)" class="p-2 hover:bg-white/10 rounded-full transition cursor-pointer hover:text-white" title="Perbesar (Zoom In)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
    </div>
</div>
@endsection
