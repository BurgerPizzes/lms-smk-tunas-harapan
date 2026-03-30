@extends('layouts.admin')
@section('title', 'Edit Tahun Ajaran')
@section('page-content')

<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tahun Ajaran</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <span class="text-gray-900 dark:text-white font-medium">Edit</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Tahun Ajaran: {{ $tahunAjaran->nama }}</h1>
</div>

<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('admin.tahun-ajaran.update', $tahunAjaran->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Tahun Ajaran <span class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $tahunAjaran->nama) }}" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tahun_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tahun Mulai <span class="text-red-500">*</span></label>
                        <input type="number" id="tahun_mulai" name="tahun_mulai" value="{{ old('tahun_mulai', $tahunAjaran->tahun_mulai) }}" required min="2000" max="2100" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                        @error('tahun_mulai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tahun_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tahun Selesai <span class="text-red-500">*</span></label>
                        <input type="number" id="tahun_selesai" name="tahun_selesai" value="{{ old('tahun_selesai', $tahunAjaran->tahun_selesai) }}" required min="2000" max="2100" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                        @error('tahun_selesai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Semester <span class="text-red-500">*</span></label>
                    <select id="semester" name="semester" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                        <option value="1" {{ old('semester', $tahunAjaran->semester) === '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ old('semester', $tahunAjaran->semester) === '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                    @error('semester') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tahunAjaran->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-gray-500 peer-checked:bg-indigo-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Ajaran Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.tahun-ajaran.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection
