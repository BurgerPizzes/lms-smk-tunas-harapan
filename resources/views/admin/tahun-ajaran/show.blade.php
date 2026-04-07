@extends('layouts.admin')
@section('title', 'Detail Tahun Ajaran')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tahun Ajaran</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <span class="text-gray-900 dark:text-white font-medium">{{ $tahunAjaran->nama }}</span>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tahunAjaran->nama }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }} &mdash; Semester {{ ucfirst($tahunAjaran->semester) }}
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.tahun-ajaran.edit', $tahunAjaran->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                Edit
            </a>
            @if($tahunAjaran->aktif)
            <span class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>Aktif
            </span>
            @else
            <form method="POST" action="{{ route('admin.tahun-ajaran.set-active', $tahunAjaran->id) }}" class="inline" onsubmit="return confirm('Yakin ingin mengaktifkan tahun ajaran ini?')">
                @csrf @method('PUT')
                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Set Aktif
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Alokasi Guru</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['totalAlokasi'] ?? 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kelas Terlibat</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['totalKelas'] ?? 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Guru Terlibat</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['totalGuru'] ?? 0 }}</p>
    </div>
</div>

<!-- Detail Info -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Informasi Detail</h3>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tahun Mulai</p>
                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $tahunAjaran->tahun_mulai }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tahun Selesai</p>
                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $tahunAjaran->tahun_selesai }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Semester</p>
                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ ucfirst($tahunAjaran->semester) }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</p>
                <p class="mt-1">
                    @if($tahunAjaran->aktif)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Nonaktif</span>
                    @endif
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dibuat</p>
                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $tahunAjaran->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Guru Mapel Assignments -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Alokasi Guru Mapel</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Guru</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kelas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($tahunAjaran->guruMapel ?? [] as $index => $gm)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-5 py-3">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $gm->guru->name ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $gm->mapel->nama ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.kelas.show', $gm->kelas_id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">{{ $gm->kelas->nama ?? '-' }}</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        Belum ada alokasi guru untuk tahun ajaran ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
