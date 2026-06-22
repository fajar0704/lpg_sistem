@extends('layouts.admin')

@section('title', 'Monitoring Sub Pangkalan - Sistem LPG')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Monitoring Sub Pangkalan</h2>
    <p class="text-gray-600">Pantau stok, aktivitas penjualan, dan distribusi ke setiap pengecer.</p>
</div>

<div class="mb-8">
    <h3 class="text-lg font-bold text-gray-700 mb-4">📦 Stok Sub Pangkalan (Pengecer)</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subPangkalans as $sp)
        <div class="bg-white rounded-lg shadow p-5 border-l-4 {{ $sp->is_active ? 'border-green-500' : 'border-gray-400' }}">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h4 class="font-bold text-gray-800">{{ $sp->name }}</h4>
                    <p class="text-xs text-gray-500">{{ $sp->code }} | {{ $sp->address }}</p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full {{ $sp->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $sp->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="bg-blue-50 p-3 rounded text-center">
                    <p class="text-xs text-gray-600 mb-1">Stok Isi</p>
                    <p class="text-xl font-bold text-blue-700">{{ $sp->stok_isi }}</p>
                </div>
                <div class="bg-orange-50 p-3 rounded text-center">
                    <p class="text-xs text-gray-600 mb-1">Stok Kosong</p>
                    <p class="text-xl font-bold text-orange-700">{{ $sp->stok_kosong }}</p>
                </div>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('admin.sub-pangkalan.detail', $sp->id) }}" class="text-sm text-blue-600 hover:underline">Lihat Detail →</a>
            </div>
        </div>
        @empty
        <div class="col-span-full p-6 text-center text-gray-500 bg-white rounded shadow">
            Belum ada data Sub Pangkalan.
        </div>
        @endforelse
    </div>
</div>

<div>
    <h3 class="text-lg font-bold text-gray-700 mb-4">🔄 Monitoring Distribusi & Aktivitas Terbaru</h3>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengecer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Aktivitas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($distributions as $dist)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $dist->transaction_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ optional($dist->subPangkalan)->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            @if($dist->transaction_type === 'receive')
                                <span class="text-blue-600">📥 Kirim LPG ke Pengecer</span>
                            @elseif($dist->transaction_type === 'sell')
                                <span class="text-green-600">🛒 Penjualan ke {{ $dist->customer_type == 'rumah_tangga' ? 'Rumah Tangga' : 'Usaha' }}</span>
                            @elseif($dist->transaction_type === 'exchange' || $dist->transaction_type === 'return_kosong')
                                <span class="text-orange-600">🔄 Pengembalian Kosong</span>
                            @else
                                {{ ucfirst($dist->transaction_type) }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $dist->tabung_type }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ $dist->quantity }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($dist->status === 'approved') bg-green-100 text-green-800
                                @elseif($dist->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $dist->status === 'approved' ? 'Selesai' : ($dist->status === 'pending' ? 'Pending' : 'Ditolak') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 bg-gray-50 text-center">
            <a href="{{ route('admin.distribution.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua Data Distribusi →</a>
        </div>
    </div>
</div>
@endsection
