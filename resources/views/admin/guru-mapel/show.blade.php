@extends('layouts.admin')
@section('title', 'Detail Alokasi')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <a href="{{ route('admin.guru-mapel.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-3">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Alokasi</h1>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Guru</h3>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $classGuruMapel->guru->name ?? '-' }}</p>
                <p class="text-xs text-gray-500">{{ $classGuruMapel->guru->email ?? '' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Mata Pelajaran</h3>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $classGuruMapel->mapel->nama ?? '-' }}</p>
                <p class="text-xs text-gray-500">{{ $classGuruMapel->mapel->kode ?? '' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kelas</h3>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $classGuruMapel->kelas->nama ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tahun Ajaran</h3>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $classGuruMapel->tahunAjaran->tahun_mulai ?? '' }}/{{ $classGuruMapel->tahunAjaran->tahun_selesai ?? '' }} {{ ucfirst($classGuruMapel->tahunAjaran->semester ?? '') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                <p class="mt-1">
                    @if($classGuruMapel->is_primary)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Primary</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">Secondary</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
