@extends('layouts.sub-pangkalan')

@section('title', 'Penjualan ke Pelanggan - Sistem LPG')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Premium Styling for Select2 to match modern UI */
    .select2-container .select2-selection--single {
        height: 48px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        padding-left: 10px !important;
        padding-top: 10px !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        padding-right: 50px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear {
        position: absolute !important;
        right: 36px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 1.1rem !important;
        font-weight: bold !important;
        color: #94a3b8 !important;
        margin-right: 0 !important;
        float: none !important;
        z-index: 2 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear:hover {
        color: #ef4444 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #94a3b8 !important;
    }
    .select2-container--default .select2-selection--single:focus, 
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
        background-color: #ffffff !important;
    }
    .select2-dropdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08) !important;
        overflow: hidden;
    }
</style>

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col gap-2 pb-4 border-b border-slate-200">
        <a href="{{ route('sub-pangkalan.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors w-max">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Dashboard
        </a>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2 mt-1">
            <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Penjualan ke Pelanggan</span>
        </h2>
        <p class="text-slate-500 text-sm">Pencatatan penjualan LPG langsung kepada pelanggan terdaftar. Stok isi berkurang dan stok kosong bertambah secara otomatis.</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center gap-3">
        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Left Sidebar: Info Stok & Guidance -->
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Informasi Stok LPG Anda</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">Real-time</span>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Stok Isi Card -->
                    <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 flex flex-col justify-between h-28 relative overflow-hidden">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-800">Stok Isi Available</span>
                        <div class="flex items-baseline gap-1 mt-auto">
                            <span class="text-3xl font-extrabold tracking-tight text-blue-600">{{ $subPangkalan->stok_isi }}</span>
                            <span class="text-xs font-semibold text-blue-500">tbg</span>
                        </div>
                    </div>

                    <!-- Stok Tabung Kosong Card -->
                    <div class="bg-amber-50/70 border border-amber-100 rounded-xl p-4 flex flex-col justify-between h-28 relative overflow-hidden">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800">Stok Tabung Kosong</span>
                        <div class="flex items-baseline gap-1 mt-auto">
                            <span class="text-3xl font-extrabold tracking-tight text-amber-600">{{ $subPangkalan->stok_kosong }}</span>
                            <span class="text-xs font-semibold text-amber-600">tbg</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($subPangkalan->stok_isi == 0)
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex gap-3 text-rose-800 shadow-xs">
                <svg class="w-5 h-5 shrink-0 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h4 class="font-bold text-sm">Stok LPG Habis!</h4>
                    <p class="text-xs mt-1 text-rose-600 leading-relaxed">Stok LPG Anda saat ini habis. Silakan tunggu konfirmasi pasokan dari Pangkalan sebelum dapat mencatat transaksi penjualan baru.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Side: Form Pencatatan Penjualan -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                <form action="{{ route('sub-pangkalan.sell.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Pilih Pelanggan -->
                    <div>
                        <label for="customer_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pilih Pelanggan <span class="text-rose-500">*</span></label>
                        <select name="customer_id" id="customer_id" required class="w-full select2-customer">
                            <option value="">Cari NIK / Nama Pelanggan...</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} (NIK: {{ $customer->ktp }})
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Tipe Tabung -->
                        <div>
                            <label for="tabung_type" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Tipe Tabung LPG</label>
                            <select name="tabung_type" id="tabung_type" required
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-[48px] font-semibold">
                                <option value="3kg" selected>Tabung 3kg</option>
                            </select>
                            @error('tabung_type')
                                <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Tabung Terjual -->
                        <div>
                            <label for="quantity" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Jumlah Tabung Terjual</label>
                            <input type="number" id="quantity" onwheel="this.blur()" name="quantity" value="{{ old('quantity') }}" placeholder="Masukkan jumlah tabung" min="1" max="{{ $subPangkalan->stok_isi }}" required
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-[48px] font-semibold @error('quantity') border-rose-400 focus:ring-rose-500/20 @enderror">
                            <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Batas Maksimal: <span class="font-bold text-slate-600">{{ $subPangkalan->stok_isi }}</span> tabung</p>
                            @error('quantity')
                                <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label for="transaction_date" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Tanggal Transaksi</label>
                        <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-[48px] font-semibold">
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Catatan Penjualan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Masukkan keterangan tambahan jika ada..."
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-slate-400 font-medium">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" {{ $subPangkalan->stok_isi == 0 ? 'disabled' : '' }}
                            style="background-color: #2563eb !important; color: #ffffff !important;"
                            class="inline-flex items-center gap-1.5 text-white font-bold px-6 py-3 rounded-xl transition duration-200 text-sm cursor-pointer shadow-md shadow-blue-500/10 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Proses Penjualan</span>
                        </button>
                        <a href="{{ route('sub-pangkalan.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition text-sm flex items-center justify-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-customer').select2({
            placeholder: "Ketik NIK atau Nama Pelanggan...",
            allowClear: true,
            minimumInputLength: 1,
            language: {
                inputTooShort: function () {
                    return "Ketik minimal 1 huruf/inisial untuk mencari pelanggan...";
                },
                searching: function () {
                    return "Mencari pelanggan...";
                },
                noResults: function () {
                    return "Pelanggan tidak ditemukan";
                }
            }
        });
    });
</script>
@endsection
