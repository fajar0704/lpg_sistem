@extends('layouts.admin')

@section('title', 'Detail Sub Pangkalan - Sistem LPG')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.sub-pangkalan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition group mb-3">
                <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Data Sub Pangkalan
            </a>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span>Detail Sub Pangkalan</span>
            </h2>
            <p class="text-slate-500 text-sm mt-1">Informasi lengkap, data identitas KTP pemilik, serta status operasional sub pangkalan.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <!-- Status Badge -->
            @if($subPangkalan->is_active)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Status: Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    Status: Nonaktif
                </span>
            @endif

            <a href="{{ route('admin.sub-pangkalan.edit', $subPangkalan) }}" class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition duration-200 shadow-md shadow-blue-500/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Ubah Data</span>
            </a>
        </div>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
    <div class="max-w-7xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center gap-3 mb-6">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-xl shadow-xs flex items-center gap-3 mb-6">
        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- COLUMN 1 & 2: Main Info Panel -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Pangkalan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-50">
                    <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Profil Sub Pangkalan</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider">Nama Sub Pangkalan</span>
                        <span class="block text-slate-800 text-sm font-bold mt-1">{{ $subPangkalan->name }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider">Nomor Induk Berusaha (NIB)</span>
                        <span class="block text-slate-800 text-sm font-mono font-bold mt-1">{{ $subPangkalan->code }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider">Nomor Telepon</span>
                        <span class="block text-slate-800 text-sm font-bold mt-1">{{ $subPangkalan->phone ?? '-' }}</span>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider">Alamat Pangkalan</span>
                        <span class="block text-slate-800 text-sm font-medium mt-1 leading-relaxed">{{ $subPangkalan->address ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Informasi Akun -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-50">
                    <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Kredensial Akun Login</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider">Alamat Email</span>
                        <span class="block text-slate-800 text-sm font-bold mt-1">{{ $subPangkalan->user->email ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider">Kata Sandi</span>
                        <div class="flex items-center gap-3 mt-1">
                            <span id="password-text" class="text-slate-800 text-sm font-bold tracking-widest">••••••••</span>
                            <form action="{{ route('admin.sub-pangkalan.reset-password', $subPangkalan) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mereset password sub pangkalan ini menjadi \'pangkalan123\'?')" 
                                    class="text-[10px] bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 px-2.5 py-1 rounded-lg border border-rose-100 font-extrabold transition cursor-pointer">
                                    🔄 Reset Password
                                </button>
                            </form>
                        </div>
                    </div>

                    <div>
                        <span class="block text-slate-400 text-xs font-semibold uppercase tracking-wider">Hak Akses Sistem</span>
                        <span class="block text-slate-800 text-sm font-bold mt-1">Sub Pangkalan (Pengecer)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMN 3: Owner Details & photo -->
        <div class="space-y-6">
            <!-- Data Identitas Pemilik -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-50">
                    <div class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Identitas Pemilik (KTP)</h3>
                </div>

                <div class="space-y-4 text-xs font-semibold text-slate-700">
                    <div>
                        <span class="block text-slate-400 font-bold uppercase tracking-wider">NIK KTP</span>
                        <span class="block text-slate-800 font-bold mt-1">{{ $subPangkalan->ktp }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-400 font-bold uppercase tracking-wider">Nama Lengkap Pemilik</span>
                        <span class="block text-slate-800 font-bold mt-1">{{ $subPangkalan->nama_ktp }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-400 font-bold uppercase tracking-wider">Tempat, Tanggal Lahir</span>
                        <span class="block text-slate-800 font-bold mt-1">
                            {{ $subPangkalan->tempat_lahir }}, {{ \Carbon\Carbon::parse($subPangkalan->tanggal_lahir)->format('d F Y') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-slate-400 font-bold uppercase tracking-wider">Jenis Kelamin</span>
                        <span class="block text-slate-800 font-bold mt-1">{{ $subPangkalan->jenis_kelamin }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-400 font-bold uppercase tracking-wider">Alamat KTP</span>
                        <span class="block text-slate-800 font-medium mt-1 leading-relaxed">{{ $subPangkalan->alamat_ktp }}</span>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Foto KTP / Pemilik -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-50">
                    <div class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Foto Dokumentasi KTP</h3>
                </div>

                <div class="flex items-center justify-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden shadow-inner h-48 relative">
                    @if($subPangkalan->photo)
                        <img src="{{ asset('storage/' . $subPangkalan->photo) }}" alt="Foto Dokumentasi KTP / Pemilik" class="w-full h-full object-cover sub-pangkalan-trigger-img cursor-zoom-in hover:scale-[1.03] active:scale-95 transition-all duration-300">
                    @else
                        <div class="flex flex-col items-center justify-center text-slate-400 gap-2 p-4 text-center">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            </svg>
                            <span class="text-xs font-bold">Belum Ada Dokumentasi Foto KTP</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dokumentasi Foto KK -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-50">
                    <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Foto Kartu Keluarga (KK)</h3>
                </div>

                <div class="flex items-center justify-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden shadow-inner h-48 relative">
                    @if($subPangkalan->kk_photo)
                        <img src="{{ asset('storage/' . $subPangkalan->kk_photo) }}" alt="Foto Kartu Keluarga" class="w-full h-full object-cover sub-pangkalan-trigger-img cursor-zoom-in hover:scale-[1.03] active:scale-95 transition-all duration-300">
                    @else
                        <div class="flex flex-col items-center justify-center text-slate-400 gap-2 p-4 text-center">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-xs font-bold">Belum Ada Dokumentasi Foto KK</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Zoom Foto KTP / KK -->
    <div id="sub-pangkalan-zoom-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/80 backdrop-blur-sm transition-all duration-300 opacity-0">
        <button type="button" id="close-sub-pangkalan-zoom-modal" class="absolute top-4 right-4 text-white hover:text-slate-300 transition cursor-pointer p-2.5 bg-slate-900/50 rounded-full" title="Tutup">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="max-w-4xl max-h-[85vh] p-2 flex items-center justify-center">
            <img id="sub-pangkalan-modal-zoomed-img" src="" alt="Foto Zoom" class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl border border-white/10 transform scale-95 transition-transform duration-300">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const triggerImgs = document.querySelectorAll('.sub-pangkalan-trigger-img');
            const modal = document.getElementById('sub-pangkalan-zoom-modal');
            const modalImg = document.getElementById('sub-pangkalan-modal-zoomed-img');
            const closeModalBtn = document.getElementById('close-sub-pangkalan-zoom-modal');

            if (triggerImgs.length > 0 && modal) {
                triggerImgs.forEach(triggerImg => {
                    triggerImg.addEventListener('click', () => {
                        if (modalImg) {
                            modalImg.src = triggerImg.src;
                            modalImg.alt = triggerImg.alt;
                        }
                        modal.classList.remove('hidden');
                        modal.offsetHeight; // Force reflow
                        modal.classList.add('opacity-100');
                        if (modalImg) {
                            modalImg.classList.remove('scale-95');
                            modalImg.classList.add('scale-100');
                        }
                    });
                });

                const closeModal = () => {
                    modal.classList.remove('opacity-100');
                    if (modalImg) {
                        modalImg.classList.remove('scale-100');
                        modalImg.classList.add('scale-95');
                    }
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        if (modalImg) modalImg.src = '';
                    }, 300);
                };

                closeModalBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            }
        });
    </script>
</div>

@endsection
