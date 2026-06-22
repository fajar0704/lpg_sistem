@extends('layouts.admin')

@section('title', 'Detail Distribusi - Sistem LPG')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.distribution.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Detail Distribusi LPG</h2>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <table class="w-full text-left border-collapse">
        <tbody>
            <tr class="border-b">
                <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700 w-1/3">Tanggal</th>
                <td class="py-3 px-4">{{ $distribution->transaction_date->format('d/m/Y') }}</td>
            </tr>
            <tr class="border-b">
                <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Sub Pangkalan</th>
                <td class="py-3 px-4">{{ $distribution->subPangkalan->name }}</td>
            </tr>
            <tr class="border-b">
                <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Jenis Transaksi</th>
                <td class="py-3 px-4">
                    @if($distribution->transaction_type === 'receive')
                        Kirim ke Sub Pangkalan
                    @elseif($distribution->transaction_type === 'exchange' || $distribution->transaction_type === 'return_kosong')
                        Pengembalian Kosong
                    @else
                        {{ ucfirst($distribution->transaction_type) }}
                    @endif
                </td>
            </tr>
            <tr class="border-b">
                <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Tipe Tabung</th>
                <td class="py-3 px-4">{{ $distribution->tabung_type }}</td>
            </tr>
            <tr class="border-b">
                <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Jumlah</th>
                <td class="py-3 px-4 font-bold">{{ $distribution->quantity }} Tabung</td>
            </tr>
            <tr class="border-b">
                <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Status</th>
                <td class="py-3 px-4">
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($distribution->status === 'approved') bg-green-100 text-green-800
                        @elseif($distribution->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $distribution->status === 'approved' ? 'Diterima/Disetujui' : ($distribution->status === 'pending' ? 'Menunggu Konfirmasi' : 'Ditolak') }}
                    </span>
                </td>
            </tr>
            @if($distribution->validatedBy)
            <tr class="border-b">
                <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Divalidasi Oleh</th>
                <td class="py-3 px-4">{{ $distribution->validatedBy->name }}</td>
            </tr>
            @endif
            <tr>
                <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700 align-top">Catatan</th>
                <td class="py-3 px-4">{{ $distribution->notes ?: '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
