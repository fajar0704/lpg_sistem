@extends('layouts.admin')
@section('title', 'Jual ke Pembeli - Sistem LPG')

{{-- Tambahkan library Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Styling agar Select2 mirip dengan Tailwind */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        padding: 5px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
</style>

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Penjualan Langsung ke Pembeli</h2>
    <p class="text-gray-500 text-sm mt-1">Transaksi langsung diproses. Stok isi pangkalan berkurang, stok kosong bertambah.</p>
</div>

{{-- Info Stok --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 max-w-2xl">
    @foreach($stocks as $stock)
    <div class="bg-white p-4 rounded-lg shadow text-center">
        <p class="text-sm font-bold text-gray-700">{{ $stock->tabung_type }}</p>
        <p class="text-2xl font-bold text-green-600">{{ $stock->stok_isi }}</p>
        <p class="text-xs text-gray-400">tabung isi tersedia</p>
    </div>
    @endforeach
</div>

@if($stocks->isEmpty())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 max-w-2xl">
    ⚠️ Tidak ada stok isi tersedia. Terima stok dari Pertamina terlebih dahulu.
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <form action="{{ route('admin.penjualan.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Tabung</label>
                <select name="tabung_type" id="tabung_type" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tabung_type') border-red-500 @enderror">
                    <option value="">Pilih Tipe Tabung</option>
                    @foreach($stocks as $stock)
                    <option value="{{ $stock->tabung_type }}" {{ old('tabung_type') === $stock->tabung_type ? 'selected' : '' }}>
                        {{ $stock->tabung_type }} (Stok isi: {{ $stock->stok_isi }})
                    </option>
                    @endforeach
                </select>
                @error('tabung_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Tabung</label>
                <input type="number" onwheel="this.blur()" name="quantity" value="{{ old('quantity') }}" min="1" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
                @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Cari Pelanggan (NIK / Nama) <span class="text-red-500">*</span></label>
            <div class="flex gap-2">
                <div class="flex-1">
                    <select name="customer_id" id="customer_id" required class="w-full select2-customer">
                        <option value="">Pilih Pelanggan...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->ktp }} - {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('admin.customers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex-shrink-0 flex items-center justify-center" target="_blank" title="Tambah Pelanggan Baru Jika Tidak Ditemukan">
                    + Tambah Pelanggan
                </a>
            </div>
            @error('customer_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Info Pelanggan & Kuota (Hidden by default) --}}
        <div id="customer-info" class="hidden mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="font-bold text-gray-800 mb-3 border-b pb-2">Data Pelanggan & Status Kuota</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="mb-1"><span class="text-gray-500 w-24 inline-block">Nama:</span> <span id="info-nama" class="font-semibold text-gray-800"></span></p>
                    <p class="mb-1"><span class="text-gray-500 w-24 inline-block">NIK:</span> <span id="info-nik" class="font-semibold text-gray-800"></span></p>
                    <p class="mb-1"><span class="text-gray-500 w-24 inline-block">Kategori:</span> <span id="info-kategori" class="font-semibold text-gray-800"></span></p>
                    <p class="mb-1"><span class="text-gray-500 w-24 inline-block">Alamat:</span> <span id="info-alamat" class="font-semibold text-gray-800"></span></p>
                </div>
                <div>
                    <p class="mb-1"><span class="text-gray-500 w-36 inline-block">Jml Pembelian Bulan Ini:</span> <span id="info-used" class="font-semibold text-gray-800"></span></p>
                    <p class="mb-1"><span class="text-gray-500 w-36 inline-block">Batas Maksimal:</span> <span id="info-max" class="font-semibold text-gray-800"></span></p>
                    <p class="mb-1"><span class="text-gray-500 w-36 inline-block">Sisa Kuota:</span> <span id="info-remaining" class="font-semibold text-gray-800"></span></p>
                    <p class="mb-1"><span class="text-gray-500 w-36 inline-block">Status Kuota:</span> <span id="info-status" class="font-bold"></span></p>
                    <p class="mt-2 text-xs text-gray-500 italic" id="info-last"></p>
                </div>
            </div>
            <div id="quota-warning" class="hidden mt-3 text-sm text-red-600 bg-red-100 p-2 rounded">
                ⚠️ Kuota pembelian sudah habis. Transaksi tidak dapat dilanjutkan.
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
            <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Catatan (Opsional)</label>
            <textarea name="notes" rows="2"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-4 text-sm text-blue-700">
            ✅ Penjualan langsung diproses. Stok isi pangkalan akan berkurang dan stok kosong bertambah.
        </div>

        <div class="flex gap-2">
            <button type="submit" id="btn-submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-bold">
                🛒 Proses Penjualan
            </button>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-customer').select2({
            placeholder: "Ketik NIK atau Nama...",
            allowClear: true
        });

        function checkQuota() {
            let customerId = $('#customer_id').val();
            let tabungType = $('#tabung_type').val();

            if(customerId) {
                $.ajax({
                    url: '{{ route("admin.penjualan.check-quota") }}',
                    data: {
                        customer_id: customerId,
                        tabung_type: tabungType
                    },
                    success: function(res) {
                        $('#customer-info').removeClass('hidden');
                        
                        // Isi data pelanggan (readonly)
                        $('#info-nama').text(res.customer.name);
                        $('#info-nik').text(res.customer.ktp);
                        $('#info-kategori').text(res.customer.category_label);
                        $('#info-alamat').text(res.customer.address || '-');
                        
                        // Isi kuota
                        $('#info-used').text(res.used_quota + ' tabung');
                        $('#info-max').text(res.max_quota == 999 ? 'Tanpa Batas' : res.max_quota + ' tabung');
                        $('#info-remaining').text(res.remaining_quota == 999 ? 'Tanpa Batas' : res.remaining_quota + ' tabung');
                        
                        $('#info-status').text(res.status).removeClass().addClass('font-bold ' + res.color);
                        $('#info-last').text('Terakhir beli: ' + res.last_transaction);

                        // Disable tombol jika kuota habis
                        if(res.remaining_quota <= 0) {
                            $('#quota-warning').removeClass('hidden');
                            $('#btn-submit').prop('disabled', true).removeClass('bg-green-600 hover:bg-green-700').addClass('bg-gray-400 cursor-not-allowed');
                        } else {
                            $('#quota-warning').addClass('hidden');
                            $('#btn-submit').prop('disabled', false).removeClass('bg-gray-400 cursor-not-allowed').addClass('bg-green-600 hover:bg-green-700');
                        }
                    },
                    error: function() {
                        $('#customer-info').addClass('hidden');
                    }
                });
            } else {
                $('#customer-info').addClass('hidden');
            }
        }

        $('#customer_id, #tabung_type').on('change', checkQuota);
        
        // Cek kuota saat pertama kali load (jika ada old input)
        if($('#customer_id').val()) {
            checkQuota();
        }
    });
</script>
@endsection
