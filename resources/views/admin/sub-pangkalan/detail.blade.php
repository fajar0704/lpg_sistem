@extends('layouts.admin')

@section('title', 'Detail Sub Pangkalan - Sistem LPG')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.sub-pangkalan.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Detail Sub Pangkalan</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Sub Pangkalan</h3>
        <div class="space-y-2">
            <p><span class="text-gray-600">Kode:</span> <span class="font-semibold">{{ $subPangkalan->code }}</span></p>
            <p><span class="text-gray-600">Nama:</span> <span class="font-semibold">{{ $subPangkalan->name }}</span></p>
            <p><span class="text-gray-600">Alamat:</span> <span class="font-semibold">{{ $subPangkalan->address ?? '-' }}</span></p>
            <p><span class="text-gray-600">Telepon:</span> <span class="font-semibold">{{ $subPangkalan->phone ?? '-' }}</span></p>
            <p><span class="text-gray-600">Status:</span> 
                <span class="px-2 py-1 text-xs rounded-full {{ $subPangkalan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $subPangkalan->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">Riwayat Distribusi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Tabung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Validasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($distributions as $dist)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->transaction_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $dist->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $dist->type === 'in' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->tabung_type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($dist->status === 'approved') bg-green-100 text-green-800
                            @elseif($dist->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($dist->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $dist->validatedBy ? $dist->validatedBy->name : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.distribution.index') }}" class="text-blue-600 hover:text-blue-900 text-xs bg-blue-100 px-2 py-1 rounded">Cek di Manajemen Distribusi</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada data distribusi</td>
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
