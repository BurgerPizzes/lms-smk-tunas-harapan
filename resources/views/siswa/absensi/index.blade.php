@extends('layouts.siswa')

@section('title', 'Absensi Saya')

@section('page-content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kehadiran Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Rekap kehadiran di semua kelas</p>
    </div>

    {{-- Overall Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Hadir</p>
                    <p class="text-xl font-bold text-gray-900">{{ $overallStats['hadir'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Izin</p>
                    <p class="text-xl font-bold text-gray-900">{{ $overallStats['izin'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 11-12.728 0M12 2v9"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Sakit</p>
                    <p class="text-xl font-bold text-gray-900">{{ $overallStats['sakit'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Alpha</p>
                    <p class="text-xl font-bold text-gray-900">{{ $overallStats['alpha'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Per Class Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($kelasAbsensi as $ka)
            @php
                $persen = $ka['persentase'] ?? 0;
                $colorClass = $persen >= 80 ? 'green' : ($persen >= 60 ? 'yellow' : 'red');
            @endphp
            <a href="{{ route('siswa.absensi.by-kelas', $ka['kelas_id']) }}" class="block bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                {{-- Class Name --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $ka['cover_color'] ?? '#4F46E5' }}">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $ka['nama_kelas'] }}</h3>
                        <p class="text-xs text-gray-500">{{ $ka['jurusan'] ?? '' }}</p>
                    </div>
                </div>

                {{-- Percentage --}}
                <div class="flex items-end justify-between mb-2">
                    <span class="text-3xl font-bold text-{{ $colorClass }}-600">{{ number_format($persen, 1) }}%</span>
                    <span class="text-xs text-gray-400">{{ $ka['total_pertemuan'] }} pertemuan</span>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all bg-{{ $colorClass }}-500" style="width: {{ min($persen, 100) }}%"></div>
                </div>

                {{-- Stats Row --}}
                <div class="flex items-center justify-between mt-4 text-xs">
                    <span class="text-green-600 font-medium">{{ $ka['hadir'] }} hadir</span>
                    <span class="text-blue-600 font-medium">{{ $ka['izin'] }} izin</span>
                    <span class="text-yellow-600 font-medium">{{ $ka['sakit'] }} sakit</span>
                    <span class="text-red-600 font-medium">{{ $ka['alpha'] }} alpha</span>
                </div>
            </a>
        @empty
            <div class="col-span-full bg-white rounded-xl border border-gray-100 py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <p class="mt-3 text-gray-500 text-sm">Belum ada data absensi</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
