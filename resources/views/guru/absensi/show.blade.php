@extends('layouts.guru')
@section('title', 'Detail Absensi')
@section('page-content')

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('guru.kelas.show', $attendance->class_id ?? '') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Kembali ke Kelas
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Absensi</h1>
        <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
            <span>{{ $attendance->mapel->nama ?? '-' }}</span>
            <span>&middot;</span>
            <span>{{ $attendance->tanggal?->translatedFormat('d F Y') }}</span>
            <span>&middot;</span>
            <span>Pertemuan {{ $attendance->pertemuan_ke ?? '-' }}</span>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 p-4 text-center">
        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['hadir'] ?? 0 }}</p>
        <p class="text-xs text-green-700 dark:text-green-400 mt-1">Hadir</p>
    </div>
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4 text-center">
        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['izin'] ?? 0 }}</p>
        <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">Izin</p>
    </div>
    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['sakit'] ?? 0 }}</p>
        <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">Sakit</p>
    </div>
    <div class="bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800 p-4 text-center">
        <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['alpha'] ?? 0 }}</p>
        <p class="text-xs text-red-700 dark:text-red-400 mt-1">Alpha</p>
    </div>
</div>

<!-- Attendance Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-10">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nama Siswa</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($detailList ?? [] as $index => $detail)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($detail->siswa->name ?? 'S', 0, 1)) }}</div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $detail->siswa->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-center">
                        @php
                            $statusMap = [
                                'hadir' => ['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400', 'Hadir'],
                                'izin' => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'Izin'],
                                'sakit' => ['bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400', 'Sakit'],
                                'alpha' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'Alpha'],
                            ];
                            $st = $statusMap[$detail->status ?? ''] ?? ['bg-gray-100 text-gray-600', '-'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $st[0] }}">
                            {{ $st[1] }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $detail->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
