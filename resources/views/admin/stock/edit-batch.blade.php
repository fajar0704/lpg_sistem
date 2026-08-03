@extends('layouts.admin')

@section('title', 'Ubah Batch - Sistem LPG')

@section('content')
<div class="space-y-6 max-w-2xl">
    <!-- Breadcrumb & Header -->
    <div>
        <a href="{{ route('admin.stock.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Stok Pangkalan
        </a>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Ubah Batch Stock</h2>
        <p class="text-slate-500 text-sm mt-1">Ubah kuantitas atau tanggal masuk batch tabung <strong>{{ $batch->tabung_type }}</strong>.</p>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('admin.stock.batch.update', $batch) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Detail Batch info -->
            <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between text-sm">
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Tipe Tabung</span>
                    <span class="font-bold text-slate-700">{{ $batch->tabung_type }}</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Sisa Saat Ini</span>
                    <span class="font-bold text-blue-600">{{ $batch->quantity_remaining }} / {{ $batch->quantity_in }} tabung</span>
                </div>
            </div>

            <!-- Jumlah Diterima -->
            <div>
                <label for="quantity_in" class="block text-slate-700 text-sm font-semibold mb-2">
                    Jumlah Tabung Diterima <span class="text-rose-500">*</span>
                </label>
                <input type="number" onwheel="this.blur()" name="quantity_in" id="quantity_in" value="{{ old('quantity_in', $batch->quantity_in) }}" min="1" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('quantity_in') border-rose-500 focus:ring-rose-500/20 @enderror">
                @error('quantity_in')
                    <p class="text-rose-600 text-xs mt-1.5 font-medium flex items-center gap-1">
                        <span class="w-1 h-1 rounded-full bg-rose-600"></span>{{ $message }}
                    </p>
                @enderror
                <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Jika kuantitas diubah, sisa stok batch dan total stok isi pangkalan akan menyesuaikan.</p>

                <!-- Capacity limit warning -->
                <div id="capacity-warning" class="mt-3 p-3 bg-rose-50/50 border border-rose-200 text-rose-800 text-xs rounded-xl flex items-start gap-2.5 hidden">
                    <div class="p-1 bg-rose-500 text-white rounded-lg shrink-0">
                        ⚠️
                    </div>
                    <div>
                        <span class="font-bold">Kapasitas Penuh:</span> Jumlah baru (<strong id="total-lbl">0</strong>) melebihi kapasitas maksimum (<strong id="max-lbl">{{ $stock ? $stock->max_stock : 0 }}</strong>). Input akan ditolak.
                    </div>
                </div>
            </div>

            <!-- Tanggal Terima -->
            <div>
                <label for="received_date" class="block text-slate-700 text-sm font-semibold mb-2">
                    Tanggal Terima <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="received_date" id="received_date" value="{{ old('received_date', $batch->received_date->format('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium">
                @error('received_date')
                    <p class="text-rose-600 text-xs mt-1.5 font-medium flex items-center gap-1">
                        <span class="w-1 h-1 rounded-full bg-rose-600"></span>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.stock.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-3 rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputQtyIn = document.getElementById('quantity_in');
            const originalQtyIn = {{ $batch->quantity_in }};
            const currentStokIsi = {{ $stock ? $stock->stok_isi : 0 }};
            const maxStock = {{ $stock ? $stock->max_stock : 0 }};
            const warning = document.getElementById('capacity-warning');
            const totalLbl = document.getElementById('total-lbl');
            const form = document.querySelector('form');

            function checkCapacity() {
                const newQty = parseInt(inputQtyIn.value) || 0;
                const diff = newQty - originalQtyIn;
                const total = currentStokIsi + diff;

                if (total > maxStock) {
                    totalLbl.textContent = total;
                    warning.classList.remove('hidden');
                } else {
                    warning.classList.add('hidden');
                }
            }

            inputQtyIn.addEventListener('input', checkCapacity);
            checkCapacity();

            form.addEventListener('submit', function(e) {
                const newQty = parseInt(inputQtyIn.value) || 0;
                const diff = newQty - originalQtyIn;
                const total = currentStokIsi + diff;

                if (total > maxStock) {
                    e.preventDefault();
                    alert(`Gagal: Jumlah stok isi baru (${total}) melebihi kapasitas maksimum (${maxStock}) untuk tipe ini!`);
                }
            });
        });
    </script>
@endpush
