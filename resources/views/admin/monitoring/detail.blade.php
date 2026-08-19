@extends('layouts.admin')

@section('title', 'Detail Monitoring Pengecer - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.monitoring.index') }}" class="p-2 bg-slate-50 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="text-2xl font-extrabold text-slate-800">Detail Monitoring Pengecer</h2>
            </div>
            <p class="text-slate-500 text-sm md:ml-11">Memonitor stok dan aktivitas penjualan dari <strong class="text-slate-700">{{ $subPangkalan->name }}</strong></p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-2.5 flex items-center gap-3">
            @php
                $detailProfilePhoto = optional($subPangkalan->user)->photo;
            @endphp
            <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-blue-200 shadow-sm flex items-center justify-center bg-blue-100">
                @if($detailProfilePhoto)
                    <img src="{{ asset('storage/' . $detailProfilePhoto) }}" alt="{{ $subPangkalan->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-lg font-bold text-blue-600">{{ substr($subPangkalan->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800">{{ $subPangkalan->name }}</p>
                <p class="text-xs text-slate-500">{{ $subPangkalan->address ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 1. Informasi Stok LPG -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-800">Informasi Stok LPG</h3>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center">
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-600/20 relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="relative flex justify-between items-end mb-6">
                        <div>
                            <p class="text-indigo-100 font-medium text-sm mb-1">Total Kapasitas Terpantau</p>
                            <h4 class="text-3xl font-extrabold">{{ $subPangkalan->stok_isi + $subPangkalan->stok_kosong }} <span class="text-lg font-normal text-indigo-200">Tabung</span></h4>
                        </div>
                        <div class="px-3 py-1.5 bg-white/20 backdrop-blur-md rounded-lg border border-white/20 text-xs font-semibold">
                            Jenis LPG: 3kg
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 rounded-xl p-4 border border-white/10">
                            <p class="text-indigo-200 text-xs font-medium uppercase tracking-wider mb-1">Stok Isi</p>
                            <p class="text-2xl font-bold">{{ $subPangkalan->stok_isi }}</p>
                        </div>
                        <div class="bg-black/10 rounded-xl p-4 border border-black/5">
                            <p class="text-indigo-200 text-xs font-medium uppercase tracking-wider mb-1">Stok Kosong</p>
                            <p class="text-2xl font-bold">{{ $subPangkalan->stok_kosong }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Ringkasan Penjualan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-800">Ringkasan Penjualan</h3>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <span class="font-semibold text-slate-700">Total Transaksi</span>
                        </div>
                        <span class="text-2xl font-extrabold text-slate-800">{{ $totalTransaksi }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <span class="font-semibold text-slate-700">Total Tabung Terjual</span>
                        </div>
                        <span class="text-2xl font-extrabold text-slate-800">{{ $totalTabungTerjual }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-100 relative overflow-hidden">
                        <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl"></div>
                        <div class="flex items-center gap-3 relative">
                            <div class="w-10 h-10 rounded-full bg-blue-600 shadow-lg shadow-blue-600/30 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="font-bold text-blue-900 block">Penjualan Hari Ini</span>
                                <span class="text-xs text-blue-600 font-medium">{{ now()->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                        <span class="text-3xl font-extrabold text-blue-700 relative">{{ $penjualanHariIni }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Riwayat Transaksi Penjualan -->
    <div id="penjualan-container" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-slate-800">Riwayat Penjualan (Pengecer ke Pelanggan)</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Transaksi yang dilakukan oleh pengecer kepada konsumen akhir</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4 text-center">Jenis LPG</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-center">Detail Pelanggan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayatPenjualan as $rp)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                            {{ $rp->transaction_date->format('d/m/Y') }}
                            <div class="text-[11px] text-slate-400 font-medium">{{ $rp->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800">
                            @if($rp->customer)
                                <span class="font-bold block">{{ $rp->customer->name }}</span>
                                <span class="text-xs text-slate-400 font-mono font-bold mt-0.5 block">NIK: {{ $rp->customer->ktp }}</span>
                            @else
                                <span class="font-semibold">
                                    @if($rp->customer_type === 'rumah_tangga')
                                        🏠 Rumah Tangga
                                    @elseif($rp->customer_type === 'usaha_mikro')
                                        🏪 Usaha Mikro
                                    @elseif($rp->customer_type === 'pengecer')
                                        🏢 Sub Pangkalan
                                    @elseif($rp->customer_type === 'konsumen_umum')
                                        🏢 Konsumen Umum
                                    @else
                                        ❓ {{ ucfirst($rp->customer_type ?? 'Lainnya') }}
                                    @endif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 text-slate-600 border border-slate-200/60 shadow-sm">{{ $rp->tabung_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-extrabold text-blue-600">
                            {{ $rp->quantity }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($rp->customer)
                                <button type="button" 
                                    onclick="openCustomerModal('{{ addslashes($rp->customer->name) }}', '{{ $rp->customer->category_label }}', '{{ $rp->customer->ktp }}', '{{ $rp->customer->phone ?? '-' }}', '{{ addslashes($rp->customer->address ?? '-') }}', '{{ $rp->customer->photo ? asset('storage/' . $rp->customer->photo) : '' }}', '{{ $rp->customer->kk_photo ? asset('storage/' . $rp->customer->kk_photo) : '' }}')"
                                    style="background-color: #2563eb !important; color: #ffffff !important;"
                                    class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-blue-700 transition cursor-pointer shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span>Lihat Detail</span>
                                </button>
                            @else
                                <span class="text-slate-400 font-medium italic text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-400 mb-3 border border-slate-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-slate-500 font-medium">Belum ada riwayat penjualan dari pengecer ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayatPenjualan->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $riwayatPenjualan->links() }}
        </div>
        @endif
    </div>

    <!-- 4. Riwayat Pembelian dari Pangkalan -->
    <div id="pembelian-container" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-slate-800">Riwayat Pembelian dari Pangkalan</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Pasokan LPG yang dikirimkan Admin ke Pengecer ini</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Tanggal Pembelian</th>
                        <th class="px-6 py-4 text-center">Jenis LPG</th>
                        <th class="px-6 py-4 text-center">Jumlah Tabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayatPembelian as $rb)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                            {{ $rb->transaction_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 text-slate-600 border border-slate-200/60 shadow-sm">{{ $rb->tabung_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-extrabold text-emerald-600">
                            + {{ $rb->quantity }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-400 mb-3 border border-slate-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-slate-500 font-medium">Belum ada riwayat pembelian dari pangkalan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayatPembelian->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $riwayatPembelian->links() }}
        </div>
        @endif
    </div>
</div>

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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></svg>
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
        <span class="text-xs font-mono font-bold select-none min-w-[3.5rem] text-center" id="zoom-percentage">100%</span>
        <button type="button" onclick="adjustZoom(0.25)" class="p-2 hover:bg-white/10 rounded-full transition cursor-pointer hover:text-white" title="Perbesar (Zoom In)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
        <div class="w-px h-5 bg-white/20"></div>
        <button type="button" onclick="resetZoom()" class="p-2 hover:bg-white/10 rounded-full transition cursor-pointer hover:text-white" title="Atur Ulang (Reset)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m-3-3v12"/>
            </svg>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupAjaxPaginationContainer(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            function bindLinks() {
                const links = container.querySelectorAll('nav a, .pagination a, nav[aria-label="Pagination Navigation"] a, nav[role="navigation"] a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.getAttribute('href');
                        if (!url || url === '#') return;

                        container.style.transition = 'opacity 0.25s ease-in-out, transform 0.25s ease-in-out';
                        container.style.opacity = '0.3';
                        container.style.transform = 'translateY(6px)';

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
                            const newContainer = doc.getElementById(containerId);

                            if (newContainer) {
                                container.innerHTML = newContainer.innerHTML;
                            }

                            setTimeout(() => {
                                container.style.opacity = '1';
                                container.style.transform = 'translateY(0)';
                            }, 50);

                            window.history.pushState({ path: url }, '', url);
                            bindLinks();
                        })
                        .catch(err => {
                            console.error(err);
                            container.style.opacity = '1';
                            container.style.transform = 'translateY(0)';
                        });
                    });
                });
            }

            bindLinks();
        }

        setupAjaxPaginationContainer('penjualan-container');
        setupAjaxPaginationContainer('pembelian-container');
    });

    function openCustomerModal(name, category, ktp, phone, address, photoUrl, kkPhotoUrl) {
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
    
    function closeCustomerModal() {
        document.getElementById('customer-modal').classList.add('hidden');
    }

    function closeCustomerModalOutside(event) {
        if (event.target.id === 'customer-modal') {
            closeCustomerModal();
        }
    }

    let currentScale = 1;
    const minScale = 0.5;
    const maxScale = 4.0;

    function openPhotoLightbox(imgElementId) {
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

    function closePhotoLightbox() {
        document.getElementById('photo-lightbox').classList.add('hidden');
        document.getElementById('lightbox-img').src = '';
    }

    function adjustZoom(factor) {
        const img = document.getElementById('lightbox-img');
        if (!img) return;

        let targetScale = currentScale + factor;
        if (targetScale < minScale) targetScale = minScale;
        if (targetScale > maxScale) targetScale = maxScale;

        currentScale = targetScale;
        img.style.transform = `scale(${currentScale})`;
        updateZoomText();
    }

    function resetZoom() {
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

    // Bind mouse wheel and touch gestures (pinch-to-zoom & triple-tap) for zoom support
    document.addEventListener('DOMContentLoaded', function() {
        const imgEl = document.getElementById('lightbox-img');
        if (imgEl) {
            // Mouse Wheel Zoom for Desktop
            imgEl.addEventListener('wheel', function(e) {
                e.preventDefault();
                const direction = e.deltaY < 0 ? 0.25 : -0.25;
                adjustZoom(direction);
            }, { passive: false });

            // Touch Gestures for Mobile
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
@endsection
