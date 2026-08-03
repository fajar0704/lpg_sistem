@extends('layouts.admin')
@section('title', 'Edit Stok LPG')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.stock.index') }}" class="text-blue-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Stok - {{ $stockLpg->tabung_type }}</h2>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-xl">
    <form action="{{ route('admin.stock.update', $stockLpg) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Tabung</label>
            <input type="text" value="{{ $stockLpg->tabung_type }}" disabled
                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Kapasitas Maksimum 📦</label>
            <input type="number" onwheel="this.blur()" name="max_stock" value="{{ old('max_stock', $stockLpg->max_stock) }}" min="1" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('max_stock') border-red-500 @enderror">
            @error('max_stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Stok Isi 🟢</label>
                <input type="number" onwheel="this.blur()" name="stok_isi" value="{{ old('stok_isi', $stockLpg->stok_isi) }}" min="0" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('stok_isi') border-red-500 @enderror">
                @error('stok_isi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Stok Kosong ⚪</label>
                <input type="number" onwheel="this.blur()" name="stok_kosong" value="{{ old('stok_kosong', $stockLpg->stok_kosong) }}" min="0" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('stok_kosong') border-red-500 @enderror">
                @error('stok_kosong')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Safety Stock (Batas Minimum)</label>
            <input type="number" onwheel="this.blur()" name="safety_stock" value="{{ old('safety_stock', $stockLpg->safety_stock) }}" min="0" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('safety_stock') border-red-500 @enderror">
            <p class="text-gray-400 text-xs mt-1">Sistem akan memberi peringatan jika stok isi ≤ nilai ini.</p>
            @error('safety_stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div id="capacity-warning" class="bg-red-50 border border-red-200 rounded p-3 mb-4 text-sm text-red-700 hidden">
            ⚠️ Perhatian: <span id="total-lbl">0</span> melebihi kapasitas maksimum untuk tipe ini (<strong id="max-lbl">0</strong>).
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4 text-sm text-yellow-700">
            ⚠️ Perubahan stok isi/kosong akan langsung memperbarui data. Gunakan fitur ini hanya untuk koreksi data.
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">💾 Simpan</button>
            <a href="{{ route('admin.stock.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const maxStockInput = document.querySelector('input[name="max_stock"]');
        const stokIsiInput = document.querySelector('input[name="stok_isi"]');
        const stokKosongInput = document.querySelector('input[name="stok_kosong"]');
        const form = document.querySelector('form');
        const warning = document.getElementById('capacity-warning');
        const totalLbl = document.getElementById('total-lbl');
        const maxLbl = document.getElementById('max-lbl');

        const initialMax = parseInt(maxStockInput.value) || 0;
        let initialKosong = parseInt(stokKosongInput.value) || 0;

        function adjustStokKosong() {
            const currentMax = parseInt(maxStockInput.value) || 0;
            if (currentMax > initialMax) {
                const diff = currentMax - initialMax;
                stokKosongInput.value = initialKosong + diff;
            } else {
                stokKosongInput.value = initialKosong;
            }
            checkCapacity();
        }

        function checkCapacity() {
            const maxVal = parseInt(maxStockInput.value) || 0;
            const isiVal = parseInt(stokIsiInput.value) || 0;
            const kosongVal = parseInt(stokKosongInput.value) || 0;
            maxLbl.textContent = maxVal;

            if (isiVal > maxVal || kosongVal > maxVal) {
                let problem = "";
                if (isiVal > maxVal && kosongVal > maxVal) {
                    problem = `Stok isi (${isiVal}) dan Stok kosong (${kosongVal})`;
                } else if (isiVal > maxVal) {
                    problem = `Stok isi (${isiVal})`;
                } else {
                    problem = `Stok kosong (${kosongVal})`;
                }
                totalLbl.textContent = problem;
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }

        maxStockInput.addEventListener('input', adjustStokKosong);

        stokKosongInput.addEventListener('input', function() {
            const currentMax = parseInt(maxStockInput.value) || 0;
            const currentKosong = parseInt(stokKosongInput.value) || 0;
            if (currentMax > initialMax) {
                initialKosong = currentKosong - (currentMax - initialMax);
            } else {
                initialKosong = currentKosong;
            }
            checkCapacity();
        });

        stokIsiInput.addEventListener('input', checkCapacity);
        checkCapacity();

        form.addEventListener('submit', function(e) {
            const maxVal = parseInt(maxStockInput.value) || 0;
            const isiVal = parseInt(stokIsiInput.value) || 0;
            const kosongVal = parseInt(stokKosongInput.value) || 0;

            if (isiVal > maxVal) {
                e.preventDefault();
                alert(`Gagal: Jumlah stok isi (${isiVal}) melebihi kapasitas maksimum (${maxVal})!`);
                return;
            }
            if (kosongVal > maxVal) {
                e.preventDefault();
                alert(`Gagal: Jumlah stok kosong (${kosongVal}) melebihi kapasitas maksimum (${maxVal})!`);
                return;
            }
        });
    });
</script>
@endsection
