@extends('layouts.admin')

@section('title', 'Edit Sub Pangkalan - Sistem LPG')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.sub-pangkalan.index') }}" class="text-blue-600 hover:text-blue-900">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Sub Pangkalan</h2>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
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
            <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Kode Sub Pangkalan</label>
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

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', optional($subPangkalan->user)->email) }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                required>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Kata Sandi (Kosongkan jika tidak ingin diubah)</label>
            <input type="password" name="password" id="password" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                minlength="6">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <hr class="my-6 border-gray-300">
        
        <div class="mb-6">
            <div class="flex flex-col items-center gap-3">
                @if($subPangkalan->photo)
                    <div class="mb-2 text-center" id="current-photo-container">
                        <p class="text-sm text-gray-500 mb-2">Foto Saat Ini:</p>
                        <img src="{{ asset('storage/' . $subPangkalan->photo) }}" alt="Foto KTP" class="max-w-xs rounded-lg border shadow-sm">
                    </div>
                @endif
                <video id="camera-video" width="320" height="240" autoplay class="border rounded-lg bg-gray-200 object-cover hidden"></video>
                <canvas id="camera-canvas" width="320" height="240" class="border rounded-lg hidden"></canvas>
                <div class="flex gap-2 mt-2">
                    <button type="button" id="start-camera-btn" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                        Nyalakan Kamera (Ubah Foto)
                    </button>
                    <button type="button" id="snap-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm hidden">
                        Ambil Foto
                    </button>
                    <button type="button" id="retake-btn" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition text-sm hidden">
                        Ulangi Foto
                    </button>
                </div>
                <input type="hidden" name="photo" id="photo_input">
                @error('photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <script>
            const video = document.getElementById('camera-video');
            const canvas = document.getElementById('camera-canvas');
            const snapBtn = document.getElementById('snap-btn');
            const startCameraBtn = document.getElementById('start-camera-btn');
            const retakeBtn = document.getElementById('retake-btn');
            const photoInput = document.getElementById('photo_input');
            const currentPhotoContainer = document.getElementById('current-photo-container');
            let stream = null;

            startCameraBtn.addEventListener('click', () => {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(mediaStream => { 
                        stream = mediaStream;
                        video.srcObject = stream; 
                        video.classList.remove('hidden');
                        startCameraBtn.classList.add('hidden');
                        snapBtn.classList.remove('hidden');
                        if (currentPhotoContainer) currentPhotoContainer.classList.add('hidden');
                    })
                    .catch(err => { 
                        alert("Gagal mengakses kamera. Pastikan izin kamera telah diberikan.");
                        console.error("Error accessing camera: ", err); 
                    });
            });

            snapBtn.addEventListener('click', () => {
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, 320, 240);
                canvas.classList.remove('hidden');
                video.classList.add('hidden');
                photoInput.value = canvas.toDataURL('image/jpeg', 0.8);
                snapBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                
                if(stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            });

            retakeBtn.addEventListener('click', () => {
                canvas.classList.add('hidden');
                video.classList.remove('hidden');
                photoInput.value = '';
                retakeBtn.classList.add('hidden');
                
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(mediaStream => { 
                        stream = mediaStream;
                        video.srcObject = stream; 
                        snapBtn.classList.remove('hidden');
                    });
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
</div>
@endsection
