<div id="exchange-history-container" class="divide-y divide-slate-100 relative transition-opacity duration-200">
    @forelse($exchanges as $exchange)
    <div class="p-5 space-y-3 hover:bg-slate-50/40 transition">
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-400 font-semibold">{{ $exchange->transaction_date->translatedFormat('d F Y') }}</span>
            @if($exchange->status === 'approved')
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100/50 rounded-full text-[10px] font-bold">
                Disetujui
            </span>
            @elseif($exchange->status === 'pending')
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-100/50 rounded-full text-[10px] font-bold">
                Menunggu
            </span>
            @else
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-100/50 rounded-full text-[10px] font-bold">
                Ditolak
            </span>
            @endif
        </div>

        <div class="flex items-center justify-between">
            <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold border border-slate-200">
                Tabung {{ $exchange->tabung_type }}
            </span>
            <span class="text-sm font-extrabold text-slate-800">
                {{ $exchange->quantity }} Tabung
            </span>
        </div>

        @if($exchange->notes)
        <p class="text-xs text-slate-500 bg-slate-50 p-2 rounded-lg border border-slate-100 italic">
            "{{ $exchange->notes }}"
        </p>
        @endif
    </div>
    @empty
    <div class="p-8 text-center text-slate-400 font-semibold">
        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <p class="text-xs">Belum ada riwayat pengajuan</p>
    </div>
    @endforelse

    @if($exchanges->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $exchanges->appends(request()->except('exchange_page'))->links() }}
    </div>
    @endif
</div>
