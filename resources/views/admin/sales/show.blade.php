@extends('layouts.admin')
@section('title', 'Detail Penjualan')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.sales.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Detail Penjualan</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow space-y-2">
        <h3 class="font-bold text-gray-700 mb-3">Informasi Transaksi</h3>
        <p><span class="text-gray-500">Invoice:</span> <span class="font-mono font-semibold">{{ $sale->invoice_number }}</span></p>
        <p><span class="text-gray-500">Tanggal:</span> {{ $sale->sale_date->format('d/m/Y') }}</p>
        <p><span class="text-gray-500">Dicatat oleh:</span> {{ $sale->user->name }}</p>
        <p><span class="text-gray-500">Sumber:</span> {{ ucfirst($sale->sold_by) }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow space-y-2">
        <h3 class="font-bold text-gray-700 mb-3">Informasi Pelanggan</h3>
        <p><span class="text-gray-500">Nama:</span> {{ $sale->customer?->name ?? '-' }}</p>
        <p><span class="text-gray-500">KTP:</span> {{ $sale->customer?->ktp ?? '-' }}</p>
        <p><span class="text-gray-500">Kategori:</span> {{ $sale->customer?->category_label ?? '-' }}</p>
        <p><span class="text-gray-500">Telepon:</span> {{ $sale->customer?->phone ?? '-' }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow mt-6">
    <div class="p-4 border-b border-gray-200 font-bold text-gray-700">Item Penjualan</div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Tabung</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($sale->items as $item)
            <tr>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->tabung_type }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }} tabung</td>
            </tr>
            @endforeach
            <tr class="bg-gray-50 font-semibold">
                <td class="px-4 py-3 text-sm">Total</td>
                <td class="px-4 py-3 text-sm">{{ $sale->total_quantity }} tabung</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
