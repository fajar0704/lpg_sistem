@extends('layouts.sub-pangkalan')
@section('title', 'Riwayat Transaksi - Sistem LPG')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
    <p class="text-gray-600">Semua riwayat transaksi LPG Anda</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Transaksi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Tabung</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divalidasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($distributions as $dist)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $dist->transaction_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($dist->transaction_type === 'receive')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">📥 Terima LPG</span>
                        @elseif($dist->transaction_type === 'sell')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">🛒 Penjualan</span>
                        @elseif($dist->transaction_type === 'exchange')
                            <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs">🔄 Tukar Kosong</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ $dist->type }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                        @if($dist->customer_type === 'rumah_tangga') 🏠 Rumah Tangga
                        @elseif($dist->customer_type === 'usaha') 🏪 Usaha
                        @else -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $dist->tabung_type }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $dist->quantity }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($dist->status === 'approved') bg-green-100 text-green-800
                            @elseif($dist->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $dist->status === 'approved' ? 'Disetujui' : ($dist->status === 'pending' ? 'Menunggu' : 'Ditolak') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $dist->validatedBy?->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($dist->transaction_type === 'receive' && $dist->status === 'pending')
                            <form action="{{ route('sub-pangkalan.distribution.confirm', $dist) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa LPG telah Anda terima? Stok isi Anda akan bertambah.')">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white text-xs px-2 py-1 rounded hover:bg-blue-700">Konfirmasi Terima</button>
                            </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-4 text-center text-gray-500">Belum ada data transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $distributions->links() }}
</div>
@endsection
