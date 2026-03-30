@extends('layouts.guru')
@section('title', 'Rekap Nilai - ' . ($kelas->nama ?? ''))
@section('page-content')

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <a href="{{ route('guru.kelas.show', $kelas->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Kembali ke Kelas
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rekap Nilai</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $kelas->nama }} &middot; {{ $kelas->jurusan->nama ?? '-' }}</p>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('guru.penilaian.export'), $kelas->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
            <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            Export
        </a>
    </div>
</div>

<!-- Filter -->
<div class="flex items-center space-x-3 mb-6">
    <div>
        <label for="mapel-filter" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Filter Mata Pelajaran</label>
        <select id="mapel-filter" onchange="window.location.href='{{ route('guru.penilaian.recap', $kelas->id) }}?mapel='+this.value" class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
            <option value="">Semua Mapel</option>
            @foreach($mapels ?? [] as $mapel)
                <option value="{{ $mapel->id }}" {{ (request('mapel') == $mapel->id) ? 'selected' : '' }}>{{ $mapel->nama }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- KKM Info -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6 flex items-center space-x-4">
    <div class="flex items-center space-x-2">
        <span class="w-3 h-3 rounded-full bg-green-500"></span>
        <span class="text-xs text-gray-600 dark:text-gray-400">Lulus (>= KKM)</span>
    </div>
    <div class="flex items-center space-x-2">
        <span class="w-3 h-3 rounded-full bg-red-500"></span>
        <span class="text-xs text-gray-600 dark:text-gray-400">Tidak Lulus (< KKM)</span>
    </div>
    <span class="text-xs text-gray-500 dark:text-gray-400">KKM: <strong class="text-gray-900 dark:text-white">{{ $kkm ?? 75 }}</strong></span>
</div>

<!-- Recap Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-10 sticky left-0 bg-gray-50 dark:bg-gray-700/50">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase sticky left-10 bg-gray-50 dark:bg-gray-700/50 min-w-[200px]">Nama Siswa</th>
                    @foreach($tugasList ?? [] as $tugas)
                    <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase min-w-[80px]">
                        <span class="block truncate max-w-[100px] mx-auto" title="{{ $tugas->judul }}">{{ Str::limit($tugas->judul, 15) }}</span>
                    </th>
                    @endforeach
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase bg-indigo-50 dark:bg-indigo-900/20 min-w-[80px]">Rata-rata</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase min-w-[90px]">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($siswaList ?? [] as $index => $siswa)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 sticky left-0 bg-white dark:bg-gray-800">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 sticky left-10 bg-white dark:bg-gray-800">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 flex-shrink-0">{{ strtoupper(substr($siswa->name ?? 'S', 0, 1)) }}</div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $siswa->name }}</span>
                        </div>
                    </td>
                    @foreach($tugasList ?? [] as $tugas)
                    <td class="px-3 py-3 text-center">
                        @php $nilai = $siswa->nilai_map[$tugas->id] ?? null; @endphp
                        @if($nilai !== null)
                            <span class="inline-block min-w-[36px] px-1.5 py-0.5 text-sm font-semibold rounded {{ $nilai >= ($kkm ?? 75) ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $nilai }}
                            </span>
                        @else
                            <span class="text-sm text-gray-300 dark:text-gray-600">-</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-4 py-3 text-center bg-indigo-50/50 dark:bg-indigo-900/10">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $siswa->rata_rata ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if(isset($siswa->rata_rata) && $siswa->rata_rata !== null)
                            @if($siswa->rata_rata >= ($kkm ?? 75))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                    Lulus
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                    Tidak
                                </span>
                            @endif
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 4 + ($tugasList->count() ?? 0) }}" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
