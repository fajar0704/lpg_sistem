@extends('layouts.sub-pangkalan')
@section('title', 'Ajukan Terima LPG')
@section('content')
<div class="mb-6">
    <a href="{{ route('sub-pangkalan.dashboard') }}" class="text-blue-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Ajukan Penerimaan LPG</h2>
    <p class="text-gray-500 text-sm mt-1">Pengajuan akan dikirim ke admin untuk divalidasi. Stok isi akan bertambah setelah disetujui.</p>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-xl">
    <form action="{{ route('sub-pangkalan.input.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Tabung</label>
            <select name="tabung_type" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tabung_type') border-red-500 @enderror">
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
            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Tabung yang Diminta</label>
            <input type="number" onwheel="this.blur()" name="quantity" value="{{ old('quantity') }}" min="1" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
            @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
            <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Catatan (Opsional)</label>
            <textarea name="notes" rows="2"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-4 text-sm text-blue-700">
            ℹ️ Setelah admin menyetujui, stok isi Anda akan bertambah sesuai jumlah yang dikirim.
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">📤 Kirim Pengajuan</button>
            <a href="{{ route('sub-pangkalan.dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection
