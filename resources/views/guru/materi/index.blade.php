@extends('layouts.guru')
@section('title', 'Materi')
@section('page-content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Materi Pembelajaran</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih kelas untuk mengelola materi</p>
</div>

<!-- Kelas List -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($kelasList ?? [] as $kelas)
    <a href="{{ route('guru.kelas.materi.index', $kelas->id) }}" class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-600 transition-all group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl flex items-center justify-center group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/60 transition-colors">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kelas->nama }}</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $kelas->jurusan->nama ?? '-' }} &middot; {{ $kelas->siswas_count ?? $kelas->siswas->count() }} siswa</p>
    </a>
    @empty
    <div class="col-span-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 py-16 text-center">
        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum ada kelas</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Hubungi admin untuk ditugaskan ke kelas.</p>
    </div>
    @endforelse
</div>

@endsection
