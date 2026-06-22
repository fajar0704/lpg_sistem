@extends('layouts.admin')

@section('title', 'Manajemen Distribusi - Sistem LPG')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Distribusi</h2>
        <p class="text-gray-600">Distribusi LPG ke Sub Pangkalan & Konfirmasi Pengembalian</p>
    </div>
    <a href="{{ route('admin.distribution.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">+ Kirim LPG ke Sub Pangkalan</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sub Pangkalan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Tabung</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($distributions as $dist)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->transaction_date->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->subPangkalan->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    @if($dist->transaction_type === 'receive')
                        Kirim ke Sub Pangkalan
                    @elseif($dist->transaction_type === 'exchange' || $dist->transaction_type === 'return_kosong')
                        Pengembalian Kosong
                    @else
                        {{ ucfirst($dist->transaction_type) }}
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->tabung_type }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->quantity }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($dist->status === 'approved') bg-green-100 text-green-800
                        @elseif($dist->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $dist->status === 'approved' ? 'Diterima/Disetujui' : ($dist->status === 'pending' ? 'Menunggu Konfirmasi' : 'Ditolak') }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    @if($dist->status === 'pending')
                        <div class="flex items-center gap-2">
                            @if($dist->transaction_type === 'exchange' || $dist->transaction_type === 'return_kosong')
                                <form action="{{ route('admin.distribution.approve', $dist) }}" method="POST" class="inline" onsubmit="return confirm('Konfirmasi pengembalian tabung kosong ini? Stok kosong pangkalan akan bertambah.')">
                                    @csrf
                                    <button type="submit" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-xs">Terima</button>
                                </form>
                                <form action="{{ route('admin.distribution.reject', $dist) }}" method="POST" class="inline" onsubmit="return confirm('Tolak pengembalian tabung kosong ini? Stok kosong pengecer akan dikembalikan.')">
                                    @csrf
                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs">Tolak</button>
                                </form>
                            @else
                                <form action="{{ route('admin.distribution.destroy', $dist) }}" method="POST" class="inline" onsubmit="return confirm('Hapus distribusi ini? Stok isi pangkalan akan dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs">Hapus Distribusi</button>
                                </form>
                            @endif
                        </div>
                    @elseif($dist->status === 'approved')
                        <a href="{{ route('admin.distribution.show', $dist) }}" class="inline-flex items-center gap-1 text-blue-600 bg-white border border-blue-600 hover:bg-blue-50 px-3 py-1 rounded text-xs transition">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat Detail
                        </a>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data distribusi</td>
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
