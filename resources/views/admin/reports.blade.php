@extends('layouts.admin')

@section('title', 'Laporan - Sistem LPG')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Laporan Distribusi</h2>
    <p class="text-gray-600">Generate dan ekspor laporan distribusi</p>
</div>

<div class="bg-white p-6 rounded-lg shadow mb-6">
    <form action="{{ route('admin.reports') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Laporan</label>
            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="daily" {{ $type === 'daily' ? 'selected' : '' }}>Harian</option>
                <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="sub_pangkalan" {{ $type === 'sub_pangkalan' ? 'selected' : '' }}>Per Sub Pangkalan</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ $startDate }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ $endDate }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Sub Pangkalan</label>
            <select name="sub_pangkalan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua</option>
                @foreach($subPangkalan as $sp)
                    <option value="{{ $sp->id }}" {{ $subPangkalanId == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Filter
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Data Distribusi</h3>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.export-pdf', request()->all()) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                📄 Export PDF
            </a>
            <a href="{{ route('admin.reports.export-excel', request()->all()) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                📊 Export Excel
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sub Pangkalan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Tabung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($distributions as $dist)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->transaction_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->subPangkalan->name }}</td>
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
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data untuk periode yang dipilih</td>
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
