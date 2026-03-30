@extends('layouts.guru')
@section('title', 'Rekap Absensi - ' . ($kelas->nama ?? ''))
@section('page-content')

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <a href="{{ route('guru.kelas.show', $kelas->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Kembali ke Kelas
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rekap Absensi</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $kelas->nama }} &middot; {{ $kelas->jurusan->nama ?? '-' }}</p>
    </div>
    <a href="{{ route('guru.absensi.export', $kelas->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
        Export CSV
    </a>
</div>

<!-- Filter -->
<div class="flex items-center space-x-3 mb-6">
    <div>
        <label for="mapel-filter" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Filter Mata Pelajaran</label>
        <select id="mapel-filter" onchange="window.location.href='{{ route('guru.absensi.recap', $kelas->id) }}?mapel='+this.value" class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
            <option value="">Semua Mapel</option>
            @foreach($mapels ?? [] as $mapel)
                <option value="{{ $mapel->id }}" {{ (request('mapel') == $mapel->id) ? 'selected' : '' }}>{{ $mapel->nama }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Legend -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6 flex flex-wrap items-center gap-4">
    <div class="flex items-center space-x-2">
        <span class="w-3 h-3 rounded-full bg-green-500"></span>
        <span class="text-xs text-gray-600 dark:text-gray-400">>= 80%</span>
    </div>
    <div class="flex items-center space-x-2">
        <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
        <span class="text-xs text-gray-600 dark:text-gray-400">60% - 79%</span>
    </div>
    <div class="flex items-center space-x-2">
        <span class="w-3 h-3 rounded-full bg-red-500"></span>
        <span class="text-xs text-gray-600 dark:text-gray-400">< 60%</span>
    </div>
</div>

<!-- Recap Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-10 sticky left-0 bg-gray-50 dark:bg-gray-700/50">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase sticky left-10 bg-gray-50 dark:bg-gray-700/50 min-w-[200px]">Nama Siswa</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Hadir</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Izin</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Sakit</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Alpha</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase min-w-[100px]">Persentase</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($recapData ?? [] as $index => $siswa)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 sticky left-0 bg-white dark:bg-gray-800">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 sticky left-10 bg-white dark:bg-gray-800">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 flex-shrink-0">{{ strtoupper(substr($siswa['name'] ?? 'S', 0, 1)) }}</div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $siswa['name'] ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ $siswa['hadir'] ?? 0 }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $siswa['izin'] ?? 0 }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm font-semibold text-yellow-600 dark:text-yellow-400">{{ $siswa['sakit'] ?? 0 }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $siswa['alpha'] ?? 0 }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full {{ ($siswa['persentase'] ?? 0) >= 80 ? 'bg-green-500' : (($siswa['persentase'] ?? 0) >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $siswa['persentase'] ?? 0 }}%"></div>
                            </div>
                            <span class="text-sm font-bold {{ ($siswa['persentase'] ?? 0) >= 80 ? 'text-green-600 dark:text-green-400' : (($siswa['persentase'] ?? 0) >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">{{ $siswa['persentase'] ?? 0 }}%</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if(($siswa['persentase'] ?? 0) >= 80)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Baik</span>
                        @elseif(($siswa['persentase'] ?? 0) >= 60)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Perlu Perhatian</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Kritis</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data rekap absensi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
