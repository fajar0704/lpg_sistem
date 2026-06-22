@extends('layouts.admin')
@section('title', 'Edit Pelanggan')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Pelanggan</h2>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-xl">
    <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
        @csrf @method('PUT')
        @foreach([['name','Nama Lengkap'],['ktp','No. KTP'],['phone','Telepon'],['address','Alamat']] as [$field,$label])
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-1">{{ $label }}</label>
            <input type="text" name="{{ $field }}" value="{{ old($field, $customer->$field) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error($field) border-red-500 @enderror">
            @error($field)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        @endforeach

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-1">Kategori</label>
            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="rumah_tangga" {{ old('category', $customer->category) === 'rumah_tangga' ? 'selected' : '' }}>Rumah Tangga</option>
                <option value="usaha_mikro"  {{ old('category', $customer->category) === 'usaha_mikro'  ? 'selected' : '' }}>Usaha Mikro</option>
                <option value="pengecer"     {{ old('category', $customer->category) === 'pengecer'     ? 'selected' : '' }}>Pengecer</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update</button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection
