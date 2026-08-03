@extends('layouts.admin')

@section('title', 'Detail Pelanggan - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition gap-1 mb-2 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Pelanggan
            </a>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Detail Pelanggan</h2>
            <p class="text-slate-500 text-sm mt-1">Rincian identitas dan riwayat transaksi pembelian langsung tabung gas LPG.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center justify-center gap-2 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold px-4 py-2.5 rounded-xl border border-amber-200/50 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Ubah Profil
            </a>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl">
        <!-- Sisi Kiri: Profil Card & Foto KTP -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profil Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                <div class="border-b border-slate-100 pb-4 pt-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                        @if($customer->category === 'rumah_tangga') bg-blue-50 text-blue-700 border border-blue-100
                        @elseif($customer->category === 'usaha_mikro') bg-purple-50 text-purple-700 border border-purple-100
                        @elseif($customer->category === 'pengecer') bg-indigo-50 text-indigo-700 border border-indigo-100
                        @elseif($customer->category === 'konsumen_umum') bg-emerald-50 text-emerald-700 border border-emerald-100
                        @else bg-slate-50 text-slate-700 border border-slate-100 @endif uppercase tracking-wider mb-2.5">
                        @if($customer->category === 'rumah_tangga') 🏠
                        @elseif($customer->category === 'usaha_mikro') 🏪
                        @elseif($customer->category === 'pengecer') 🏢
                        @elseif($customer->category === 'konsumen_umum') 🏢
                        @else ❓ @endif
                        {{ $customer->category_label }}
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">{{ $customer->name }}</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Terdaftar sejak {{ $customer->created_at->format('d M Y') }}</p>
                </div>

                <div class="space-y-4 text-sm font-semibold text-slate-700">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">NIK (KTP)</span>
                        <span class="font-mono text-base font-bold text-slate-800">{{ $customer->ktp }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nomor Telepon</span>
                        <span class="text-slate-600">{{ $customer->phone ?? 'Tidak ada nomor telepon' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Alamat Domisili</span>
                        <span class="text-slate-600 block mt-0.5 leading-relaxed font-medium">{{ $customer->address ?? 'Tidak ada alamat' }}</span>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Foto KTP -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider pt-2">Dokumentasi Foto KTP (Klik untuk memperbesar)</h4>
                @if($customer->photo)
                <div class="bg-slate-50 border border-slate-200/50 rounded-xl overflow-hidden shadow-sm flex items-center justify-center p-2">
                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="Foto KTP {{ $customer->name }}" class="w-full max-h-56 object-contain rounded-lg ktp-trigger-img cursor-zoom-in hover:scale-[1.02] active:scale-95 transition-all duration-300">
                </div>
                @else
                <div class="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-xs text-slate-400 font-semibold italic">Tidak ada foto KTP terlampir</p>
                </div>
                @endif
            </div>

            @if($customer->category !== 'konsumen_umum')
            <!-- Dokumentasi Foto KK -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider pt-2">Dokumentasi Foto KK (Klik untuk memperbesar)</h4>
                @if($customer->kk_photo)
                <div class="bg-slate-50 border border-slate-200/50 rounded-xl overflow-hidden shadow-sm flex items-center justify-center p-2">
                    <img src="{{ asset('storage/' . $customer->kk_photo) }}" alt="Foto KK {{ $customer->name }}" class="w-full max-h-56 object-contain rounded-lg kk-trigger-img cursor-zoom-in hover:scale-[1.02] active:scale-95 transition-all duration-300">
                </div>
                @else
                <div class="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-xs text-slate-400 font-semibold italic">Tidak ada foto KK terlampir</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sisi Kanan: Riwayat Transaksi -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between pt-6">
                    <h3 class="font-bold text-slate-800 text-lg">Riwayat Pembelian Langsung</h3>
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold">{{ $sales->total() }} Transaksi</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                                <th class="px-6 py-4 text-center w-16">No</th>
                                <th class="px-6 py-4">Tanggal Transaksi</th>
                                <th class="px-6 py-4 text-center">Tipe Tabung</th>
                                <th class="px-6 py-4 text-center">Jumlah</th>
                                <th class="px-6 py-4">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($sales as $index => $sale)
                            <tr class="hover:bg-slate-50/30 transition">
                                <td class="px-6 py-4 text-center text-slate-400 font-semibold">
                                    {{ $sales->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $sale->transaction_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100/50 rounded-full text-xs font-bold font-mono">
                                        {{ $sale->tabung_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-800 text-base">
                                    {{ $sale->quantity }} Tabung
                                </td>
                                <td class="px-6 py-4 text-slate-400 font-normal italic">
                                    {{ $sale->notes ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-semibold">
                                    Belum ada catatan pembelian langsung untuk pelanggan ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sales->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $sales->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Zoom Foto KTP / KK -->
    <div id="ktp-zoom-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/80 backdrop-blur-sm transition-all duration-300 opacity-0">
        <button type="button" id="close-zoom-modal" class="absolute top-4 right-4 text-white hover:text-slate-300 transition cursor-pointer p-2.5 bg-slate-900/50 rounded-full" title="Tutup">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="max-w-4xl max-h-[85vh] p-2 flex items-center justify-center">
            <img id="modal-zoomed-img" src="" alt="Foto Zoom" class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl border border-white/10 transform scale-95 transition-transform duration-300">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ktpImg = document.querySelector('.ktp-trigger-img');
            const kkImg = document.querySelector('.kk-trigger-img');
            const modal = document.getElementById('ktp-zoom-modal');
            const modalImg = document.getElementById('modal-zoomed-img');
            const closeModalBtn = document.getElementById('close-zoom-modal');

            if (modal) {
                const openZoom = (src) => {
                    modalImg.src = src;
                    modal.classList.remove('hidden');
                    // Force reflow
                    modal.offsetHeight;
                    modal.classList.add('opacity-100');
                    modalImg.classList.remove('scale-95');
                    modalImg.classList.add('scale-100');
                };

                if (ktpImg) {
                    ktpImg.addEventListener('click', () => openZoom(ktpImg.src));
                }
                if (kkImg) {
                    kkImg.addEventListener('click', () => openZoom(kkImg.src));
                }

                const closeModal = () => {
                    modal.classList.remove('opacity-100');
                    modalImg.classList.remove('scale-100');
                    modalImg.classList.add('scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modalImg.src = '';
                    }, 300);
                };

                closeModalBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            }
        });
    </script>
</div>
@endsection
