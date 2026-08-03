@extends('layouts.admin')

@section('title', 'Edit Sub Pangkalan - Sistem LPG')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.sub-pangkalan.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Sub Pangkalan</h2>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <!-- Alert Success / Error -->
    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <p class="text-sm font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    <form action="{{ route('admin.sub-pangkalan.update', $subPangkalan) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Sub Pangkalan</label>
            <input type="text" name="name" id="name" value="{{ old('name', $subPangkalan->name) }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Nomor Induk Berusaha (NIB)</label>
            <input type="text" name="code" id="code" value="{{ old('code', $subPangkalan->code) }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                required>
            @error('code')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
            <textarea name="address" id="address" rows="3" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $subPangkalan->address) }}</textarea>
            @error('address')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Telepon</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $subPangkalan->phone) }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
            @error('phone')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <hr class="my-6 border-gray-300">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Data Sesuai KTP</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="ktp" class="block text-gray-700 text-sm font-bold mb-2">NIK KTP</label>
                <input type="text" name="ktp" id="ktp" value="{{ old('ktp', $subPangkalan->ktp) }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ktp') border-red-500 @enderror"
                    required>
                @error('ktp')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nama_ktp" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap (Sesuai KTP)</label>
                <input type="text" name="nama_ktp" id="nama_ktp" value="{{ old('nama_ktp', $subPangkalan->nama_ktp) }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_ktp') border-red-500 @enderror"
                    required>
                @error('nama_ktp')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tempat_lahir" class="block text-gray-700 text-sm font-bold mb-2">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $subPangkalan->tempat_lahir) }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tempat_lahir') border-red-500 @enderror"
                    required>
                @error('tempat_lahir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tanggal_lahir" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $subPangkalan->tanggal_lahir) }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_lahir') border-red-500 @enderror"
                    required>
                @error('tanggal_lahir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="jenis_kelamin" class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_kelamin') border-red-500 @enderror" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $subPangkalan->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $subPangkalan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="alamat_ktp" class="block text-gray-700 text-sm font-bold mb-2">Alamat KTP</label>
                <textarea name="alamat_ktp" id="alamat_ktp" rows="2" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alamat_ktp') border-red-500 @enderror" required>{{ old('alamat_ktp', $subPangkalan->alamat_ktp) }}</textarea>
                @error('alamat_ktp')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="my-6 border-gray-300">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Akun (Login)</h3>

        @php
            $isPasswordReset = $subPangkalan->user && \Hash::check('pangkalan123', $subPangkalan->user->password);
        @endphp

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', optional($subPangkalan->user)->email) }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror {{ !$isPasswordReset ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                required {{ !$isPasswordReset ? 'readonly' : '' }}>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Kata Sandi (Kosongkan jika tidak ingin diubah)</label>
            <input type="password" name="password" id="password" placeholder="{{ !$isPasswordReset ? 'Lakukan Reset Password terlebih dahulu untuk mengubah kata sandi' : 'Masukkan kata sandi baru' }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror {{ !$isPasswordReset ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                minlength="6" {{ !$isPasswordReset ? 'readonly' : '' }}>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        @if(!$isPasswordReset)
            <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg text-sm flex items-center justify-between gap-3">
                <span>⚠️ Email dan Password dikunci demi keamanan. Silakan klik tombol di sebelah kanan untuk melakukan Reset Password terlebih dahulu.</span>
                <button type="submit" form="reset-password-form" onclick="return confirm('Apakah Anda yakin ingin mereset password sub pangkalan ini menjadi \'pangkalan123\'?')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2 rounded-lg text-xs transition duration-200 whitespace-nowrap shrink-0">
                    🔄 Reset Password
                </button>
            </div>
        @endif

        <hr class="my-6 border-gray-300">
        
        <div class="mb-6 border-b pb-6">
            <div class="flex flex-col items-center gap-3">
                @if($subPangkalan->photo)
                    <div class="mb-2 text-center" id="current-photo-container">
                        <p class="text-sm font-semibold text-slate-700 mb-2">Foto Dokumentasi KTP / Pemilik Saat Ini:</p>
                        <img src="{{ asset('storage/' . $subPangkalan->photo) }}" alt="Foto Dokumentasi KTP" class="max-w-xs rounded-lg border shadow-sm">
                    </div>
                @endif
                <video id="camera-video" width="320" height="240" autoplay class="border rounded-lg bg-gray-200 object-cover hidden"></video>
                <canvas id="camera-canvas" width="320" height="240" class="border rounded-lg hidden"></canvas>
                <div class="flex flex-wrap gap-2 mt-2 items-center justify-center">
                    <button type="button" id="start-camera-btn" style="background-color: #059669 !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm cursor-pointer">
                        Nyalakan Kamera (Ubah Foto Dokumentasi KTP)
                    </button>
                    
                    <label for="fallback-file-input" id="fallback-file-label" style="background-color: #4b5563 !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm cursor-pointer inline-flex items-center">
                        Pilih Foto File
                    </label>
                    <input type="file" id="fallback-file-input" accept="image/*" class="hidden">

                    <button type="button" id="snap-btn" style="background-color: #2563eb !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm hidden cursor-pointer">
                        Ambil Foto
                    </button>
                    <button type="button" id="retake-btn" style="background-color: #d97706 !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm hidden cursor-pointer">
                        Ulangi Foto
                    </button>
                    <button type="button" id="switch-camera-btn" style="background-color: #4b5563 !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm hidden cursor-pointer">
                        Beralih Kamera
                    </button>
                </div>
                <input type="hidden" name="photo" id="photo_input">
                @error('photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <div class="flex flex-col items-center gap-3">
                @if($subPangkalan->kk_photo)
                    <div class="mb-2 text-center" id="kk-current-photo-container">
                        <p class="text-sm font-semibold text-slate-700 mb-2">Foto Kartu Keluarga (KK) Saat Ini:</p>
                        <img src="{{ asset('storage/' . $subPangkalan->kk_photo) }}" alt="Foto Kartu Keluarga" class="max-w-xs rounded-lg border shadow-sm">
                    </div>
                @endif
                <video id="kk-camera-video" width="320" height="240" autoplay class="border rounded-lg bg-gray-200 object-cover hidden"></video>
                <canvas id="kk-camera-canvas" width="320" height="240" class="border rounded-lg hidden"></canvas>
                <div class="flex flex-wrap gap-2 mt-2 items-center justify-center">
                    <button type="button" id="kk-start-camera-btn" style="background-color: #059669 !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm cursor-pointer">
                        Nyalakan Kamera (Ubah Foto KK)
                    </button>
                    
                    <label for="kk-fallback-file-input" id="kk-fallback-file-label" style="background-color: #4b5563 !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm cursor-pointer inline-flex items-center">
                        Pilih Foto File
                    </label>
                    <input type="file" id="kk-fallback-file-input" accept="image/*" class="hidden">

                    <button type="button" id="kk-snap-btn" style="background-color: #2563eb !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm hidden cursor-pointer">
                        Ambil Foto KK
                    </button>
                    <button type="button" id="kk-retake-btn" style="background-color: #d97706 !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm hidden cursor-pointer">
                        Ulangi Foto
                    </button>
                    <button type="button" id="kk-switch-camera-btn" style="background-color: #4b5563 !important; color: #ffffff !important;" class="text-white px-4 py-2 rounded-lg transition text-sm hidden cursor-pointer">
                        Beralih Kamera
                    </button>
                </div>
                <input type="hidden" name="kk_photo" id="kk_photo_input">
                @error('kk_photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <script>
            // --- KTP Camera ---
            const video = document.getElementById('camera-video');
            const canvas = document.getElementById('camera-canvas');
            const snapBtn = document.getElementById('snap-btn');
            const startCameraBtn = document.getElementById('start-camera-btn');
            const retakeBtn = document.getElementById('retake-btn');
            const switchCameraBtn = document.getElementById('switch-camera-btn');
            const photoInput = document.getElementById('photo_input');
            const currentPhotoContainer = document.getElementById('current-photo-container');
            const fallbackFileInput = document.getElementById('fallback-file-input');
            const fallbackFileLabel = document.getElementById('fallback-file-label');
            
            let stream = null;
            let currentFacingMode = 'environment';
            let ktpSource = null; // 'camera' or 'file'

            function startCamera() {
                if (stream) {
                    stopCameraTracks();
                }
                
                ktpSource = 'camera';

                navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: currentFacingMode } } })
                    .then(mediaStream => { 
                        stream = mediaStream;
                        video.srcObject = stream; 
                        video.classList.remove('hidden');
                        startCameraBtn.classList.add('hidden');
                        fallbackFileLabel.classList.add('hidden');
                        snapBtn.classList.remove('hidden');
                        switchCameraBtn.classList.remove('hidden');
                        retakeBtn.classList.remove('hidden');
                        if (currentPhotoContainer) currentPhotoContainer.classList.add('hidden');
                    })
                    .catch(err => { 
                        navigator.mediaDevices.getUserMedia({ video: true })
                            .then(mediaStream => {
                                stream = mediaStream;
                                video.srcObject = stream; 
                                video.classList.remove('hidden');
                                startCameraBtn.classList.add('hidden');
                                fallbackFileLabel.classList.add('hidden');
                                snapBtn.classList.remove('hidden');
                                switchCameraBtn.classList.remove('hidden');
                                retakeBtn.classList.remove('hidden');
                                if (currentPhotoContainer) currentPhotoContainer.classList.add('hidden');
                            })
                            .catch(fallbackErr => {
                                alert("Gagal mengakses kamera KTP. Pastikan izin kamera telah diberikan.");
                                console.error("Error accessing camera: ", fallbackErr); 
                                ktpSource = null;
                            });
                    });
            }

            function stopCameraTracks() {
                if(stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                video.srcObject = null;
            }

            startCameraBtn.addEventListener('click', startCamera);

            switchCameraBtn.addEventListener('click', () => {
                currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
                startCamera();
            });

            snapBtn.addEventListener('click', () => {
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, 320, 240);
                canvas.classList.remove('hidden');
                video.classList.add('hidden');
                photoInput.value = canvas.toDataURL('image/jpeg', 0.8);
                snapBtn.classList.add('hidden');
                switchCameraBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                
                stopCameraTracks();
            });

            retakeBtn.addEventListener('click', () => {
                stopCameraTracks();
                video.classList.add('hidden');
                canvas.classList.add('hidden');
                photoInput.value = '';
                fallbackFileInput.value = '';
                
                if (ktpSource === 'camera' && !canvas.classList.contains('hidden')) {
                    startCamera();
                } else {
                    ktpSource = null;
                    retakeBtn.classList.add('hidden');
                    snapBtn.classList.add('hidden');
                    switchCameraBtn.classList.add('hidden');
                    startCameraBtn.classList.remove('hidden');
                    fallbackFileLabel.classList.remove('hidden');
                    if (currentPhotoContainer) currentPhotoContainer.classList.remove('hidden');
                }
            });

            fallbackFileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    ktpSource = 'file';
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = new Image();
                        img.onload = function() {
                            const context = canvas.getContext('2d');
                            context.drawImage(img, 0, 0, 320, 240);
                            canvas.classList.remove('hidden');
                            photoInput.value = reader.result;
                            
                            startCameraBtn.classList.add('hidden');
                            fallbackFileLabel.classList.add('hidden');
                            retakeBtn.classList.remove('hidden');
                            if (currentPhotoContainer) currentPhotoContainer.classList.add('hidden');
                        };
                        img.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // --- KK Camera ---
            const videoKK = document.getElementById('kk-camera-video');
            const canvasKK = document.getElementById('kk-camera-canvas');
            const snapBtnKK = document.getElementById('kk-snap-btn');
            const startCameraBtnKK = document.getElementById('kk-start-camera-btn');
            const retakeBtnKK = document.getElementById('kk-retake-btn');
            const switchCameraBtnKK = document.getElementById('kk-switch-camera-btn');
            const photoInputKK = document.getElementById('kk_photo_input');
            const currentPhotoContainerKK = document.getElementById('kk-current-photo-container');
            const fallbackFileInputKK = document.getElementById('kk-fallback-file-input');
            const fallbackFileLabelKK = document.getElementById('kk-fallback-file-label');
            
            let streamKK = null;
            let currentFacingModeKK = 'environment';
            let kkSource = null; // 'camera' or 'file'

            function startCameraKK() {
                if (streamKK) {
                    stopCameraTracksKK();
                }
                
                kkSource = 'camera';

                navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: currentFacingModeKK } } })
                    .then(mediaStream => { 
                        streamKK = mediaStream;
                        videoKK.srcObject = streamKK; 
                        videoKK.classList.remove('hidden');
                        startCameraBtnKK.classList.add('hidden');
                        fallbackFileLabelKK.classList.add('hidden');
                        snapBtnKK.classList.remove('hidden');
                        switchCameraBtnKK.classList.remove('hidden');
                        retakeBtnKK.classList.remove('hidden');
                        if (currentPhotoContainerKK) currentPhotoContainerKK.classList.add('hidden');
                    })
                    .catch(err => { 
                        navigator.mediaDevices.getUserMedia({ video: true })
                            .then(mediaStream => {
                                streamKK = mediaStream;
                                videoKK.srcObject = streamKK; 
                                videoKK.classList.remove('hidden');
                                startCameraBtnKK.classList.add('hidden');
                                fallbackFileLabelKK.classList.add('hidden');
                                snapBtnKK.classList.remove('hidden');
                                switchCameraBtnKK.classList.remove('hidden');
                                retakeBtnKK.classList.remove('hidden');
                                if (currentPhotoContainerKK) currentPhotoContainerKK.classList.add('hidden');
                            })
                            .catch(fallbackErr => {
                                alert("Gagal mengakses kamera KK. Pastikan izin kamera telah diberikan.");
                                console.error("Error accessing KK camera: ", fallbackErr); 
                                kkSource = null;
                            });
                    });
            }

            function stopCameraTracksKK() {
                if(streamKK) {
                    streamKK.getTracks().forEach(track => track.stop());
                    streamKK = null;
                }
                videoKK.srcObject = null;
            }

            startCameraBtnKK.addEventListener('click', startCameraKK);

            switchCameraBtnKK.addEventListener('click', () => {
                currentFacingModeKK = currentFacingModeKK === 'environment' ? 'user' : 'environment';
                startCameraKK();
            });

            snapBtnKK.addEventListener('click', () => {
                const context = canvasKK.getContext('2d');
                context.drawImage(videoKK, 0, 0, 320, 240);
                canvasKK.classList.remove('hidden');
                videoKK.classList.add('hidden');
                photoInputKK.value = canvasKK.toDataURL('image/jpeg', 0.8);
                snapBtnKK.classList.add('hidden');
                switchCameraBtnKK.classList.add('hidden');
                retakeBtnKK.classList.remove('hidden');
                
                stopCameraTracksKK();
            });

            retakeBtnKK.addEventListener('click', () => {
                stopCameraTracksKK();
                videoKK.classList.add('hidden');
                canvasKK.classList.add('hidden');
                photoInputKK.value = '';
                fallbackFileInputKK.value = '';
                
                if (kkSource === 'camera' && !canvasKK.classList.contains('hidden')) {
                    startCameraKK();
                } else {
                    kkSource = null;
                    retakeBtnKK.classList.add('hidden');
                    snapBtnKK.classList.add('hidden');
                    switchCameraBtnKK.classList.add('hidden');
                    startCameraBtnKK.classList.remove('hidden');
                    fallbackFileLabelKK.classList.remove('hidden');
                    if (currentPhotoContainerKK) currentPhotoContainerKK.classList.remove('hidden');
                }
            });

            fallbackFileInputKK.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    kkSource = 'file';
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = new Image();
                        img.onload = function() {
                            const context = canvasKK.getContext('2d');
                            context.drawImage(img, 0, 0, 320, 240);
                            canvasKK.classList.remove('hidden');
                            photoInputKK.value = reader.result;
                            
                            startCameraBtnKK.classList.add('hidden');
                            fallbackFileLabelKK.classList.add('hidden');
                            retakeBtnKK.classList.remove('hidden');
                            if (currentPhotoContainerKK) currentPhotoContainerKK.classList.add('hidden');
                        };
                        img.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Update
            </button>
            <a href="{{ route('admin.sub-pangkalan.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                Batal
            </a>
        </div>
    </form>

    <form id="reset-password-form" action="{{ route('admin.sub-pangkalan.reset-password', $subPangkalan) }}" method="POST" class="hidden">
        @csrf
    </form>
</div>
@endsection
