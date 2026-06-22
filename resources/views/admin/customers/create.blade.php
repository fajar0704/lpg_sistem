@extends('layouts.admin')
@section('title', 'Tambah Pelanggan')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Tambah Pelanggan</h2>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-xl">
    <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf
        @foreach([['name','Nama Lengkap','text'],['ktp','No. KTP','text'],['phone','Telepon','text'],['address','Alamat','text']] as [$field,$label,$type])
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-1">{{ $label }}</label>
            <input type="{{ $type }}" name="{{ $field }}" value="{{ old($field) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error($field) border-red-500 @enderror">
            @error($field)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        @endforeach

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-1">Kategori</label>
            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                <option value="">Pilih Kategori</option>
                <option value="rumah_tangga" {{ old('category') === 'rumah_tangga' ? 'selected' : '' }}>Rumah Tangga</option>
                <option value="usaha_mikro"  {{ old('category') === 'usaha_mikro'  ? 'selected' : '' }}>Usaha Mikro</option>
                <option value="pengecer"     {{ old('category') === 'pengecer'     ? 'selected' : '' }}>Pengecer</option>
            </select>
            @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection
