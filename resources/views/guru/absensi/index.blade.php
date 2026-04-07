@extends('layouts.guru')
@section('title', 'Absensi')
@section('page-content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Absensi</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih kelas untuk mengelola absensi</p>
</div>

<!-- Kelas List -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($kelasList ?? [] as $kelas)
    <a href="{{ route('guru.kelas.absensi.index', $kelas->id) }}" class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-600 transition-all group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 dark:group-hover:bg-emerald-900/60 transition-colors">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kelas->nama }}</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $kelas->jurusan->nama ?? '-' }} &middot; {{ $kelas->siswas_count ?? $kelas->siswas->count() }} siswa</p>
    </a>
    @empty
    <div class="col-span-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 py-16 text-center">
        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum ada kelas</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Hubungi admin untuk ditugaskan ke kelas.</p>
    </div>
    @endforelse
</div>

@endsection
