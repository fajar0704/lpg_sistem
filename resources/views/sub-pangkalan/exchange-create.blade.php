@extends('layouts.sub-pangkalan')
@section('title', 'Tukar Tabung Kosong')
@section('content')
<div class="mb-6">
    <a href="{{ route('sub-pangkalan.dashboard') }}" class="text-orange-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Pengajuan Tukar Tabung Kosong</h2>
    <p class="text-gray-500 text-sm mt-1">Ajukan penukaran tabung kosong ke pangkalan. Setelah disetujui, stok kosong berkurang dan stok isi bertambah.</p>
</div>

{{-- Info Stok --}}
<div class="grid grid-cols-2 gap-4 mb-6 max-w-xl">
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
        <p class="text-sm text-gray-500">Stok Isi</p>
        <p class="text-3xl font-bold text-green-600">{{ $subPangkalan->stok_isi }}</p>
    </div>
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
        <p class="text-sm text-gray-500">Stok Kosong</p>
        <p class="text-3xl font-bold text-orange-500">{{ $subPangkalan->stok_kosong }}</p>
    </div>
</div>

@if($subPangkalan->stok_kosong == 0)
<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 max-w-xl">
    ⚠️ Tidak ada tabung kosong untuk ditukar.
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow max-w-xl">
    <form action="{{ route('sub-pangkalan.exchange.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Tabung</label>
            <select name="tabung_type" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('tabung_type') border-red-500 @enderror">
                <option value="">Pilih Tipe Tabung</option>
                @foreach($stocks as $stock)
                <option value="{{ $stock->tabung_type }}" {{ old('tabung_type') === $stock->tabung_type ? 'selected' : '' }}>
                    {{ $stock->tabung_type }}
                </option>
                @endforeach
            </select>
            @error('tabung_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Tabung Kosong yang Ditukar</label>
            <input type="number" onwheel="this.blur()" name="quantity" value="{{ old('quantity') }}" min="1" max="{{ $subPangkalan->stok_kosong }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('quantity') border-red-500 @enderror">
            <p class="text-xs text-gray-400 mt-1">Maks: {{ $subPangkalan->stok_kosong }} tabung kosong</p>
            @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
            <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Catatan (Opsional)</label>
            <textarea name="notes" rows="2"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">{{ old('notes') }}</textarea>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded p-3 mb-4 text-sm text-orange-700">
            🔄 Setelah admin menyetujui: stok kosong berkurang, stok isi bertambah sesuai jumlah yang ditukar.
        </div>

        <div class="flex gap-2">
            <button type="submit" {{ $subPangkalan->stok_kosong == 0 ? 'disabled' : '' }}
                class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed">
                🔄 Kirim Pengajuan Tukar
            </button>
            <a href="{{ route('sub-pangkalan.dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection
