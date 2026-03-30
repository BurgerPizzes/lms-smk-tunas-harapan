@extends('layouts.guru')
@section('title', 'Edit Tugas - ' . ($tugas->judul ?? ''))
@section('page-content')

<div class="mb-6">
    <a href="{{ route('guru.tugas.show', $tugas->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-3 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Detail Tugas
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Tugas</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui informasi tugas</p>
</div>

<div class="max-w-3xl">
    <form method="POST" action="{{ route('guru.tugas.update', $tugas->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Informasi Tugas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Judul Tugas <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $tugas->judul) }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('judul') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select id="mapel_id" name="mapel_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Mapel</option>
                        @foreach($mapels ?? [] as $mapel)
                            <option value="{{ $mapel->id }}" {{ (old('mapel_id', $tugas->mapel_id) == $mapel->id) ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <select id="kelas_id" name="kelas_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList ?? [] as $k)
                            <option value="{{ $k->id }}" {{ (old('kelas_id', $tugas->class_id) == $k->id) ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipe Tugas</label>
                    <select id="tipe" name="tipe" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="tugas" {{ old('tipe', $tugas->tipe) === 'tugas' ? 'selected' : '' }}>Tugas</option>
                        <option value="proyek" {{ old('tipe', $tugas->tipe) === 'proyek' ? 'selected' : '' }}>Proyek</option>
                        <option value="quiz" {{ old('tipe', $tugas->tipe) === 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="ujian" {{ old('tipe', $tugas->tipe) === 'ujian' ? 'selected' : '' }}>Ujian</option>
                    </select>
                </div>

                <div>
                    <label for="nilai_maks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nilai Maksimum</label>
                    <input type="number" id="nilai_maks" name="nilai_maks" value="{{ old('nilai_maks', $tugas->nilai_maks ?? 100) }}" min="1" max="1000" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deadline <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="deadline" name="deadline" value="{{ old('deadline', $tugas->deadline?->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('deadline') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="instruksi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Instruksi</label>
                    <textarea id="instruksi" name="instruksi" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('instruksi', $tugas->instruksi) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Lampiran</label>
                    @if($tugas->file)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">File saat ini: <a href="{{ Storage::url($tugas->file) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ pathinfo($tugas->file, PATHINFO_BASENAME) }}</a></p>
                    @endif
                    <input type="file" name="file" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 file:cursor-pointer">
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
                    <input type="checkbox" name="allow_late" value="1" {{ old('allow_late', $tugas->allow_late) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Terbitkan</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Matikan untuk menyimpan sebagai draft</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $tugas->is_published) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('guru.tugas.show', $tugas->id) }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Batal</a>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection
