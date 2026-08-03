@extends('layouts.sub-pangkalan')
@section('title', 'Riwayat Penjualan')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Riwayat Penjualan</h2>
    <a href="{{ route('sub-pangkalan.sales.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Penjualan Baru</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($sales as $sale)
                <tr>
                    <td class="px-4 py-3 text-sm font-mono text-gray-900">{{ $sale->invoice_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->sale_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->customer?->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($sale->customer?->category === 'rumah_tangga') bg-blue-100 text-blue-800
                            @elseif($sale->customer?->category === 'usaha_mikro') bg-yellow-100 text-yellow-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ $sale->customer?->category_label ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->total_quantity }} tabung</td>
                    <td class="px-4 py-3 text-sm">
                        <a href="{{ route('sub-pangkalan.sales.show', $sale) }}" class="text-blue-600 hover:underline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data penjualan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $sales->links() }}</div>
@endsection
