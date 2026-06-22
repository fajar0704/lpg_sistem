@extends('layouts.admin')
@section('title', 'Terima Stok Baru')
@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.stock.index') }}" class="text-blue-600 hover:underline">← Kembali ke Stok Pangkalan</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Terima Stok dari Pertamina</h2>
        <p class="text-gray-500 text-sm mt-1">Setiap penerimaan akan membuat <strong>batch baru</strong> dan dikelola dengan
            metode FIFO.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Form --}}
        <div class="bg-white p-6 rounded-lg shadow">
            <form action="{{ route('admin.stock.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Tabung <span
                            class="text-red-500">*</span></label>
                    <select name="tabung_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tabung_type') border-red-500 @enderror">
                        <option value="">Pilih Tipe</option>
                        <option value="3kg" {{ old('tabung_type') === '3kg' ? 'selected' : '' }}>3 kg (Subsidi)</option>
                        <option value="5kg" {{ old('tabung_type') === '5kg' ? 'selected' : '' }}>5 kg</option>
                        <option value="12kg" {{ old('tabung_type') === '12kg' ? 'selected' : '' }}>12 kg (Non-Subsidi)
                        </option>
                    </select>
                    @error('tabung_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Tabung Diterima <span
                            class="text-red-500">*</span></label>
                    <input type="number" onwheel="this.blur()" name="initial_stock" id="input_jumlah" value="{{ old('initial_stock') }}" min="1"
                        required placeholder="Contoh: 120"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('initial_stock') border-red-500 @enderror">
                    @error('initial_stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <div id="hint_kosong"
                        class="mt-2 p-2 bg-orange-50 border border-orange-200 rounded text-xs text-orange-700 hidden">
                        🔄 <strong>Otomatis:</strong> Stok kosong akan berkurang <span id="hint_jumlah"
                            class="font-bold">0</span> tabung (dikembalikan ke agen sebagai penukar).
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Terima <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Safety Stock (Batas Minimum Peringatan) <span
                            class="text-red-500">*</span></label>
                    <input type="number" onwheel="this.blur()" name="safety_stock" value="{{ old('safety_stock', 10) }}" min="0" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Sistem memberi peringatan jika stok isi ≤ nilai ini.</p>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                        💾 Simpan & Buat Batch
                    </button>
                    <a href="{{ route('admin.stock.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Panduan & Kapasitas --}}
        <div class="space-y-4">
            {{-- Kapasitas & Sisa --}}
            @if($currentStocks->isNotEmpty())
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow">
                    <p class="font-semibold text-gray-700 mb-3">📦 Kapasitas & Sisa Stok</p>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 text-gray-500">Tipe</th>
                                <th class="text-center py-2 text-gray-500">Stok Isi</th>
                                <th class="text-center py-2 text-gray-500">Stok Kosong</th>
                                <th class="text-center py-2 text-gray-500">Maks</th>
                                <th class="text-center py-2 text-gray-500">Sisa Kapasitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentStocks as $s)
                                @php $sisa = $s->sisaKapasitas(); @endphp
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 font-semibold text-gray-800">{{ $s->tabung_type }}</td>
                                    <td class="text-center font-bold text-green-600">{{ $s->stok_isi }}</td>
                                    <td class="text-center font-bold text-orange-500">{{ $s->stok_kosong }}</td>
                                    <td class="text-center text-gray-500">{{ $s->max_stock ?: '-' }}</td>
                                    <td
                                        class="text-center font-bold {{ $sisa <= 0 ? 'text-red-600' : ($sisa <= 5 ? 'text-orange-500' : 'text-blue-600') }}">
                                        {{ $sisa <= 0 ? '🔴 PENUH' : $sisa }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="text-xs text-orange-600 mt-2">🔄 Stok kosong akan otomatis berkurang saat kamu menyimpan
                        penerimaan stok isi.</p>
                </div>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="font-semibold text-blue-800 mb-2">💡 Panduan Kapasitas Pangkalan</p>
                <table class="w-full text-sm text-blue-700">
                    <thead>
                        <tr class="border-b border-blue-200">
                            <th class="text-left py-1">Tipe</th>
                            <th class="text-center py-1">Maks Stok</th>
                            <th class="text-center py-1">Safety Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-blue-100">
                            <td class="py-2">3 kg</td>
                            <td class="text-center font-bold">120 tabung</td>
                            <td class="text-center">20</td>
                        </tr>
                        <tr class="border-b border-blue-100">
                            <td class="py-2">5 kg</td>
                            <td class="text-center font-bold">10 tabung</td>
                            <td class="text-center">3</td>
                        </tr>
                        <tr>
                            <td class="py-2">12 kg</td>
                            <td class="text-center font-bold">20 tabung</td>
                            <td class="text-center">5</td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-xs text-blue-600 mt-2">⚠️ Total kapasitas pangkalan: <strong>150 tabung</strong></p>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
                <p class="font-semibold mb-1">🔒 Aturan Batas Stok</p>
                <ul class="list-disc list-inside space-y-1 text-yellow-700">
                    <li>Stok tidak boleh melebihi kapasitas maksimum</li>
                    <li>Sistem akan menolak input jika melebihi batas</li>
                    <li>Contoh: 3kg maks 120, tidak bisa input jika sudah 120</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const stokKosongMap = @json($stokKosongMap);

        const selectTipe    = document.querySelector('select[name="tabung_type"]');
        const inputJumlah   = document.getElementById('input_jumlah');
        const hintBox       = document.getElementById('hint_kosong');
        const hintJumlah    = document.getElementById('hint_jumlah');

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
        }

        selectTipe.addEventListener('change', updateHint);
        inputJumlah.addEventListener('input', updateHint);
    </script>
@endpush