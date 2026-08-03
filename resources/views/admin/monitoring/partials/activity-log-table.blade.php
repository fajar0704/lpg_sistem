<div id="activity-log-table-container" class="transition-opacity duration-300 ease-in-out">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                    <th class="px-6 py-4 font-semibold">Sub Pangkalan</th>
                    <th class="px-6 py-4 font-semibold">Jenis Transaksi</th>
                    <th class="px-6 py-4 font-semibold text-center">Tipe</th>
                    <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-center">Catatan</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($distributions as $dist)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                        {{ $dist->transaction_date ? $dist->transaction_date->format('d M Y') : $dist->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                        <div class="text-[11px] text-slate-400 font-normal">{{ $dist->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800">
                        {{ optional($dist->subPangkalan)->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($dist->transaction_type === 'receive')
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                </div>
                                <span class="font-medium text-slate-700">Kirim Stok ke Pengecer</span>
                            </div>
                        @elseif($dist->transaction_type === 'sell')
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                @php
                                    $catLabel = 'Lainnya';
                                    if ($dist->customer_type === 'rumah_tangga') {
                                        $catLabel = 'Rumah Tangga';
                                    } elseif ($dist->customer_type === 'usaha_mikro' || $dist->customer_type === 'usaha') {
                                        $catLabel = 'Usaha Mikro';
                                    } elseif ($dist->customer_type === 'pengecer') {
                                        $catLabel = 'Sub Pangkalan';
                                    } elseif ($dist->customer_type === 'konsumen_umum') {
                                        $catLabel = 'Konsumen Umum';
                                    } else {
                                        $catLabel = ucfirst($dist->customer_type ?? 'Lainnya');
                                    }
                                @endphp
                                <span class="font-medium text-slate-700">Penjualan ({{ $catLabel }})</span>
                            </div>
                        @elseif($dist->transaction_type === 'exchange' || $dist->transaction_type === 'return_kosong')
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                </div>
                                <span class="font-medium text-slate-700">Pengembalian Tabung Kosong</span>
                            </div>
                        @else
                            <span class="font-medium text-slate-700">{{ ucfirst($dist->transaction_type) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 text-slate-600 border border-slate-200/60 shadow-sm">{{ $dist->tabung_type }}</span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-extrabold text-slate-800">
                        {{ $dist->quantity }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($dist->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Selesai
                            </span>
                        @elseif($dist->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Ditolak
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-slate-600 font-medium">
                        {{ $dist->notes ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($dist->status === 'pending' && in_array($dist->transaction_type, ['return_kosong', 'exchange']))
                            <form action="{{ route('admin.sub-pangkalan-transaction.confirm-return', $dist->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition shadow-sm">
                                    Konfirmasi
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-400 mb-3 border border-slate-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-500">Belum ada aktivitas atau penjualan yang tercatat.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($distributions->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $distributions->links() }}
    </div>
    @endif
</div>
