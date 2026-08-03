@extends('layouts.sub-pangkalan')

@section('title', 'Penukaran Tabung Kosong - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('sub-pangkalan.dashboard') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition gap-1 mb-2 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Penukaran Tabung Kosong</h2>
            <p class="text-slate-500 text-sm mt-1">Ajukan penukaran tabung kosong Anda ke pangkalan untuk diisi ulang.</p>
        </div>
    </div>

    <!-- Info Stok Cards -->
    <div class="grid grid-cols-2 gap-4 max-w-xl">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 border border-blue-200/60 rounded-2xl p-5 relative overflow-hidden group shadow-sm">
            <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            <p class="text-[10px] font-bold text-blue-600/85 uppercase tracking-wider mb-1">Stok Tabung Isi</p>
            <p class="text-3xl font-black text-blue-700">{{ $subPangkalan->stok_isi }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100/50 border border-orange-200/60 rounded-2xl p-5 relative overflow-hidden group shadow-sm">
            <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-orange-500/10 rounded-full blur-xl group-hover:bg-orange-500/20 transition-all duration-500"></div>
            <p class="text-[10px] font-bold text-orange-600/85 uppercase tracking-wider mb-1">Stok Tabung Kosong</p>
            <p class="text-3xl font-black text-orange-600">{{ $subPangkalan->stok_kosong }}</p>
        </div>
    </div>

    @if($subPangkalan->stok_kosong == 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl shadow-sm max-w-xl flex items-start gap-3">
        <div class="shrink-0 text-red-500 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
            <h3 class="text-sm font-bold text-red-800">Tidak ada stok kosong</h3>
            <p class="text-sm text-red-700 mt-1">Anda tidak memiliki tabung kosong yang bisa ditukarkan saat ini.</p>
        </div>
    </div>
    @endif

    <!-- Main Two Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Left: Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 to-amber-500"></div>
                
                <form action="{{ route('sub-pangkalan.exchange.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
                    @csrf

                    <div>
                        <label for="tabung_type" class="block text-slate-700 text-xs font-semibold mb-2">Tipe Tabung <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="tabung_type" id="tabung_type" required
                                class="w-full pl-4 pr-10 py-3 border border-slate-200 rounded-xl appearance-none bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition font-semibold text-slate-700 @error('tabung_type') border-red-500 ring-red-500/20 @enderror">
                                <option value="3kg" selected>Tabung 3kg</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('tabung_type')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="quantity" class="block text-slate-700 text-xs font-semibold mb-2">Jumlah Tabung Kosong <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="quantity" onwheel="this.blur()" name="quantity" value="{{ old('quantity') }}" min="1" max="{{ $subPangkalan->stok_kosong }}" required placeholder="Contoh: 5"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition font-medium text-slate-700 @error('quantity') border-red-500 ring-red-500/20 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm font-semibold">Tabung</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Batas maksimal pengajuan: {{ $subPangkalan->stok_kosong }} tabung kosong</p>
                        @error('quantity')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="transaction_date" class="block text-slate-700 text-xs font-semibold mb-2">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                        <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition font-medium text-slate-700">
                    </div>

                    <div>
                        <label for="notes" class="block text-slate-700 text-xs font-semibold mb-2">Catatan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Tulis keterangan tambahan..."
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition font-medium text-slate-700 resize-none">{{ old('notes') }}</textarea>
                    </div>

                    <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 flex gap-3 text-blue-800 text-xs">
                        <div class="shrink-0 text-blue-500 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="leading-relaxed">
                            Pengajuan penukaran tabung kosong harus diverifikasi oleh Pangkalan. Setelah disetujui, <span class="font-bold">stok kosong Anda akan dikurangi</span> secara otomatis.
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('sub-pangkalan.dashboard') }}" class="bg-white hover:bg-slate-100 text-slate-700 font-bold px-5 py-3 rounded-xl transition text-sm border border-slate-200">
                            Batal
                        </a>
                        <button type="submit" {{ $subPangkalan->stok_kosong == 0 ? 'disabled' : '' }}
                            class="bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold px-6 py-3.5 rounded-xl shadow-md shadow-orange-500/10 transition flex items-center gap-2 cursor-pointer text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: History List -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-base">Riwayat Penukaran Terbaru</h3>
                    <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Daftar pengajuan penukaran tabung</p>
                    
                    <!-- Sidebar Filters -->
                    <form id="exchange-filter-form" action="{{ route('sub-pangkalan.exchange.create') }}" method="GET" class="mt-4 space-y-2">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="status" class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Status</label>
                                <select name="status" id="status"
                                    class="w-full bg-white border border-slate-200 text-slate-700 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition font-semibold">
                                    <option value="">Semua</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                </select>
                            </div>
                            <div>
                                <label for="month" class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Bulan</label>
                                <select name="month" id="month"
                                    class="w-full bg-white border border-slate-200 text-slate-700 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition font-semibold">
                                    <option value="">Semua</option>
                                    @foreach([
                                        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                                        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                                    ] as $num => $name)
                                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="reset-filter-container" class="pt-1 {{ (request()->filled('status') || request()->filled('month')) ? '' : 'hidden' }}">
                            <button type="button" id="reset-filter-btn" class="inline-flex items-center justify-center w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-1.5 rounded-lg text-[10px] transition cursor-pointer">
                                Reset Filter
                            </button>
                        </div>
                    </form>
                </div>

                <div id="exchange-history-wrapper">
                    @include('sub-pangkalan.partials.exchange-history-list')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function filterExchangeHistory(url) {
            const targetUrl = url || "{{ route('sub-pangkalan.exchange.create') }}";
            const status = $('#status').val();
            const month = $('#month').val();

            if (status || month) {
                $('#reset-filter-container').removeClass('hidden');
            } else {
                $('#reset-filter-container').addClass('hidden');
            }

            $('#exchange-history-container').css('opacity', '0.4');

            $.ajax({
                url: targetUrl,
                type: 'GET',
                data: {
                    status: status,
                    month: month
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response && response.html) {
                        $('#exchange-history-wrapper').html(response.html);
                    }
                },
                error: function(err) {
                    console.error('Gagal memuat riwayat penukaran:', err);
                },
                complete: function() {
                    $('#exchange-history-container').css('opacity', '1');
                }
            });
        }

        $('#status, #month').on('change', function() {
            filterExchangeHistory();
        });

        $(document).on('click', '#reset-filter-btn', function(e) {
            e.preventDefault();
            $('#status').val('');
            $('#month').val('');
            filterExchangeHistory();
        });

        $(document).on('click', '#exchange-history-wrapper .pagination a', function(e) {
            e.preventDefault();
            const pageUrl = $(this).attr('href');
            if (pageUrl) {
                filterExchangeHistory(pageUrl);
            }
        });
    });
</script>
@endsection
