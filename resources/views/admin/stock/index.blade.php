@extends('layouts.admin')

@section('title', 'Manajemen Stok LPG')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Stok Pangkalan LPG
            </h2>
            <p class="text-slate-500 text-sm mt-1">Sistem pencatatan dan pengelolaan arus stok tabung LPG berbasis metode FIFO.</p>
        </div>
        <a href="{{ route('admin.stock.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-5 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Stok Masuk Baru</span>
        </a>
    </div>

    <!-- Keterangan Kelebihan FIFO -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col md:flex-row items-start md:items-center gap-4">
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl"></div>
        <div class="p-3 bg-blue-600 text-white rounded-xl shadow-md shadow-blue-500/10 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="space-y-1.5 flex-1 relative">
            <h4 class="font-bold text-slate-800 text-sm md:text-base">Mengenal Metode First In First Out (FIFO)</h4>
            <p class="text-slate-600 text-xs md:text-sm leading-relaxed">
                Sistem pencatatan pangkalan menerapkan metode **FIFO (First In First Out)** untuk menjamin kelancaran arus keluar-masuk barang. Tabung LPG yang diterima lebih awal dari distributor/agen (batch terlama) akan otomatis dikeluarkan terlebih dahulu saat ada penjualan.
        </div>
    </div>

    <!-- Ringkasan Stok Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @foreach($stocks as $stock)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between relative overflow-hidden">
            <!-- Decorative outline indicator -->
            <div class="absolute top-0 left-0 right-0 h-1.5 
                @if($stock->stok_isi <= 0) bg-gradient-to-r from-rose-500 to-red-600
                @elseif($stock->stok_isi <= $stock->safety_stock) bg-gradient-to-r from-amber-500 to-orange-600
                @else bg-gradient-to-r from-emerald-500 to-teal-600 @endif"></div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <h3 class="font-extrabold text-slate-800 text-lg tracking-tight">{{ $stock->tabung_type }}</h3>
                    @if($stock->stok_isi <= 0)
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-rose-50 text-rose-700 px-2.5 py-1 rounded-full border border-rose-100 animate-pulse">
                        ⚠️ Habis
                    </span>
                    @elseif($stock->stok_isi <= $stock->safety_stock)
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-100">
                        ⚠️ Stok Menipis
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full border border-emerald-100">
                        ✓ Aman
                    </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-emerald-50/40 border border-emerald-100/50 rounded-xl p-3 text-center">
                        <p class="text-slate-500 text-xs font-semibold">Stok Isi</p>
                        <p class="font-extrabold text-emerald-600 text-2xl mt-1">{{ $stock->stok_isi }}</p>
                    </div>
                    <div class="bg-amber-50/40 border border-amber-100/50 rounded-xl p-3 text-center">
                        <p class="text-slate-500 text-xs font-semibold">Stok Kosong</p>
                        <p class="font-extrabold text-amber-500 text-2xl mt-1">{{ $stock->stok_kosong }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">Safety Stock: <strong class="text-slate-600 font-semibold">{{ $stock->safety_stock }}</strong></span>
                <a href="{{ route('admin.stock.edit', $stock) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                    Ubah Stok & Safety →
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Tab Navigation -->
    @if(!$stocks->isEmpty())
    <div class="bg-white p-2 rounded-2xl border border-slate-100 shadow-sm flex flex-wrap gap-2 max-w-md">
        @foreach($stocks as $index => $stock)
        <button type="button" onclick="window.switchStockTab('{{ $stock->tabung_type }}')" id="tab-btn-{{ $stock->tabung_type }}"
            class="flex-1 text-center py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer {{ $index === 0 ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
            Tabung {{ $stock->tabung_type }}
        </button>
        @endforeach
    </div>
    @endif

    <!-- FIFO per Tipe Tabung Detail -->
    @foreach($stocks as $index => $stock)
    <div id="stock-detail-{{ $stock->tabung_type }}" class="stock-tab-content {{ $index === 0 ? '' : 'hidden' }} bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Section Title Bar -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2.5">
            <div class="p-2 bg-slate-900 text-white rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="font-bold text-slate-800 tracking-tight">FIFO Tabung {{ $stock->tabung_type }}</h3>
        </div>

        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Daftar Batch Masuk -->
            <div class="flex flex-col text-slate-800">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-1.5 h-3 bg-blue-600 rounded-full"></span>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Daftar Batch Masuk</h4>
                </div>
                <div class="overflow-x-auto border border-slate-100 rounded-t-xl">
                    <table class="w-full min-w-max text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                                <th class="px-4 py-3.5 text-left">Batch</th>
                                <th class="px-4 py-3.5 text-left">Tanggal Masuk</th>
                                <th class="px-4 py-3.5 text-center">Stok Awal</th>
                                <th class="px-4 py-3.5 text-center">Sisa</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-4 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($batches->get($stock->tabung_type, collect()) as $i => $batch)
                            @php $isHabis = $batch->isHabis(); @endphp
                            <tr class="batch-row-{{ $stock->tabung_type }} {{ $isHabis ? 'bg-slate-50/50 text-slate-400' : 'hover:bg-slate-50/30' }} transition">
                                <td class="px-4 py-3 font-mono font-bold text-slate-500">B{{ $i + 1 }}</td>
                                <td class="px-4 py-3">{{ $batch->received_date->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-center">{{ $batch->quantity_in }}</td>
                                <td class="px-4 py-3 text-center font-extrabold {{ $isHabis ? 'text-slate-400' : 'text-emerald-600' }}">{{ $batch->quantity_remaining }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($batch->status === 'Habis')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold">
                                        Habis
                                    </span>
                                    @elseif($batch->status === 'Aktif')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100/50 rounded-full text-xs font-semibold animate-pulse">
                                        Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100/50 rounded-full text-xs font-semibold">
                                        Baru
                                    </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        @if($isHabis)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-slate-50 border border-slate-100 text-slate-400 px-2 py-0.5 rounded-full" title="Batch sudah habis dan terkunci">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Terkunci
                                        </span>
                                        @else
                                        <!-- Edit -->
                                        <a href="{{ route('admin.stock.batch.edit', $batch) }}" class="text-blue-600 hover:text-blue-800 transition" title="Ubah Batch">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('admin.stock.batch.destroy', $batch) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Batch ini? Penghapusan akan membatalkan input penerimaan stok, mengurangi stok isi pangkalan, dan mengembalikan stok kosong.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 transition cursor-pointer" title="Hapus Batch">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-400 font-medium">
                                    Belum ada batch masuk
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Batch -->
                @if($batches->get($stock->tabung_type, collect())->count() > 10)
                <div class="flex items-center justify-between px-4 py-3 bg-white border border-slate-100 border-t-0 rounded-b-xl sm:px-6 shadow-sm">
                    <div class="flex justify-between flex-1 sm:hidden">
                        <button type="button" onclick="prevPage('batches-{{ $stock->tabung_type }}')" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 cursor-pointer">
                            Sebelumnya
                        </button>
                        <button type="button" onclick="nextPage('batches-{{ $stock->tabung_type }}')" class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 cursor-pointer">
                            Selanjutnya
                        </button>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-xs text-slate-500 font-semibold">
                                Menampilkan <span class="font-bold text-slate-700" id="start-batches-{{ $stock->tabung_type }}">1</span> - <span class="font-bold text-slate-700" id="end-batches-{{ $stock->tabung_type }}">10</span> dari <span class="font-bold text-slate-700" id="total-batches-{{ $stock->tabung_type }}">{{ $batches->get($stock->tabung_type, collect())->count() }}</span> data
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex -space-x-px rounded-xl shadow-sm bg-white" aria-label="Pagination" id="nav-batches-{{ $stock->tabung_type }}">
                                <!-- Diisi via JS -->
                            </nav>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Riwayat Pengeluaran -->
            <div class="flex flex-col text-slate-800">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-3 bg-red-600 rounded-full"></span>
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Riwayat Pengeluaran (FIFO Tracking)</h4>
                    </div>
                    @if(!$outflows->get($stock->tabung_type, collect())->isEmpty())
                    <form action="{{ route('admin.stock.outflow.clear', $stock->tabung_type) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh riwayat pengeluaran FIFO untuk tabung {{ $stock->tabung_type }} ini?');" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 transition flex items-center gap-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Riwayat
                        </button>
                    </form>
                    @endif
                </div>
                <div class="overflow-x-auto border border-slate-100 rounded-t-xl">
                    <table class="w-full min-w-max text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                                <th class="px-4 py-3.5 text-left">Tanggal</th>
                                <th class="px-4 py-3.5 text-center">Keluar</th>
                                <th class="px-4 py-3.5 text-center">Dari Batch</th>
                                <th class="px-4 py-3.5 text-left">Sumber Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @php
                                $batchList = $batches->get($stock->tabung_type, collect())->values();
                            @endphp
                            @forelse($outflows->get($stock->tabung_type, collect()) as $out)
                            @php
                                $batchIndex = $batchList->search(fn($b) => $b->id === $out->stock_batch_id);
                                $batchLabel = $batchIndex !== false ? 'B' . ($batchIndex + 1) : '-';
                            @endphp
                            <tr class="outflow-row-{{ $stock->tabung_type }} hover:bg-slate-50/30 transition">
                                <td class="px-4 py-3 text-slate-500 font-medium">{{ $out->transaction_date->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-center font-extrabold text-rose-600">{{ $out->quantity }}</td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-blue-600 bg-blue-50/20">{{ $batchLabel }}</td>
                                <td class="px-4 py-3">
                                    @if($out->source === 'penjualan_langsung')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100/50 rounded-full text-xs font-semibold">
                                        🛒 Jual Langsung
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100/50 rounded-full text-xs font-semibold">
                                        📦 Distribusi Sub
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-400 font-medium">
                                    Belum ada pengeluaran
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Outflow -->
                @if($outflows->get($stock->tabung_type, collect())->count() > 10)
                <div class="flex items-center justify-between px-4 py-3 bg-white border border-slate-100 border-t-0 rounded-b-xl sm:px-6 shadow-sm">
                    <div class="flex justify-between flex-1 sm:hidden">
                        <button type="button" onclick="prevPage('outflows-{{ $stock->tabung_type }}')" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 cursor-pointer">
                            Sebelumnya
                        </button>
                        <button type="button" onclick="nextPage('outflows-{{ $stock->tabung_type }}')" class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 cursor-pointer">
                            Selanjutnya
                        </button>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-xs text-slate-500 font-semibold">
                                Menampilkan <span class="font-bold text-slate-700" id="start-outflows-{{ $stock->tabung_type }}">1</span> - <span class="font-bold text-slate-700" id="end-outflows-{{ $stock->tabung_type }}">10</span> dari <span class="font-bold text-slate-700" id="total-outflows-{{ $stock->tabung_type }}">{{ $outflows->get($stock->tabung_type, collect())->count() }}</span> data
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex -space-x-px rounded-xl shadow-sm bg-white" aria-label="Pagination" id="nav-outflows-{{ $stock->tabung_type }}">
                                <!-- Diisi via JS -->
                            </nav>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <!-- Empty State -->
    @if($stocks->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center text-slate-500 max-w-lg mx-auto">
        <svg class="w-16 h-16 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0H4"></path>
        </svg>
        <p class="font-bold text-slate-700 text-lg">Data Stok Pangkalan Kosong</p>
        <p class="text-sm text-slate-400 mt-1">Anda belum mencatat stok tabung apa pun. Mulailah dengan mendaftarkan stok tabung masuk perdana.</p>
        <div class="mt-5">
            <a href="{{ route('admin.stock.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white font-bold px-4 py-2 rounded-xl text-sm shadow hover:bg-blue-700 transition">
                + Tambah Stok Masuk
            </a>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const itemsPerPage = 10;
        const tables = {};

        // Inisialisasi status paginasi untuk setiap tabel yang ada di halaman
        document.querySelectorAll('[id^="nav-"]').forEach(navEl => {
            const tableKey = navEl.id.replace('nav-', ''); // e.g. 'batches-3kg' or 'outflows-5kg'
            const tabungType = tableKey.split('-')[1]; // e.g. '3kg'
            const isBatches = tableKey.startsWith('batches');
            const rows = document.querySelectorAll(isBatches ? `.batch-row-${tabungType}` : `.outflow-row-${tabungType}`);
            const totalItems = rows.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            tables[tableKey] = {
                currentPage: 1,
                rows: rows,
                totalItems: totalItems,
                totalPages: totalPages,
                navEl: navEl,
                startEl: document.getElementById(`start-${tableKey}`),
                endEl: document.getElementById(`end-${tableKey}`)
            };

            // Tampilkan halaman pertama
            renderTable(tableKey);
        });

        function renderTable(key) {
            const t = tables[key];
            if (!t) return;

            const startIdx = (t.currentPage - 1) * itemsPerPage;
            const endIdx = Math.min(startIdx + itemsPerPage, t.totalItems);

            // Sembunyikan/tampilkan baris
            t.rows.forEach((row, index) => {
                if (index >= startIdx && index < endIdx) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });

            // Update info teks
            if (t.startEl) t.startEl.textContent = t.totalItems === 0 ? 0 : startIdx + 1;
            if (t.endEl) t.endEl.textContent = endIdx;

            // Buat tombol navigasi
            generateNavButtons(key);
        }

        function generateNavButtons(key) {
            const t = tables[key];
            if (!t || !t.navEl) return;

            let html = '';

            // Tombol Sebelumnya
            const isPrevDisabled = t.currentPage === 1;
            html += `
                <button type="button" ${isPrevDisabled ? 'disabled' : ''} onclick="window.setPage('${key}', ${t.currentPage - 1})" 
                    class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-slate-200 bg-white text-xs font-bold ${isPrevDisabled ? 'text-slate-300 bg-slate-50/50 cursor-not-allowed' : 'text-slate-500 hover:bg-slate-50 cursor-pointer'} transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            `;

            // Tombol Halaman (1, 2, 3, dst.)
            const maxPageButtons = 5;
            let startPage = Math.max(1, t.currentPage - 2);
            let endPage = Math.min(t.totalPages, startPage + maxPageButtons - 1);
            if (endPage - startPage < maxPageButtons - 1) {
                startPage = Math.max(1, endPage - maxPageButtons + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const isActive = t.currentPage === i;
                html += `
                    <button type="button" onclick="window.setPage('${key}', ${i})" 
                        class="relative inline-flex items-center px-3.5 py-2 border ${isActive ? 'z-10 bg-blue-600 border-blue-600 text-white font-extrabold shadow-sm shadow-blue-500/20' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50 font-bold'} text-xs transition cursor-pointer">
                        ${i}
                    </button>
                `;
            }

            // Tombol Selanjutnya
            const isNextDisabled = t.currentPage === t.totalPages;
            html += `
                <button type="button" ${isNextDisabled ? 'disabled' : ''} onclick="window.setPage('${key}', ${t.currentPage + 1})" 
                    class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-slate-200 bg-white text-xs font-bold ${isNextDisabled ? 'text-slate-300 bg-slate-50/50 cursor-not-allowed' : 'text-slate-500 hover:bg-slate-50 cursor-pointer'} transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            `;

            t.navEl.innerHTML = html;
        }

        // Ekspos ke global scope
        window.setPage = function(key, pageNum) {
            const t = tables[key];
            if (!t) return;

            if (pageNum < 1 || pageNum > t.totalPages) return;
            t.currentPage = pageNum;
            renderTable(key);
        };

        window.prevPage = function(key) {
            const t = tables[key];
            if (t && t.currentPage > 1) {
                window.setPage(key, t.currentPage - 1);
            }
        };

        window.nextPage = function(key) {
            const t = tables[key];
            if (t && t.currentPage < t.totalPages) {
                window.setPage(key, t.currentPage + 1);
            }
        };

        window.switchStockTab = function(type) {
            // Sembunyikan semua tab content
            document.querySelectorAll('.stock-tab-content').forEach(el => {
                el.classList.add('hidden');
            });
            // Tampilkan yang terpilih
            document.getElementById(`stock-detail-${type}`).classList.remove('hidden');

            // Reset semua tombol tab ke style non-aktif
            document.querySelectorAll('[id^="tab-btn-"]').forEach(btn => {
                btn.className = "flex-1 text-center py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer text-slate-500 hover:text-slate-800 hover:bg-slate-50";
            });
            // Set tombol aktif ke style aktif
            document.getElementById(`tab-btn-${type}`).className = "flex-1 text-center py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer bg-blue-600 text-white shadow-md shadow-blue-500/20";
        };
    });
</script>
@endsection
