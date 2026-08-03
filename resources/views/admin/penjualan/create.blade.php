@extends('layouts.admin')

@section('title', 'Jual ke Pembeli - Sistem LPG')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Premium Styling for Select2 to match modern UI */
    .select2-container .select2-selection--single {
        height: 48px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        padding-left: 10px !important;
        padding-top: 8px !important;
        background-color: #ffffff !important;
        transition: all 0.2s ease;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-weight: 500 !important;
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
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
    }
    .select2-dropdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }
</style>

<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
            <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Dashboard
        </a>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
            <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Jual ke Pembeli
        </h2>
        <p class="text-slate-500 text-sm mt-1">Transaksi langsung diproses. Stok isi pangkalan berkurang, stok kosong bertambah.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Stok Panel -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($stocks as $stock)
        <div class="bg-white p-5 rounded-2xl shadow-sm border {{ $stock->stok_isi <= 0 ? 'border-rose-100 bg-rose-50/10' : 'border-slate-100' }} flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Tabung {{ $stock->tabung_type }}</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stock->stok_isi }} <span class="text-xs font-medium text-slate-400">Tabung</span></p>
                @if($stock->stok_isi <= 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 mt-2">
                        ⚠️ Stok Habis!
                    </span>
                @endif
            </div>
            <span class="w-2.5 h-2.5 rounded-full {{ ($stock->stok_isi <= 0 || $stock->isBelowSafety()) ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></span>
        </div>
        @endforeach
    </div>

    @if($stocks->isEmpty())
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-xs flex items-center gap-3">
        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <p class="text-sm font-semibold">Tidak ada stok isi tersedia. Terima stok dari Pertamina terlebih dahulu.</p>
    </div>
    @endif

    <!-- Form Section -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('admin.penjualan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Tipe Tabung & Jumlah -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tabung_type" class="block text-slate-700 text-sm font-semibold mb-2">Tipe Tabung</label>
                    <select name="tabung_type" id="tabung_type" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 bg-white font-medium @error('tabung_type') border-rose-500 focus:ring-rose-500/20 @enderror">
                        <option value="">Pilih Tipe Tabung</option>
                        @foreach($stocks as $stock)
                        <option value="{{ $stock->tabung_type }}" {{ old('tabung_type') === $stock->tabung_type ? 'selected' : '' }} {{ $stock->stok_isi <= 0 ? 'disabled' : '' }}>
                            Tabung {{ $stock->tabung_type }} (Sisa: {{ $stock->stok_isi }}{{ $stock->stok_isi <= 0 ? ' - Stok Habis' : '' }})
                        </option>
                        @endforeach
                    </select>
                    @error('tabung_type')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="quantity" class="block text-slate-700 text-sm font-semibold mb-2">Jumlah Tabung</label>
                    <input type="number" onwheel="this.blur()" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1" required
                        placeholder="Contoh: 1"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium @error('quantity') border-rose-500 focus:ring-rose-500/20 @enderror">
                    @error('quantity')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Kategori Pelanggan -->
            <div>
                <label for="customer_category" class="block text-slate-700 text-sm font-semibold mb-2">Kategori Pelanggan</label>
                <select id="customer_category"
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 bg-white font-medium">
                    <option value="">Semua Kategori</option>
                    <option value="rumah_tangga">🏠 Rumah Tangga</option>
                    <option value="usaha_mikro">🏪 UMKM (Usaha Mikro)</option>
                    <option value="pengecer">🏢 Sub Pangkalan (Pengecer)</option>
                    <option value="konsumen_umum">🏢 Konsumen Umum (Pembeli Non Subsidi)</option>
                </select>
            </div>

            <!-- Cari Pelanggan -->
            <div>
                <label for="customer_id" class="block text-slate-700 text-sm font-semibold mb-2">Cari Pelanggan (NIK / Nama) <span class="text-rose-500">*</span></label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <select name="customer_id" id="customer_id" required class="w-full select2-customer">
                            @if(old('customer_id'))
                                @php
                                    $oldId = old('customer_id');
                                    $oldName = '';
                                    $oldKtp = '';
                                    if (str_starts_with($oldId, 'sub_')) {
                                        $sub = \App\Models\SubPangkalan::find(str_replace('sub_', '', $oldId));
                                        if ($sub) {
                                            $oldName = $sub->name;
                                            $oldKtp = $sub->ktp ?: $sub->code;
                                        }
                                    } else {
                                        $cust = \App\Models\Customer::find(str_replace('cust_', '', $oldId));
                                        if ($cust) {
                                            $oldName = $cust->name;
                                            $oldKtp = $cust->ktp;
                                        }
                                    }
                                @endphp
                                @if($oldName)
                                    <option value="{{ $oldId }}" selected>{{ $oldKtp }} - {{ $oldName }}</option>
                                @else
                                    <option value="">Pilih Pelanggan...</option>
                                @endif
                            @else
                                <option value="">Pilih Pelanggan...</option>
                            @endif
                        </select>
                    </div>
                    <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center justify-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold px-5 py-3 rounded-xl transition shrink-0" target="_blank">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span>+ Pelanggan</span>
                    </a>
                </div>
                @error('customer_id')<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Info Pelanggan & Kuota -->
            <div id="customer-info" class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-4">
                <h3 class="font-extrabold text-slate-800 text-sm tracking-tight border-b border-slate-200 pb-2.5">Data Pelanggan & Status Kuota</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-semibold text-slate-700">
                    <div class="space-y-2">
                        <p class="flex items-start gap-1.5"><span class="text-slate-400 w-16 sm:w-20 shrink-0">Nama:</span> <span id="info-nama" class="text-slate-800 break-words flex-1"></span></p>
                        <p class="flex items-start gap-1.5"><span class="text-slate-400 w-16 sm:w-20 shrink-0">NIK:</span> <span id="info-nik" class="text-slate-800 break-words flex-1"></span></p>
                        <p class="flex items-start gap-1.5"><span class="text-slate-400 w-16 sm:w-20 shrink-0">Kategori:</span> <span id="info-kategori" class="text-slate-800 break-words flex-1"></span></p>
                        <p class="flex items-start gap-1.5"><span class="text-slate-400 w-16 sm:w-20 shrink-0">Alamat:</span> <span id="info-alamat" class="text-slate-800 break-words flex-1"></span></p>
                    </div>
                    <div class="space-y-2">
                        <p class="flex items-start gap-1.5"><span id="label-used" class="text-slate-400 w-24 sm:w-28 shrink-0">Bulan Ini:</span> <span id="info-used" class="text-slate-800 break-words flex-1"></span></p>
                        <p class="flex items-start gap-1.5"><span class="text-slate-400 w-24 sm:w-28 shrink-0">Batas Maks:</span> <span id="info-max" class="text-slate-800 break-words flex-1"></span></p>
                        <p class="flex items-start gap-1.5"><span class="text-slate-400 w-24 sm:w-28 shrink-0">Sisa Kuota:</span> <span id="info-remaining" class="text-slate-800 break-words flex-1"></span></p>
                        <p class="flex items-start gap-1.5"><span class="text-slate-400 w-24 sm:w-28 shrink-0">Status Kuota:</span> <span id="info-status" class="font-bold break-words flex-1"></span></p>
                    </div>
                </div>
                <div class="pt-2 border-t border-slate-100 flex justify-between items-center text-[10px] text-slate-400 italic">
                    <span id="info-last"></span>
                </div>
                <div id="quota-warning" class="hidden p-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-xl font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>Kuota pembelian sudah habis. Transaksi tidak dapat dilanjutkan.</span>
                </div>
            </div>

            <!-- Tanggal -->
            <div>
                <label for="transaction_date" class="block text-slate-700 text-sm font-semibold mb-2">Tanggal</label>
                <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 font-medium">
            </div>

            <!-- Catatan -->
            <div>
                <label for="notes" class="block text-slate-700 text-sm font-semibold mb-2">Catatan (Opsional)</label>
                <textarea name="notes" id="notes" rows="2" placeholder="Catatan transaksi..."
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-200 text-slate-800 placeholder-slate-400 font-medium">{{ old('notes') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" id="btn-submit" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-green-500/10 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                    Proses Penjualan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-3 rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
        </div>
        
        <!-- Kolom Kanan: Info Kategori -->
        <div class="lg:col-span-1">
            <div class="bg-blue-50/50 p-6 sm:p-8 rounded-2xl border border-blue-100 shadow-sm sticky top-6">
                <h3 class="font-extrabold text-blue-800 text-base mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Panduan Kategori Pelanggan
                </h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="text-xl leading-none mt-0.5">🏠</span>
                        <div>
                            <strong class="text-slate-800 text-sm block">Rumah Tangga</strong>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">Keluarga atau masyarakat yang menggunakan LPG 3 Kg bersubsidi untuk keperluan memasak dapur sehari-hari secara wajar.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-xl leading-none mt-0.5">🏪</span>
                        <div>
                            <strong class="text-slate-800 text-sm block">UMKM (Usaha Mikro)</strong>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">Pelaku usaha skala mikro produktif (seperti pedagang kaki lima, rumah makan kecil) yang berhak menggunakan LPG 3 Kg bersubsidi.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-xl leading-none mt-0.5">🏪</span>
                        <div>
                            <strong class="text-slate-800 text-sm block">Sub Pangkalan (Pengecer)</strong>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">Mitra penyalur atau toko pengecer yang mendistribusikan kembali LPG kepada konsumen akhir di tingkat wilayah/desa.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-xl leading-none mt-0.5">🏢</span>
                        <div>
                            <strong class="text-slate-800 text-sm block">Konsumen Umum (Non-Subsidi)</strong>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">Pelanggan menengah/besar, restoran, hotel, industri, atau masyarakat mampu yang diwajibkan membeli produk LPG Non-Subsidi (misalnya Bright Gas 5.5 Kg atau Elpiji 12 Kg).</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi awal Select2 dengan AJAX
        $('.select2-customer').select2({
            width: '100%',
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
            },
            ajax: {
                url: '{{ route("admin.penjualan.search-customers") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        category: $('#customer_category').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.ktp + ' - ' + item.name
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Filter Pelanggan Berdasarkan Kategori
        $('#customer_category').on('change', function() {
            const selectedCategory = $(this).val();
            
            // Kosongkan pilihan pelanggan saat kategori diubah
            $('#customer_id').val(null).trigger('change');
            
            // Logika untuk Tabung
            if (selectedCategory === 'konsumen_umum') {
                $('#tabung_type option[value="3kg"]').prop('disabled', true).hide();
                if ($('#tabung_type').val() === '3kg') {
                    $('#tabung_type').val('').trigger('change');
                }
            } else {
                $('#tabung_type option[value="3kg"]').prop('disabled', false).show();
            }
        });

        function checkQuota() {
            let customerId = $('#customer_id').val();
            let tabungType = $('#tabung_type').val();

            if (customerId) {
                $.ajax({
                    url: '{{ route("admin.penjualan.check-quota") }}',
                    data: {
                        customer_id: customerId,
                        tabung_type: tabungType
                    },
                    success: function(res) {
                        $('#customer-info').removeClass('hidden');
                        
                        // Isi data pelanggan
                        $('#info-nama').text(res.customer.name);
                        $('#info-nik').text(res.customer.ktp);
                        $('#info-kategori').text(res.customer.category_label);
                        $('#info-alamat').text(res.customer.address || '-');
                        
                        // Isi kuota
                        let labelUsed = res.quota_label ? res.quota_label : 'Bulan Ini';
                        $('#label-used').text(labelUsed + ':');
                        $('#info-used').text(res.used_quota + ' tabung');
                        $('#info-max').text(res.max_quota == 999 ? 'Tanpa Batas' : res.max_quota + ' tabung');
                        $('#info-remaining').text(res.max_quota == 999 ? 'Tanpa Batas' : res.remaining_quota + ' tabung');
                        
                        $('#info-status').text(res.status).removeClass().addClass('font-bold ' + res.color);
                        $('#info-last').text('Terakhir beli: ' + res.last_transaction);

                        // Disable tombol submit jika kuota habis
                        if (res.remaining_quota <= 0) {
                            $('#quota-warning').removeClass('hidden');
                            $('#btn-submit').prop('disabled', true)
                                .removeClass('from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700')
                                .addClass('bg-slate-300 text-slate-400 cursor-not-allowed shadow-none');
                        } else {
                            $('#quota-warning').addClass('hidden');
                            $('#btn-submit').prop('disabled', false)
                                .removeClass('bg-slate-300 text-slate-400 cursor-not-allowed shadow-none')
                                .addClass('bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700');
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
        
        // Cek kuota jika ada data default terpilih (misal old input)
        if ($('#customer_id').val()) {
            checkQuota();
        }
    });
</script>
@endsection
