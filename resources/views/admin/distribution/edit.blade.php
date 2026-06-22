@extends('layouts.admin')

@section('title', 'Edit Distribusi - Sistem LPG')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.distribution.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Distribusi LPG</h2>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <form action="{{ route('admin.distribution.update', $distribution) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Sub Pangkalan (Pengecer)</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" value="{{ $distribution->subPangkalan->name }}" disabled>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Tabung</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" value="{{ $distribution->tabung_type }}" disabled>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $distribution->quantity) }}" min="1"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror"
                onwheel="this.blur()"
                required>
            <p class="text-xs text-gray-500 mt-1">Stok akan disesuaikan secara otomatis jika jumlah diubah.</p>
            @error('quantity')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="transaction_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengiriman</label>
            <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', $distribution->transaction_date->format('Y-m-d')) }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('transaction_date') border-red-500 @enderror"
                required>
            @error('transaction_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Catatan (Opsional)</label>
            <textarea name="notes" id="notes" rows="3" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $distribution->notes) }}</textarea>
            @error('notes')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.distribution.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
