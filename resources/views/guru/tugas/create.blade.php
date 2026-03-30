@extends('layouts.guru')
@section('title', 'Buat Tugas Baru')
@section('page-content')

<div class="mb-6">
    <a href="{{ route('guru.kelas.show', $kelas->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-3 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Tugas Baru</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat tugas untuk siswa Anda</p>
</div>

<div class="max-w-3xl">
    <form method="POST" action="{{ route('guru.kelas.tugas.store', $kelas->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Informasi Tugas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Judul Tugas <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Judul tugas">
                    @error('judul') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select id="mapel_id" name="mapel_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Mapel</option>
                        @foreach($mapels ?? [] as $mapel)
                            <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                    @error('mapel_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kelas</label>
                    <input type="text" value="{{ $kelas->nama }}" disabled class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-sm cursor-not-allowed">
                </div>

                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipe Tugas</label>
                    <select id="tipe" name="tipe" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="tugas" {{ old('tipe', 'tugas') === 'tugas' ? 'selected' : '' }}>Tugas</option>
                        <option value="proyek" {{ old('tipe') === 'proyek' ? 'selected' : '' }}>Proyek</option>
                        <option value="quiz" {{ old('tipe') === 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="ujian" {{ old('tipe') === 'ujian' ? 'selected' : '' }}>Ujian</option>
                    </select>
                </div>

                <div>
                    <label for="nilai_maks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nilai Maksimum</label>
                    <input type="number" id="nilai_maks" name="nilai_maks" value="{{ old('nilai_maks', 100) }}" min="1" max="1000" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deadline <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="deadline" name="deadline" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('deadline') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Deskripsi tugas...">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="instruksi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Instruksi</label>
                    <textarea id="instruksi" name="instruksi" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Instruksi pengerjaan tugas...">{{ old('instruksi') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Lampiran</label>
                    <input type="file" name="file" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 file:cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">File pendukung untuk tugas ini. Maks 50MB.</p>
                </div>
            </div>
        </div>

        <!-- Options -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Pengaturan</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Izinkan Pengumpulan Terlambat</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Siswa dapat mengumpulkan setelah deadline</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="allow_late" value="1" {{ old('allow_late') ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Terbitkan Sekarang</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Matikan untuk menyimpan sebagai draft</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <button type="submit" name="action" value="draft" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Simpan Draft</button>
            <button type="submit" name="action" value="publish" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" /></svg>
                Publikasikan
            </button>
        </div>
    </form>
</div>

@endsection
