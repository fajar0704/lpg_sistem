@extends('layouts.admin')

@section('title', 'Laporan - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan Sistem
            </h2>
            <p class="text-slate-500 text-sm mt-1">Unduh laporan aktivitas penjualan, stok, dan data pelanggan.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all text-sm print:hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print
            </button>
            <a id="btn-export-pdf" href="{{ route('admin.reports.export-pdf', request()->all()) }}" class="inline-flex items-center justify-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all text-sm print:hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a,2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </a>
            <a id="btn-export-excel" href="{{ route('admin.reports.export-excel', request()->all()) }}" class="inline-flex items-center justify-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all text-sm print:hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 print:hidden mb-6">
        <form action="{{ route('admin.reports') }}" method="GET" class="flex flex-wrap xl:flex-nowrap items-end gap-3" id="reportForm">
            
            <!-- Jenis Laporan -->
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jenis Laporan</label>
                <select name="report_type" id="report_type" class="w-full px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium transition-all h-[42px]">
                    <option value="penjualan" {{ $reportType === 'penjualan' ? 'selected' : '' }}>Laporan Penjualan LPG</option>
                    <option value="pelanggan" {{ $reportType === 'pelanggan' ? 'selected' : '' }}>Laporan Pelanggan Pangkalan (Utama)</option>
                    <option value="pelanggan_sub_pangkalan" {{ $reportType === 'pelanggan_sub_pangkalan' ? 'selected' : '' }}>Laporan Pelanggan Sub Pangkalan (Pengecer)</option>
                </select>
            </div>

            <!-- Kategori Pelanggan -->
            <div class="flex-1 min-w-[140px]" id="filter-category-wrapper">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kategori Pelanggan</label>
                <select name="customer_type" id="customer_type" class="w-full px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium transition-all h-[42px]">
                    <option value="">Semua Kategori</option>
                    <option value="rumah_tangga" {{ request('customer_type') === 'rumah_tangga' ? 'selected' : '' }}>Rumah Tangga</option>
                    <option value="usaha_mikro" {{ request('customer_type') === 'usaha_mikro' ? 'selected' : '' }}>Usaha Mikro</option>
                    <option value="konsumen_umum" {{ request('customer_type') === 'konsumen_umum' ? 'selected' : '' }}>Konsumen Umum (Non-Subsidi)</option>
                    <option value="pengecer" {{ request('customer_type') === 'pengecer' ? 'selected' : '' }}>Sub Pangkalan (Pengecer)</option>
                </select>
            </div>

            <!-- Filter Pengecer -->
            <div class="flex-1 min-w-[140px]" id="filter-sp-wrapper">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Filter Pengecer</label>
                <select name="sub_pangkalan_id" id="sub_pangkalan_id" class="w-full px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium transition-all h-[42px]">
                    <option value="">Semua Pengecer</option>
                    @foreach($subPangkalansList as $sp)
                        <option value="{{ $sp->id }}" {{ request('sub_pangkalan_id') == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Periode Filter -->
            <div class="flex-1 min-w-[130px]" id="filter-period-wrapper">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Periode Filter</label>
                <select name="period" id="period" class="w-full px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium transition-all h-[42px]">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            <!-- Pilih Tanggal/Bulan/Tahun -->
            <div class="flex-1 min-w-[140px]" id="filter-date-wrapper">
                <div id="filter-daily" style="display: {{ $period === 'daily' ? 'block' : 'none' }}">
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Pilih Tanggal</label>
                    <input type="date" name="date" id="input-date" value="{{ request('date', now()->format('Y-m-d')) }}" class="w-full px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none h-[42px]">
                </div>
                <div id="filter-monthly" style="display: {{ $period === 'monthly' ? 'block' : 'none' }}">
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Pilih Bulan</label>
                    <input type="month" name="month" id="input-month" value="{{ request('month', now()->format('Y-m')) }}" class="w-full px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none h-[42px]">
                </div>
                <div id="filter-yearly" style="display: {{ $period === 'yearly' ? 'block' : 'none' }}">
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Pilih Tahun</label>
                    <input type="number" name="year" id="input-year" min="2020" max="2100" value="{{ request('year', now()->format('Y')) }}" class="w-full px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none h-[42px]">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0" id="filter-action-wrapper">
                <button type="submit" class="px-5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-200 text-sm flex items-center justify-center shadow-md shadow-blue-500/10 h-[42px] cursor-pointer border-0">
                    Filter
                </button>
                <button type="button" id="btn-reset" class="px-5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition duration-200 text-sm flex items-center justify-center h-[42px] cursor-pointer border-0">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            adjustFilterUI();
            bindPaginationLinks();

            // Event Listeners for interactive updates
            document.getElementById('report_type').addEventListener('change', function() {
                adjustFilterUI();
            });

            document.getElementById('customer_type').addEventListener('change', function() {
                adjustFilterUI();
            });

            document.getElementById('period').addEventListener('change', function() {
                adjustFilterUI();
            });

            document.getElementById('reportForm').addEventListener('submit', function(e) {
                e.preventDefault();
                applyReportFilter();
            });

            document.getElementById('btn-reset').addEventListener('click', function() {
                document.getElementById('report_type').value = 'penjualan';
                document.getElementById('customer_type').value = '';
                document.getElementById('sub_pangkalan_id').value = '';
                document.getElementById('period').value = 'monthly';
                
                const todayStr = '{{ now()->format("Y-m-d") }}';
                const thisMonthStr = '{{ now()->format("Y-m") }}';
                const thisYearStr = '{{ now()->format("Y") }}';
                
                document.getElementById('input-date').value = todayStr;
                document.getElementById('input-month').value = thisMonthStr;
                document.getElementById('input-year').value = thisYearStr;

                adjustFilterUI();
                applyReportFilter();
            });
        });

        function adjustFilterUI() {
            const reportType = document.getElementById('report_type').value;
            const customerType = document.getElementById('customer_type').value;
            const period = document.getElementById('period').value;

            const wrapperCategory = document.getElementById('filter-category-wrapper');
            const wrapperSp = document.getElementById('filter-sp-wrapper');
            const wrapperPeriod = document.getElementById('filter-period-wrapper');
            const wrapperDate = document.getElementById('filter-date-wrapper');
            const wrapperAction = document.getElementById('filter-action-wrapper');

            // Show/hide based on Report Type
            if (reportType === 'penjualan') {
                wrapperCategory.style.display = 'block';
                wrapperPeriod.style.display = 'block';
                wrapperDate.style.display = 'block';
                wrapperAction.style.display = 'flex';

                if (customerType === 'pengecer' || customerType === '') {
                    wrapperSp.style.display = 'block';
                } else {
                    wrapperSp.style.display = 'none';
                }
            } else if (reportType === 'stok') {
                wrapperCategory.style.display = 'none';
                wrapperSp.style.display = 'none';
                wrapperPeriod.style.display = 'block';
                wrapperDate.style.display = 'block';
                wrapperAction.style.display = 'flex';
            } else if (reportType === 'pelanggan') {
                wrapperCategory.style.display = 'block';
                wrapperSp.style.display = 'none';
                wrapperPeriod.style.display = 'none';
                wrapperDate.style.display = 'none';
                wrapperAction.style.display = 'flex';
            }

            // Toggle daily/monthly/yearly input display inside date wrapper
            document.getElementById('filter-daily').style.display = period === 'daily' ? 'block' : 'none';
            document.getElementById('filter-monthly').style.display = period === 'monthly' ? 'block' : 'none';
            document.getElementById('filter-yearly').style.display = period === 'yearly' ? 'block' : 'none';
        }

        function applyReportFilter() {
            const form = document.getElementById('reportForm');
            const formData = new FormData(form);
            const queryParams = new URLSearchParams(formData);
            
            const reportType = document.getElementById('report_type').value;
            if (reportType !== 'penjualan' && reportType !== 'pelanggan') {
                queryParams.delete('customer_type');
                queryParams.delete('sub_pangkalan_id');
            } else if (reportType === 'pelanggan') {
                queryParams.delete('sub_pangkalan_id');
                queryParams.delete('period');
                queryParams.delete('date');
                queryParams.delete('month');
                queryParams.delete('year');
            } else {
                const customerType = document.getElementById('customer_type').value;
                if (customerType !== 'pengecer' && customerType !== '') {
                    queryParams.delete('sub_pangkalan_id');
                }
            }

            const url = `${window.location.pathname}?${queryParams.toString()}`;
            window.history.pushState({ path: url }, '', url);

            const container = document.getElementById('report-content-container');
            if (container) {
                container.style.transition = 'opacity 0.25s ease-in-out, transform 0.25s ease-in-out';
                container.style.opacity = '0.3';
                container.style.transform = 'translateY(6px)';
            }

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newContent = doc.getElementById('report-content-container');
                    if (newContent && container) {
                        container.innerHTML = newContent.innerHTML;
                    }

                    const pdfBtn = document.getElementById('btn-export-pdf');
                    const excelBtn = document.getElementById('btn-export-excel');
                    
                    const newPdfBtn = doc.getElementById('btn-export-pdf');
                    const newExcelBtn = doc.getElementById('btn-export-excel');
                    
                    if (pdfBtn && newPdfBtn) pdfBtn.href = newPdfBtn.getAttribute('href');
                    if (excelBtn && newExcelBtn) excelBtn.href = newExcelBtn.getAttribute('href');

                    if (container) {
                        setTimeout(() => {
                            container.style.opacity = '1';
                            container.style.transform = 'translateY(0)';
                        }, 50);
                    }
                    
                    bindPaginationLinks();
                })
                .catch(err => {
                    console.error(err);
                    if (container) {
                        container.style.opacity = '1';
                        container.style.transform = 'translateY(0)';
                    }
                });
        }

        function bindPaginationLinks() {
            const container = document.getElementById('report-content-container');
            if (!container) return;
            const paginationLinks = container.querySelectorAll('nav a, .pagination a, nav[aria-label="Pagination Navigation"] a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    if (!url || url === '#') return;

                    window.history.pushState({ path: url }, '', url);
                    
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

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.getElementById('report-content-container');
                            if (newContent) {
                                container.innerHTML = newContent.innerHTML;
                            }

                            const pdfBtn = document.getElementById('btn-export-pdf');
                            const excelBtn = document.getElementById('btn-export-excel');
                            const newPdfBtn = doc.getElementById('btn-export-pdf');
                            const newExcelBtn = doc.getElementById('btn-export-excel');
                            
                            if (pdfBtn && newPdfBtn) pdfBtn.href = newPdfBtn.getAttribute('href');
                            if (excelBtn && newExcelBtn) excelBtn.href = newExcelBtn.getAttribute('href');

                            setTimeout(() => {
                                container.style.opacity = '1';
                                container.style.transform = 'translateY(0)';
                            }, 50);

                            bindPaginationLinks();
                        })
                        .catch(err => {
                            console.error(err);
                            container.style.opacity = '1';
                            container.style.transform = 'translateY(0)';
                        });
                });
            });
        }
    </script>

    <!-- Report Content -->
    <div id="report-content-container" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 print:shadow-none print:border-none print:p-0">
        
        <!-- Print Header (Hanya muncul saat di-print) -->
        <div class="hidden print:block mb-8 text-center">
            <h1 class="text-2xl font-bold uppercase text-slate-800">
                @if($reportType === 'penjualan')
                    Laporan Penjualan LPG
                @elseif($reportType === 'stok')
                    Laporan Stok LPG
                @elseif($reportType === 'pelanggan')
                    Laporan Data Pelanggan
                @endif
            </h1>
            <p class="text-slate-500 mt-1">
                @if($period === 'daily')
                    Tanggal: {{ $label }}
                @elseif($period === 'monthly')
                    Periode: Bulan {{ $label }}
                @elseif($period === 'yearly')
                    Periode: {{ $label }}
                @endif
            </p>
        </div>

        @if($reportType === 'penjualan')
            <div class="mb-4 flex items-center justify-between print:hidden">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Laporan Penjualan LPG</h3>
                    <p class="text-sm text-slate-500">Periode: {{ $label }}</p>
                </div>
                <div class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-sm font-semibold border border-blue-100">
                    Total: {{ $records->count() }} Transaksi
                </div>
            </div>
            
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Tanggal Transaksi</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Nama Pelanggan</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Jenis LPG</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($paginatedRecords ?? $records as $r)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3 text-sm text-slate-600">{{ $r->transaction_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-sm font-semibold text-slate-800">{{ $r->nama_pembeli ?? ($r->customer ? $r->customer->name : 'Anonim') }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600">
                                @if($r->customer_type === 'rumah_tangga') Rumah Tangga
                                @elseif($r->customer_type === 'usaha_mikro') Usaha Mikro
                                @elseif($r->customer_type === 'pengecer') Sub Pangkalan
                                @else {{ ucfirst($r->customer_type) }} @endif
                            </td>
                            <td class="px-5 py-3 text-sm text-center font-medium">{{ $r->tabung_type }}</td>
                            <td class="px-5 py-3 text-sm text-center font-bold text-blue-600">{{ $r->quantity }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-500 text-sm">Tidak ada transaksi penjualan di periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($paginatedRecords) && $paginatedRecords->hasPages())
            <div class="mt-4 print:hidden">
                {{ $paginatedRecords->links() }}
            </div>
            @endif

        @elseif($reportType === 'stok')
            <div class="mb-6 print:hidden">
                <h3 class="text-lg font-bold text-slate-800">Laporan Stok LPG</h3>
                <p class="text-sm text-slate-500">Periode: {{ $label }}</p>
            </div>

            <!-- Ringkasan Stok -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-500 font-semibold uppercase mb-1">Stok Awal</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stockSummary['stokAwal'] }} <span class="text-sm font-medium text-slate-500">tabung</span></p>
                </div>
                <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                    <p class="text-xs text-emerald-600 font-semibold uppercase mb-1">Total Masuk</p>
                    <p class="text-2xl font-bold text-emerald-700">+{{ $stockSummary['masukTotal'] }} <span class="text-sm font-medium text-emerald-600/70">tabung</span></p>
                    <p class="text-xs text-emerald-600/80 mt-1">(Restok: {{ $stockSummary['masukRestok'] }})</p>
                </div>
                <div class="bg-rose-50 p-4 rounded-xl border border-rose-100">
                    <p class="text-xs text-rose-600 font-semibold uppercase mb-1">Total Keluar</p>
                    <p class="text-2xl font-bold text-rose-700">-{{ $stockSummary['keluarTotal'] }} <span class="text-sm font-medium text-rose-600/70">tabung</span></p>
                    <p class="text-xs text-rose-600/80 mt-1">(Penjualan)</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <p class="text-xs text-blue-600 font-semibold uppercase mb-1">Stok Akhir Periode</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $stockSummary['stokAkhir'] }} <span class="text-sm font-medium text-blue-600/70">tabung</span></p>
                </div>
            </div>

            <!-- Detail Rincian LPG Masuk & Keluar -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Rincian LPG Masuk -->
                <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-sm">
                    <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            Rincian LPG Masuk (Restok)
                        </h4>
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded">
                            {{ $restokDetail->count() }} Data
                        </span>
                    </div>
                    <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400 font-bold uppercase">
                                    <th class="px-4 py-2.5">Tanggal</th>
                                    <th class="px-4 py-2.5">Tipe Tabung</th>
                                    <th class="px-4 py-2.5 text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($restokDetail as $item)
                                <tr class="hover:bg-slate-50/30 transition">
                                    <td class="px-4 py-2.5 text-slate-600 font-semibold">{{ $item->received_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 font-bold">Tabung {{ $item->cylinder_type }}</td>
                                    <td class="px-4 py-2.5 text-center text-slate-900 font-extrabold">+{{ $item->quantity_in }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-slate-400 font-medium">Tidak ada data LPG masuk di periode ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Rincian LPG Keluar -->
                <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-sm">
                    <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            Rincian LPG Keluar (Penjualan)
                        </h4>
                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold rounded">
                            {{ $penjualanDetail->count() }} Data
                        </span>
                    </div>
                    <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400 font-bold uppercase">
                                    <th class="px-4 py-2.5">Tanggal</th>
                                    <th class="px-4 py-2.5">Penerima</th>
                                    <th class="px-4 py-2.5">Kategori</th>
                                    <th class="px-4 py-2.5 text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($penjualanDetail as $item)
                                <tr class="hover:bg-slate-50/30 transition">
                                    <td class="px-4 py-2.5 text-slate-600 font-semibold">{{ $item->transaction_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 font-bold truncate max-w-[120px]" title="{{ $item->nama_pembeli }}">{{ $item->nama_pembeli }}</td>
                                    <td class="px-4 py-2.5 text-slate-500">
                                        @if($item->customer_type === 'rumah_tangga') RT
                                        @elseif($item->customer_type === 'usaha_mikro') Usaha
                                        @elseif($item->customer_type === 'pengecer') Sub Pangkalan
                                        @else {{ ucfirst($item->customer_type) }} @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-center text-rose-600 font-extrabold">-{{ $item->quantity }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400 font-medium">Tidak ada data LPG keluar di periode ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif($reportType === 'pelanggan' || $reportType === 'pelanggan_sub_pangkalan')
            <div class="mb-4 flex items-center justify-between print:hidden">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $reportType === 'pelanggan_sub_pangkalan' ? 'Laporan Data Pelanggan Sub Pangkalan (Pengecer)' : 'Laporan Data Pelanggan Pangkalan (Utama)' }}
                    </h3>
                    <p class="text-sm text-slate-500">Daftar semua pelanggan terdaftar.</p>
                </div>
                <div class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-sm font-semibold border border-blue-100">
                    Total: {{ $records->count() }} Pelanggan
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Terdaftar</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Foto KTP</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Foto KK</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Nomor Kontak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($paginatedRecords ?? $records as $c)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold shadow-sm">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $c->name }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">NIK: {{ $c->ktp }}</p>
                                        @if($reportType === 'pelanggan_sub_pangkalan')
                                            <p class="text-[10px] text-blue-600 font-bold mt-0.5">Pengecer: {{ $c->sub_pangkalan_name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shadow-sm border
                                    @if($c->category === 'Rumah Tangga') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @elseif($c->category === 'Usaha Mikro') bg-purple-50 text-purple-700 border-purple-200
                                    @elseif($c->category === 'Konsumen Umum') bg-indigo-50 text-indigo-700 border-indigo-200
                                    @else bg-amber-50 text-amber-700 border-amber-200 @endif
                                ">
                                    @if($c->category === 'Rumah Tangga')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    @elseif($c->category === 'Usaha Mikro')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    @elseif($c->category === 'Konsumen Umum')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    @endif
                                    {{ $c->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">
                                {{ $c->created_at ? \Carbon\Carbon::parse($c->created_at)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(!empty($c->photo))
                                    <img src="{{ asset('storage/' . $c->photo) }}" alt="Foto KTP" class="w-16 h-10 object-cover rounded-lg border border-slate-200 shadow-xs hover:scale-105 hover:border-blue-500 transition-all duration-200 cursor-zoom-in inline-block" onclick="showReportImageModal('{{ asset('storage/' . $c->photo) }}')">
                                @else
                                    <span class="text-xs text-slate-400 font-medium italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(!empty($c->kk_photo))
                                    <img src="{{ asset('storage/' . $c->kk_photo) }}" alt="Foto KK" class="w-16 h-10 object-cover rounded-lg border border-slate-200 shadow-xs hover:scale-105 hover:border-blue-500 transition-all duration-200 cursor-zoom-in inline-block" onclick="showReportImageModal('{{ asset('storage/' . $c->kk_photo) }}')">
                                @else
                                    <span class="text-xs text-slate-400 font-medium italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-2 text-slate-600 font-medium group-hover:text-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $c->phone }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <p class="text-slate-500 font-medium">Tidak ada data pelanggan yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($paginatedRecords) && $paginatedRecords->hasPages())
            <div class="mt-4 print:hidden">
                {{ $paginatedRecords->links() }}
            </div>
            @endif

        @endif

    </div>
</div>

<!-- Modal Zoom Foto Laporan -->
<div id="report-zoom-modal" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-slate-950/80 backdrop-blur-sm transition-all duration-300 opacity-0">
    <button type="button" onclick="closeReportImageModal()" class="absolute top-4 right-4 text-white hover:text-slate-300 transition cursor-pointer p-2.5 bg-slate-900/50 rounded-full border-0 outline-none" title="Tutup">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    <div class="max-w-4xl max-h-[85vh] p-2 flex items-center justify-center">
        <img id="report-modal-zoomed-img" src="" alt="Foto Zoom" class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl border border-white/10 transform scale-95 transition-transform duration-300">
    </div>
</div>

<script>
    function showReportImageModal(src) {
        const modal = document.getElementById('report-zoom-modal');
        const modalImg = document.getElementById('report-modal-zoomed-img');
        if (modal && modalImg) {
            modalImg.src = src;
            modal.classList.remove('hidden');
            modal.offsetHeight; // Force reflow
            modal.classList.add('opacity-100');
            modalImg.classList.remove('scale-95');
            modalImg.classList.add('scale-100');
        }
    }
    function closeReportImageModal() {
        const modal = document.getElementById('report-zoom-modal');
        const modalImg = document.getElementById('report-modal-zoomed-img');
        if (modal && modalImg) {
            modal.classList.remove('opacity-100');
            modalImg.classList.remove('scale-100');
            modalImg.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modalImg.src = '';
            }, 300);
        }
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReportImageModal();
        }
    });
</script>
@endsection
