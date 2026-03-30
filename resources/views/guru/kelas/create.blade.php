@extends('layouts.guru')
@section('title', 'Buat Kelas Baru')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <a href="{{ route('guru.kelas.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-3 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Kelas Saya
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Kelas Baru</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Isi informasi kelas yang akan Anda buat</p>
</div>

<!-- Form -->
<div class="max-w-3xl">
    <form method="POST" action="{{ route('guru.kelas.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Cover Image Upload -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Gambar Sampul</h3>
            <div class="flex items-center space-x-6">
                <div id="cover-preview" class="w-48 h-32 bg-gray-100 dark:bg-gray-700 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500 mx-auto mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Preview</p>
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Gambar</label>
                    <input type="file" name="cover_image" accept="image/*" onchange="document.getElementById('cover-preview').innerHTML='<img src='+URL.createObjectURL(this.files[0])+' class=\'w-full h-full object-cover\'>'" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 file:cursor-pointer file:transition-colors cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, atau WEBP. Maks 2MB.</p>
                </div>
            </div>
        </div>

        <!-- Info Kelas -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Informasi Kelas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Nama Kelas -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Contoh: X RPL 1">
                    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Jurusan -->
                <div>
                    <label for="jurusan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jurusan <span class="text-red-500">*</span></label>
                    <select id="jurusan_id" name="jurusan_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Pilih Jurusan</option>
                        @foreach($jurusans ?? [] as $jurusan)
                            <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama }}</option>
                        @endforeach
                    </select>
                    @error('jurusan_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select id="tahun_ajaran" name="tahun_ajaran" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach($tahunAjarans ?? [] as $ta)
                            <option value="{{ $ta->id ?? $ta }}" {{ old('tahun_ajaran') == ($ta->id ?? $ta) ? 'selected' : '' }}>{{ $ta->nama ?? $ta }}</option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Tingkat -->
                <div>
                    <label for="tingkat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tingkat <span class="text-red-500">*</span></label>
                    <select id="tingkat" name="tingkat" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Pilih Tingkat</option>
                        <option value="10" {{ old('tingkat') == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ old('tingkat') == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ old('tingkat') == '12' ? 'selected' : '' }}>Kelas 12</option>
                    </select>
                    @error('tingkat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Ruangan -->
                <div>
                    <label for="ruangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Ruangan</label>
                    <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Contoh: RPL Lab 1">
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kapasitas</label>
                    <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', 36) }}" min="1" max="100" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <!-- Kode Unik (Auto-generated) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kode Unik</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="kode_unik" name="kode_unik" value="{{ old('kode_unik', $kodeUnik ?? strtoupper(Str::random(6))) }}" readonly class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-900 dark:text-white text-sm font-mono cursor-not-allowed">
                        <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('kode_unik').value)" class="px-3 py-2.5 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors" title="Salin kode">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Bagikan kode ini kepada siswa untuk bergabung</p>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Deskripsi singkat tentang kelas ini...">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('guru.kelas.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Buat Kelas
            </button>
        </div>
    </form>
</div>

@endsection
