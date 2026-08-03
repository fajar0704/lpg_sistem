@extends('layouts.admin')

@section('title', 'Terima Stok Baru - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div>
        <a href="{{ route('admin.stock.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Stok Pangkalan
        </a>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Terima Stok dari Agen Pertamina</h2>
        <p class="text-slate-500 text-sm mt-1">Setiap penerimaan stok isi baru akan dicatat sebagai <strong>batch baru</strong> dalam antrean FIFO.</p>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Terima Stok -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
                <form id="stock-form" action="{{ route('admin.stock.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Tipe Tabung -->
                    <div>
                        <label for="tabung_type" class="block text-slate-700 text-sm font-semibold mb-2 flex items-center gap-1">
                            Tipe Tabung <span class="text-rose-500">*</span>
                        </label>
                        <select name="tabung_type" id="tabung_type" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 bg-white font-medium @error('tabung_type') border-rose-500 focus:ring-rose-500/20 @enderror">
                            <option value="">Pilih Tipe Tabung</option>
                            <option value="3kg" {{ old('tabung_type') === '3kg' ? 'selected' : '' }}>🟢 3 kg (Subsidi)</option>
                            <option value="5kg" {{ old('tabung_type') === '5kg' ? 'selected' : '' }}>🔴 5 kg (Bright Gas)</option>
                            <option value="12kg" {{ old('tabung_type') === '12kg' ? 'selected' : '' }}>🔵 12 kg (Non-Subsidi)</option>
                        </select>
                        @error('tabung_type')
                            <p class="text-rose-600 text-xs mt-1.5 font-medium flex items-center gap-1">
                                <span class="w-1 h-1 rounded-full bg-rose-600"></span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Jumlah Diterima -->
                    <div>
                        <label for="input_jumlah" class="block text-slate-700 text-sm font-semibold mb-2">
                            Jumlah Tabung Diterima <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" onwheel="this.blur()" name="initial_stock" id="input_jumlah" value="{{ old('initial_stock') }}" min="1" required
                                placeholder="Masukkan jumlah tabung (Contoh: 120)"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('initial_stock') border-rose-500 focus:ring-rose-500/20 @enderror">
                        </div>
                        @error('initial_stock')
                            <p class="text-rose-600 text-xs mt-1.5 font-medium flex items-center gap-1">
                                <span class="w-1 h-1 rounded-full bg-rose-600"></span>{{ $message }}
                            </p>
                        @enderror
                        
                        <!-- Dynamic Info Box -->
                        <div id="hint_kosong" class="mt-3 p-3 bg-amber-50/50 border border-amber-200 text-amber-800 text-xs rounded-xl flex items-start gap-2.5 hidden">
                            <div class="p-1 bg-amber-500 text-white rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold">Otomatis Tukar Agen:</span> Stok kosong pangkalan Anda akan berkurang sebanyak <strong id="hint_jumlah" class="underline">0</strong> tabung sebagai penukar tabung isi baru ini.
                            </div>
                        </div>

                        <!-- Capacity Limit Warning -->
                        <div id="error_limit" class="mt-3 p-3 bg-rose-50/50 border border-rose-200 text-rose-800 text-xs rounded-xl flex items-start gap-2.5 hidden">
                            <div class="p-1 bg-rose-500 text-white rounded-lg shrink-0">
                                ⚠️
                            </div>
                            <div>
                                <span class="font-bold">Kapasitas Penuh:</span> Jumlah baru (<strong id="limit_total">0</strong>) melebihi kapasitas maksimum (<strong id="limit_max">0</strong>). Input akan ditolak.
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Terima & Safety Stock -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="received_date" class="block text-slate-700 text-sm font-semibold mb-2">
                                Tanggal Terima <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="received_date" id="received_date" value="{{ old('received_date', date('Y-m-d')) }}" required
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium">
                        </div>

                        <div>
                            <label for="safety_stock" class="block text-slate-700 text-sm font-semibold mb-2">
                                Safety Stock (Batas Minimum) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" onwheel="this.blur()" name="safety_stock" id="safety_stock" value="{{ old('safety_stock', 10) }}" min="0" required
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium">
                            <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Peringatan stok akan muncul jika stok isi ≤ nilai ini.</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                            Simpan & Buat Batch
                        </button>
                        <a href="{{ route('admin.stock.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-3 rounded-xl transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Kapasitas & Sisa Stok -->
            @if($currentStocks->isNotEmpty())
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <div class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <p class="font-extrabold text-slate-800 text-sm tracking-tight">Kapasitas & Sisa Stok</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-slate-400 font-bold border-b border-slate-100 text-left">
                                <th class="pb-2">Tipe</th>
                                <th class="pb-2 text-center">Isi</th>
                                <th class="pb-2 text-center">Kosong</th>
                                <th class="pb-2 text-center">Maks</th>
                                <th class="pb-2 text-center">Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                            @foreach($currentStocks as $s)
                                @php $sisa = $s->sisaKapasitas(); @endphp
                                <tr>
                                    <td class="py-2.5 font-bold">{{ $s->tabung_type }}</td>
                                    <td class="py-2.5 text-center text-emerald-600">{{ $s->stok_isi }}</td>
                                    <td class="py-2.5 text-center text-amber-500">{{ $s->stok_kosong }}</td>
                                    <td class="py-2.5 text-center text-slate-400">{{ $s->max_stock ?: '-' }}</td>
                                    <td class="py-2.5 text-center font-extrabold">
                                        @if($sisa <= 0)
                                            <span class="text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded text-[10px] font-bold">PENUH</span>
                                        @else
                                            <span class="{{ $sisa <= 5 ? 'text-amber-600' : 'text-blue-600' }}">{{ $sisa }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-[10px] text-amber-600 font-medium">💡 Sistem akan otomatis mengurangi stok kosong saat Anda menyimpan penerimaan stok isi.</p>
            </div>
            @endif

            <!-- Panduan Kapasitas Pangkalan -->
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 space-y-3 text-blue-800">
                <div class="flex items-center gap-2 pb-2 border-b border-blue-100/60">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-bold text-sm tracking-tight">Panduan Kapasitas Pangkalan</p>
                </div>
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-blue-600/70 font-bold border-b border-blue-100/60 text-left">
                            <th class="pb-1.5">Tipe</th>
                            <th class="pb-1.5 text-center">Maks Stok</th>
                            <th class="pb-1.5 text-center">Safety Stock</th>
                        </tr>
                    </thead>
                    <tbody class="font-semibold text-blue-900/80">
                        <tr class="border-b border-blue-100/40">
                            <td class="py-2">3 kg</td>
                            <td class="py-2 text-center font-bold text-blue-700">{{ $currentStocks->firstWhere('tabung_type', '3kg')->max_stock ?? 120 }} tabung</td>
                            <td class="py-2 text-center">{{ $currentStocks->firstWhere('tabung_type', '3kg')->safety_stock ?? 20 }}</td>
                        </tr>
                        <tr class="border-b border-blue-100/40">
                            <td class="py-2">5 kg</td>
                            <td class="py-2 text-center font-bold text-blue-700">{{ $currentStocks->firstWhere('tabung_type', '5kg')->max_stock ?? 10 }} tabung</td>
                            <td class="py-2 text-center">{{ $currentStocks->firstWhere('tabung_type', '5kg')->safety_stock ?? 5 }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">12 kg</td>
                            <td class="py-2 text-center font-bold text-blue-700">{{ $currentStocks->firstWhere('tabung_type', '12kg')->max_stock ?? 20 }} tabung</td>
                            <td class="py-2 text-center">{{ $currentStocks->firstWhere('tabung_type', '12kg')->safety_stock ?? 10 }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="pt-2 text-[10px] font-semibold text-blue-700 flex justify-between items-center">
                    <span>Total Kapasitas Pangkalan:</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs font-bold">{{ $currentStocks->sum('max_stock') }} tabung</span>
                </div>
            </div>

            <!-- Aturan Batas Stok -->
            <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-5 text-amber-800 space-y-2">
                <p class="font-bold text-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Aturan Batas Stok
                </p>
                <ul class="list-disc list-inside space-y-1.5 text-xs text-amber-800/80 font-medium">
                    <li>Stok tidak boleh melebihi kapasitas maksimum masing-masing tipe.</li>
                    <li>Sistem otomatis membatalkan input jika jumlah baru melebihi limit.</li>
                    <li>Misal: batas 3kg adalah 120, input ditolak jika stok aktif sudah 120.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const stokKosongMap = @json($stokKosongMap);
        const stokIsiMap    = @json($stokIsiMap);
        const maxStockMap   = @json($maxStockMap);

        const selectTipe    = document.querySelector('select[name="tabung_type"]');
        const inputJumlah   = document.getElementById('input_jumlah');
        const hintBox       = document.getElementById('hint_kosong');
        const hintJumlah    = document.getElementById('hint_jumlah');
        const errorLimit    = document.getElementById('error_limit');
        const limitTotal    = document.getElementById('limit_total');
        const limitMax      = document.getElementById('limit_max');
        const stockForm     = document.getElementById('stock-form');

        function updateHint() {
            const tipe    = selectTipe.value;
            const jumlah  = parseInt(inputJumlah.value) || 0;
            const kosong  = stokKosongMap[tipe] ?? 0;
            const kembali = Math.min(jumlah, kosong);

            if (tipe && jumlah > 0 && kosong > 0) {
                hintJumlah.textContent = kembali;
                hintBox.classList.remove('hidden');
            } else {
                hintBox.classList.add('hidden');
            }

            // Check capacity limit
            if (tipe && jumlah > 0) {
                const currentIsi = parseInt(stokIsiMap[tipe]) || 0;
                const maxStock   = parseInt(maxStockMap[tipe]) || 0;
                const total      = currentIsi + jumlah;

                if (total > maxStock) {
                    limitTotal.textContent = total;
                    limitMax.textContent = maxStock;
                    errorLimit.classList.remove('hidden');
                } else {
                    errorLimit.classList.add('hidden');
                }
            } else {
                errorLimit.classList.add('hidden');
            }
        }

        selectTipe.addEventListener('change', updateHint);
        inputJumlah.addEventListener('input', updateHint);

        stockForm.addEventListener('submit', function(e) {
            const tipe    = selectTipe.value;
            const jumlah  = parseInt(inputJumlah.value) || 0;
            if (tipe && jumlah > 0) {
                const currentIsi = parseInt(stokIsiMap[tipe]) || 0;
                const maxStock   = parseInt(maxStockMap[tipe]) || 0;
                const total      = currentIsi + jumlah;

                if (total > maxStock) {
                    e.preventDefault();
                    alert(`Gagal: Jumlah stok baru (${total}) melebihi kapasitas maksimum (${maxStock}) untuk tipe ${tipe}!`);
                }
            }
        });
    </script>
@endpush