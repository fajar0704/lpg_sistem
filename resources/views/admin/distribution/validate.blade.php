@extends('layouts.admin')

@section('title', 'Validasi Distribusi - Sistem LPG')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.distribution.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Validasi Distribusi</h2>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Distribusi</h3>
        <div class="space-y-2">
            <p><span class="text-gray-600">Tanggal:</span> <span class="font-semibold">{{ $distribution->transaction_date->format('d/m/Y') }}</span></p>
            <p><span class="text-gray-600">Sub Pangkalan:</span> <span class="font-semibold">{{ $distribution->subPangkalan->name }}</span></p>
            <p><span class="text-gray-600">User:</span> <span class="font-semibold">{{ $distribution->user->name }}</span></p>
            <p><span class="text-gray-600">Jenis:</span> 
                <span class="px-2 py-1 text-xs rounded-full {{ $distribution->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $distribution->type === 'in' ? 'Masuk' : 'Keluar' }}
                </span>
            </p>
            <p><span class="text-gray-600">Tipe Tabung:</span> <span class="font-semibold">{{ $distribution->tabung_type }}</span></p>
            <p><span class="text-gray-600">Jumlah:</span> <span class="font-semibold">{{ $distribution->quantity }}</span></p>
            <p><span class="text-gray-600">Catatan:</span> <span class="font-semibold">{{ $distribution->notes ?? '-' }}</span></p>
        </div>
    </div>

    @if($distribution->status === 'pending')
    <div class="flex gap-2">
        <form action="{{ route('admin.distribution.approve', $distribution) }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                ✓ Setujui
            </button>
        </form>
        <form action="{{ route('admin.distribution.reject', $distribution) }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                ✗ Tolak
            </button>
        </form>
    </div>
    @else
    <div class="bg-gray-100 p-4 rounded-lg">
        <p class="text-gray-700">Status: <span class="font-semibold">{{ ucfirst($distribution->status) }}</span></p>
        <p class="text-gray-700">Divalidasi oleh: {{ $distribution->validatedBy ? $distribution->validatedBy->name : '-' }}</p>
        <p class="text-gray-700">Tanggal validasi: {{ $distribution->validated_at ? $distribution->validated_at->format('d/m/Y H:i') : '-' }}</p>
    </div>
    @endif
</div>
@endsection
