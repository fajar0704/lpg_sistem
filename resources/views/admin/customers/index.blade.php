@extends('layouts.admin')
@section('title', 'Pelanggan - Sistem LPG')
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
    <h2 class="text-2xl font-bold text-gray-800">Manajemen Data Pelanggan Umum</h2>
    <a href="{{ route('admin.customers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">+ Tambah Pelanggan</a>
</div>

<div class="bg-white p-4 rounded-lg shadow mb-4">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-3 flex-wrap">
        <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" onchange="if(this.value === 'pengecer') window.location.href='{{ route('admin.sub-pangkalan.index') }}'">
            <option value="">Semua Kategori</option>
            <option value="rumah_tangga" {{ request('category') === 'rumah_tangga' ? 'selected' : '' }}>Rumah Tangga</option>
            <option value="usaha_mikro"  {{ request('category') === 'usaha_mikro'  ? 'selected' : '' }}>Usaha Mikro</option>
            <option value="pengecer"     {{ request('category') === 'pengecer'     ? 'selected' : '' }}>Sub Pangkalan (Pengecer)</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">Filter</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">KTP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($customers as $customer)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $customer->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $customer->ktp }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $customer->phone ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($customer->category === 'rumah_tangga') bg-blue-100 text-blue-800
                            @elseif($customer->category === 'usaha_mikro') bg-yellow-100 text-yellow-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ $customer->category_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm flex gap-2">
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada data pelanggan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $customers->withQueryString()->links() }}</div>
@endsection
