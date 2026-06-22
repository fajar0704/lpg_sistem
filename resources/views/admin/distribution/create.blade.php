@extends('layouts.admin')

@section('title', 'Kirim LPG ke Sub Pangkalan - Sistem LPG')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.distribution.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Kirim LPG ke Sub Pangkalan</h2>
    <p class="text-gray-600">Catat distribusi pengiriman LPG ke pengecer. Stok pangkalan akan langsung berkurang setelah disimpan.</p>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <form action="{{ route('admin.distribution.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="sub_pangkalan_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Sub Pangkalan (Pengecer)</label>
            <select name="sub_pangkalan_id" id="sub_pangkalan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sub_pangkalan_id') border-red-500 @enderror" required>
                <option value="">-- Pilih Sub Pangkalan --</option>
                @foreach($subPangkalans as $sp)
                    <option value="{{ $sp->id }}" {{ old('sub_pangkalan_id') == $sp->id ? 'selected' : '' }}>
                        {{ $sp->name }} ({{ $sp->code }}) - Stok Isi Saat Ini: {{ $sp->stok_isi }}
                    </option>
                @endforeach
            </select>
            @error('sub_pangkalan_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tabung_type" class="block text-gray-700 text-sm font-bold mb-2">Tipe Tabung</label>
            <select name="tabung_type" id="tabung_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tabung_type') border-red-500 @enderror" required>
                <option value="">-- Pilih Tipe --</option>
                @foreach($stocks as $stock)
                    <option value="{{ $stock->tabung_type }}" {{ old('tabung_type') == $stock->tabung_type ? 'selected' : '' }}>
                        {{ $stock->tabung_type }} (Tersedia: {{ $stock->stok_isi }})
                    </option>
                @endforeach
            </select>
            @error('tabung_type')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Dikirim (Tabung Isi)</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror"
                onwheel="this.blur()"
                required>
            @error('quantity')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="transaction_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengiriman</label>
            <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('transaction_date') border-red-500 @enderror"
                required>
            @error('transaction_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Catatan (Opsional)</label>
            <textarea name="notes" id="notes" rows="3" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
            @error('notes')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" onclick="return confirm('Anda yakin ingin menyimpan distribusi ini? Stok isi pangkalan akan langsung dikurangi.')">
                Kirim Distribusi
            </button>
            <a href="{{ route('admin.distribution.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
