@extends('layouts.sub-pangkalan')

@section('title', 'Riwayat Transaksi - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Transaksi</h2>
            <p class="text-slate-500 text-sm mt-1">Daftar lengkap seluruh transaksi penjualan pelanggan.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <span id="total-count-badge" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold border border-slate-200">
                Total: {{ $distributions->total() }} Transaksi
            </span>
            @if($distributions->total() > 0)
            <form action="{{ route('sub-pangkalan.history.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh riwayat transaksi? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-md shadow-rose-500/10 border-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Kosongkan Riwayat</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Premium Filter Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <form id="history-filter-form" method="GET" action="{{ route('sub-pangkalan.history') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
            <!-- Pencarian (Pelanggan/Catatan) -->
            <div class="w-full">
                <label for="search" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pencarian</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Pelanggan, NIK, Catatan..."
                        class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 placeholder-slate-400 font-semibold h-[42px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </div>
                </div>
            </div>

            <!-- Bulan -->
            <div class="w-full">
                <label for="month" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
                <select name="month" id="month" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 font-semibold h-[42px]">
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

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full">
                <button type="submit" class="flex-1 justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition cursor-pointer flex items-center justify-center h-[42px] shadow-md shadow-blue-500/10">
                    Filter
                </button>
                <button type="button" id="btn-reset-history" class="flex-1 justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl text-sm transition flex items-center justify-center h-[42px] cursor-pointer">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div id="history-table-wrapper" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @include('sub-pangkalan.partials.history-table-list')
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const historyForm = document.getElementById('history-filter-form');
    const btnReset = document.getElementById('btn-reset-history');
    const totalBadge = document.getElementById('total-count-badge');

    function fetchHistory(customUrl) {
        let fetchUrl;
        if (customUrl) {
            fetchUrl = customUrl;
        } else if (historyForm) {
            const formData = new FormData(historyForm);
            const queryParams = new URLSearchParams();
            for (const [key, value] of formData.entries()) {
                if (value && value.trim()) {
                    queryParams.set(key, value.trim());
                }
            }
            const queryString = queryParams.toString();
            fetchUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
        } else {
            fetchUrl = window.location.pathname;
        }

        window.history.pushState({ path: fetchUrl }, '', fetchUrl);

        const tableWrapper = document.getElementById('history-table-wrapper');
        const tableContainer = document.getElementById('history-table-container');

        if (tableContainer) {
            tableContainer.style.opacity = '0.3';
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
                const newEl = doc.getElementById('history-table-wrapper');
                return { html: newEl ? newEl.innerHTML : html };
            });
        })
        .then(data => {
            if (data && data.html && tableWrapper) {
                tableWrapper.innerHTML = data.html;
                bindHistoryPaginationLinks();
            }
            if (data && data.total !== undefined && totalBadge) {
                totalBadge.textContent = `Total: ${data.total} Transaksi`;
            }
            const updatedContainer = document.getElementById('history-table-container');
            if (updatedContainer) {
                updatedContainer.style.opacity = '1';
            }
        })
        .catch(err => {
            console.error('Gagal memuat riwayat transaksi:', err);
            const updatedContainer = document.getElementById('history-table-container');
            if (updatedContainer) {
                updatedContainer.style.opacity = '1';
            }
        });
    }

    function bindHistoryPaginationLinks() {
        const container = document.getElementById('history-table-wrapper');
        if (!container) return;
        const links = container.querySelectorAll('.pagination a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageUrl = this.getAttribute('href');
                if (pageUrl) {
                    fetchHistory(pageUrl);
                    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    if (historyForm) {
        historyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchHistory();
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', function(e) {
            e.preventDefault();
            if (historyForm) {
                const elements = historyForm.querySelectorAll('input, select');
                elements.forEach(el => {
                    el.value = '';
                });
            }
            fetchHistory();
        });
    }

    bindHistoryPaginationLinks();
});
</script>
@endsection
