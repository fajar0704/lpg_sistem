<div id="refill-table-container" class="transition-opacity duration-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max text-sm text-left">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                    <th class="px-5 py-3.5 text-left">Tanggal</th>
                    <th class="px-5 py-3.5 text-left">Tipe Tabung</th>
                    <th class="px-5 py-3.5 text-center">Jumlah</th>
                    <th class="px-5 py-3.5 text-left">Catatan</th>
                    <th class="px-5 py-3.5 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                @forelse($recentRefills as $refill)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5 text-slate-500">{{ $refill->transaction_date->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 font-bold text-slate-800">Tabung {{ $refill->tabung_type }}</td>
                    <td class="px-5 py-3.5 text-center font-extrabold text-slate-900">{{ $refill->quantity }} Tabung</td>
                    <td class="px-5 py-3.5 text-slate-500 italic max-w-xs truncate" title="{{ $refill->notes }}">
                        {{ $refill->notes ?? '-' }}
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100/70 text-emerald-800">
                            Selesai
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-slate-400 font-semibold">
                        <div class="max-w-xs mx-auto">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-slate-600 font-bold text-sm">Belum ada penerimaan tabung</p>
                            <p class="text-xs text-slate-400 mt-1">Riwayat penerimaan tabung isi dari pangkalan akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($recentRefills->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
        {{ $recentRefills->appends(request()->except('refill_page'))->links() }}
    </div>
    @endif
</div>
