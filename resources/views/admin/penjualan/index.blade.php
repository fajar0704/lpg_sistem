@extends('layouts.admin')
@section('title', 'Riwayat Penjualan - Sistem LPG')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Penjualan Langsung</h2>
        <p class="text-gray-600">Penjualan langsung ke pembeli di pangkalan</p>
    </div>
    <a href="{{ route('admin.penjualan.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
        + Jual ke Pembeli
    </a>
</div>

{{-- Ringkasan --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-sm text-gray-500">Total Terjual Hari Ini</p>
        <p class="text-2xl font-bold text-green-600">{{ $penjualan->where('transaction_date', today()->toDateString())->sum('quantity') }} tabung</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-sm text-gray-500">Total Terjual Bulan Ini</p>
        <p class="text-2xl font-bold text-blue-600">{{ $penjualan->where('transaction_date', '>=', now()->startOfMonth()->toDateString())->sum('quantity') }} tabung</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pembeli</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. KTP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Tabung</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dicatat Oleh</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($penjualan as $p)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $p->transaction_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->nama_pembeli }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-700">{{ $p->no_ktp }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-full text-xs {{ $p->customer_type === 'rumah_tangga' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            {{ $p->customer_type === 'rumah_tangga' ? '🏠 Rumah Tangga' : '🏪 Usaha Mikro' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->tabung_type }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ $p->quantity }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $p->user->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $p->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-4 text-center text-gray-500">Belum ada penjualan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $penjualan->links() }}
</div>
@endsection
