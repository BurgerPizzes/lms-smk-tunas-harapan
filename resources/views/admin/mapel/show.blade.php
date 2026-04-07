@extends('layouts.admin')
@section('title', 'Detail Mata Pelajaran')
@section('page-content')

<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
        <a href="{{ route('admin.mapel.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Mata Pelajaran</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <span class="text-gray-900 dark:text-white font-medium">{{ $mapel->nama }}</span>
    </div>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mapel->nama }}</h1>
        <a href="{{ route('admin.mapel.edit', $mapel->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
            Edit
        </a>
    </div>
</div>

<!-- Info Cards -->
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Kode</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white mt-1 font-mono">{{ $mapel->kode }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Kategori</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white mt-1 capitalize">{{ $mapel->kategori }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">KKM</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $mapel->kkm }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Pengampu</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $mapel->guruMapel->count() }}</p>
    </div>
</div>

<!-- Detail Info -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Informasi Mata Pelajaran</h3>
    <div class="space-y-3">
        <div class="flex items-start space-x-3">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-36 flex-shrink-0">Nama</span>
            <span class="text-sm text-gray-900 dark:text-white">{{ $mapel->nama }}</span>
        </div>
        <div class="flex items-start space-x-3">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-36 flex-shrink-0">Deskripsi</span>
            <span class="text-sm text-gray-900 dark:text-white">{{ $mapel->deskripsi ?? '-' }}</span>
        </div>
    </div>
</div>

<!-- Guru Pengampu -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Guru Pengampu</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Guru</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kelas</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tahun Ajaran</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Utama</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($mapel->guruMapel ?? [] as $index => $gm)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                {{ strtoupper(substr($gm->guru->name ?? 'G', 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $gm->guru->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $gm->kelas->nama ?? '-' }}</td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $gm->tahunAjaran->nama ?? '-' }}</td>
                    <td class="px-5 py-4 text-center">
                        @if($gm->is_primary)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">Utama</span>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada guru pengampu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
