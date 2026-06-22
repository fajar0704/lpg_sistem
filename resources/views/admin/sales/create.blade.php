@extends('layouts.admin')
@section('title', 'Penjualan LPG')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Transaksi Penjualan</h2>
</div>

{{-- Step 1: Pilih Kategori --}}
@if(!$category)
<div class="bg-white p-6 rounded-lg shadow max-w-xl">
    <h3 class="text-lg font-bold text-gray-700 mb-4">Pilih Kategori Pelanggan</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach(['rumah_tangga' => ['Rumah Tangga','🏠','blue'], 'usaha_mikro' => ['Usaha Mikro','🏪','yellow'], 'pengecer' => ['Pengecer','🛒','purple']] as $val => [$label,$icon,$color])
        <a href="{{ route('admin.sales.create', ['category' => $val]) }}"
            class="flex flex-col items-center p-6 border-2 border-{{ $color }}-300 rounded-lg hover:bg-{{ $color }}-50 transition text-center">
            <span class="text-4xl mb-2">{{ $icon }}</span>
            <span class="font-semibold text-{{ $color }}-700">{{ $label }}</span>
        </a>
        @endforeach
    </div>
</div>
@else
{{-- Step 2: Form Penjualan --}}
<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <div class="flex items-center gap-2 mb-4">
        <a href="{{ route('admin.sales.create') }}" class="text-blue-600 hover:underline text-sm">← Ganti Kategori</a>
        <span class="text-gray-400">|</span>
        <span class="text-sm font-semibold text-gray-700">
            Kategori: {{ ['rumah_tangga'=>'Rumah Tangga','usaha_mikro'=>'Usaha Mikro','pengecer'=>'Pengecer'][$category] }}
        </span>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
    </div>
    @endif

    <form action="{{ route('admin.sales.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-1">Pelanggan</label>
            <select name="customer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Pilih Pelanggan</option>
                @foreach($customers as $c)
                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->name }} — KTP: {{ $c->ktp }}
                </option>
                @endforeach
            </select>
            @if($customers->isEmpty())
            <p class="text-yellow-600 text-xs mt-1">Belum ada pelanggan kategori ini. <a href="{{ route('admin.customers.create') }}" class="underline">Tambah pelanggan</a></p>
            @endif
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-1">Tanggal Penjualan</label>
            <input type="date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Item LPG</label>
            <div id="items-container" class="space-y-2">
                <div class="flex gap-2 items-center item-row">
                    <select name="items[0][tabung_type]" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Pilih Tipe</option>
                        @foreach($stocks as $s)
                        <option value="{{ $s->tabung_type }}">{{ $s->tabung_type }} (Stok: {{ $s->current_stock }})</option>
                        @endforeach
                    </select>
                    <input type="number" onwheel="this.blur()" name="items[0][quantity]" placeholder="Jumlah" min="1" required
                        class="w-28 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            <button type="button" onclick="addItem()" class="mt-2 text-blue-600 text-sm hover:underline">+ Tambah Item</button>
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">💾 Simpan Penjualan</button>
            <a href="{{ route('admin.sales.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
let idx = 1;
const stocks = @json($stocks->pluck('current_stock', 'tabung_type'));
function addItem() {
    const c = document.getElementById('items-container');
    const opts = Object.entries(stocks).map(([t,s]) => `<option value="${t}">${t} (Stok: ${s})</option>`).join('');
    c.insertAdjacentHTML('beforeend', `
        <div class="flex gap-2 items-center item-row">
            <select name="items[${idx}][tabung_type]" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">Pilih Tipe</option>${opts}
            </select>
            <input type="number" onwheel="this.blur()" name="items[${idx}][quantity]" placeholder="Jumlah" min="1" required class="w-28 px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 text-sm">✕</button>
        </div>`);
    idx++;
}
</script>
@endif
@endsection
