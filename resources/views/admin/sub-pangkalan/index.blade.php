@extends('layouts.admin')

@section('title', 'Manajemen Sub Pangkalan - Sistem LPG')

@section('content')
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            Pelanggan Umum
        </a>
        <a href="{{ route('admin.sub-pangkalan.index') }}" class="{{ request()->routeIs('admin.sub-pangkalan.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            Sub Pangkalan
        </a>
    </nav>
</div>

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Sub Pangkalan</h2>
        <p class="text-gray-600">Daftar semua Sub Pangkalan terdaftar</p>
    </div>
    <a href="{{ route('admin.sub-pangkalan.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        + Tambah Sub Pangkalan
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Distribusi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($subPangkalan as $sp)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $sp->code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sp->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $sp->address ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sp->phone ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sp->distributions_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full {{ $sp->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $sp->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.sub-pangkalan.detail', $sp) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                    <a href="{{ route('admin.sub-pangkalan.edit', $sp) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                    <form action="{{ route('admin.sub-pangkalan.toggle-status', $sp) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-purple-600 hover:text-purple-900">
                            {{ $sp->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada Sub Pangkalan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $subPangkalan->links() }}
</div>
@endsection
