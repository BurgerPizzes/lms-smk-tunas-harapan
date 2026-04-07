@extends('layouts.admin')
@section('title', 'Edit Alokasi')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <a href="{{ route('admin.guru-mapel.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-3">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Alokasi Guru</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ubah alokasi guru ke mata pelajaran dan kelas.</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <form method="POST" action="{{ route('admin.guru-mapel.update', $classGuruMapel->id) }}">
        @csrf @method('PUT')
        <div class="p-6 space-y-5">
            <div>
                <label for="guru_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Guru <span class="text-red-500">*</span></label>
                <select id="guru_id" name="guru_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ $guru->id == $classGuruMapel->guru_id ? 'selected' : '' }}>{{ $guru->name }} - {{ $guru->nip ?? '' }}</option>
                    @endforeach
                </select>
                @error('guru_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="mapel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                <select id="mapel_id" name="mapel_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    <option value="">Pilih Mapel</option>
                    @foreach($mapels as $mapel)
                        <option value="{{ $mapel->id }}" {{ $mapel->id == $classGuruMapel->mapel_id ? 'selected' : '' }}>{{ $mapel->nama }} ({{ $mapel->kode }})</option>
                    @endforeach
                </select>
                @error('mapel_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                <select id="kelas_id" name="kelas_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $kelas->id == $classGuruMapel->class_id ? 'selected' : '' }}>{{ $kelas->nama }} - {{ $kelas->jurusan->nama ?? '' }}</option>
                    @endforeach
                </select>
                @error('kelas_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tahun Ajaran <span class="text-red-500">*</span></label>
                <select id="tahun_ajaran_id" name="tahun_ajaran_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $ta->id == $classGuruMapel->tahun_ajaran_id ? 'selected' : '' }}>{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} {{ ucfirst($ta->semester) }}</option>
                    @endforeach
                </select>
                @error('tahun_ajaran_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3 bg-gray-50 dark:bg-gray-900/50">
            <a href="{{ route('admin.guru-mapel.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
