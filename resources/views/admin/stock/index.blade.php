@extends('layouts.admin')
@section('title', 'Manajemen Stok LPG')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Stok Pangkalan</h2>
        <p class="text-gray-500 text-sm">Pengelolaan stok dengan metode FIFO</p>
    </div>
    <a href="{{ route('admin.stock.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Stok Masuk</a>
</div>

{{-- Ringkasan Stok --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @foreach($stocks as $stock)
    <div class="bg-white p-4 rounded-lg shadow border-l-4 {{ $stock->isBelowSafety() ? 'border-red-500' : 'border-green-500' }}">
        <div class="flex justify-between items-start mb-2">
            <h3 class="font-bold text-gray-800 text-lg">{{ $stock->tabung_type }}</h3>
            @if($stock->isBelowSafety())
            <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">⚠️ Menipis</span>
            @else
            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">✅ Aman</span>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-2 text-sm mb-2">
            <div>
                <p class="text-gray-500">Stok Isi</p>
                <p class="text-2xl font-bold text-green-600">{{ $stock->stok_isi }}</p>
            </div>
            <div>
                <p class="text-gray-500">Stok Kosong</p>
                <p class="text-2xl font-bold text-orange-500">{{ $stock->stok_kosong }}</p>
            </div>
        </div>
        <p class="text-xs text-gray-400">Safety stock: {{ $stock->safety_stock }}</p>
        <a href="{{ route('admin.stock.edit', $stock) }}" class="text-xs text-blue-600 hover:underline">Edit safety stock →</a>
    </div>
    @endforeach
</div>

{{-- FIFO per Tipe Tabung --}}
@foreach($stocks as $stock)
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <h3 class="font-bold text-gray-800">📦 FIFO Tabung {{ $stock->tabung_type }}</h3>
    </div>

    {{-- Tabel Batch --}}
    <div class="p-4">
        <h4 class="text-sm font-semibold text-gray-600 mb-2">Daftar Batch Masuk</h4>
        <div class="overflow-x-auto mb-4">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Batch</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Masuk</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stok Awal</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sisa</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($batches->get($stock->tabung_type, collect()) as $i => $batch)
                    <tr class="{{ $batch->isHabis() ? 'bg-gray-50 text-gray-400' : '' }}">
                        <td class="px-4 py-2 font-mono font-semibold">B{{ $i + 1 }}</td>
                        <td class="px-4 py-2">{{ $batch->received_date->format('d M Y') }}</td>
                        <td class="px-4 py-2">{{ $batch->quantity_in }}</td>
                        <td class="px-4 py-2 font-bold {{ $batch->isHabis() ? 'text-gray-400' : 'text-green-600' }}">{{ $batch->quantity_remaining }}</td>
                        <td class="px-4 py-2">
                            @if($batch->status === 'Habis')
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Habis</span>
                            @elseif($batch->status === 'Aktif')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">Aktif</span>
                            @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Baru</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-3 text-center text-gray-400">Belum ada batch masuk</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabel Riwayat Pengeluaran --}}
        <h4 class="text-sm font-semibold text-gray-600 mb-2">Riwayat Pengeluaran (FIFO Tracking)</h4>
        <div class="overflow-x-auto">
            <table class="w-full min-w-max text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keluar</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Diambil dari Batch</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sumber</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $batchList = $batches->get($stock->tabung_type, collect())->values();
                    @endphp
                    @forelse($outflows->get($stock->tabung_type, collect()) as $out)
                    @php
                        $batchIndex = $batchList->search(fn($b) => $b->id === $out->stock_batch_id);
                        $batchLabel = $batchIndex !== false ? 'B' . ($batchIndex + 1) : '-';
                    @endphp
                    <tr>
                        <td class="px-4 py-2">{{ $out->transaction_date->format('d M Y') }}</td>
                        <td class="px-4 py-2 font-semibold text-red-600">{{ $out->quantity }}</td>
                        <td class="px-4 py-2 font-mono font-bold text-blue-700">{{ $batchLabel }}</td>
                        <td class="px-4 py-2">
                            @if($out->source === 'penjualan_langsung')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">🛒 Jual Langsung</span>
                            @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">📦 Distribusi Sub</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-3 text-center text-gray-400">Belum ada pengeluaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach

@if($stocks->isEmpty())
<div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
    <p class="text-4xl mb-2">📦</p>
    <p>Belum ada data stok. Klik <a href="{{ route('admin.stock.create') }}" class="text-blue-600 hover:underline">+ Stok Masuk</a> untuk menambahkan.</p>
</div>
@endif
@endsection
