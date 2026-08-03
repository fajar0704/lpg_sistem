<div id="history-table-container" class="transition-opacity duration-300 ease-in-out">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max text-sm text-left">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                    <th class="px-5 py-4 text-center w-16">No</th>
                    <th class="px-5 py-4">Tanggal</th>
                    <th class="px-5 py-4">Jenis Transaksi</th>
                    <th class="px-5 py-4">Pelanggan</th>
                    <th class="px-5 py-4 text-center">Tipe Tabung</th>
                    <th class="px-5 py-4 text-center">Jumlah</th>
                    <th class="px-5 py-4 text-center">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                @forelse($distributions as $index => $dist)
                <tr class="hover:bg-slate-50/30 transition">
                    <td class="px-5 py-4 text-center text-slate-400 font-semibold">
                        {{ $distributions->firstItem() + $index }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-bold text-slate-800">
                            {{ $dist->transaction_date->translatedFormat('d F Y') }}
                        </div>
                        <div class="text-xs text-slate-400 font-medium mt-1">
                            {{ $dist->created_at ? $dist->created_at->timezone('Asia/Jakarta')->format('H:i') . ' WIB' : '-' }}
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @if($dist->transaction_type === 'receive')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100/50">
                                📥 Terima LPG
                            </span>
                        @elseif($dist->transaction_type === 'sell')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100/50">
                                🛒 Penjualan
                            </span>
                        @elseif($dist->transaction_type === 'exchange')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100/50">
                                🔄 Tukar Kosong
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-100/50">
                                {{ $dist->type }}
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($dist->customer)
                            <a href="{{ route('sub-pangkalan.customers.show', $dist->customer) }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline block">
                                {{ $dist->customer->name }}
                            </a>
                            <span class="text-xs text-slate-400 font-mono font-bold mt-0.5 block">NIK: {{ $dist->customer->ktp }}</span>
                        @elseif($dist->transaction_type === 'exchange')
                            <span class="text-amber-600 font-bold block">Pangkalan</span>
                            <span class="text-xs text-slate-400 font-semibold mt-0.5 block">Tukar Kosong</span>
                        @elseif($dist->transaction_type === 'receive')
                            <span class="text-blue-600 font-bold block">Pangkalan</span>
                            <span class="text-xs text-slate-400 font-semibold mt-0.5 block">Terima LPG</span>
                        @else
                            <span class="text-slate-400 italic">Bukan Penjualan Pelanggan</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center font-bold text-slate-700">
                        {{ $dist->tabung_type }}
                    </td>
                    <td class="px-5 py-4 text-center font-extrabold text-slate-800 text-base">
                        {{ $dist->quantity }} Tabung
                    </td>
                    <td class="px-5 py-4 text-center text-slate-600 font-medium">
                        {{ $dist->notes ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-400 font-semibold">
                        Belum ada riwayat transaksi yang cocok dengan penyaringan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($distributions->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        {{ $distributions->links() }}
    </div>
    @endif
</div>
