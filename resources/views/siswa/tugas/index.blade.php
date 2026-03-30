@extends('layouts.siswa')

@section('title', 'Tugas Kelas')

@section('page-content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('siswa.kelas.index') }}" class="hover:text-blue-600">Kelas</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('siswa.kelas.show', $kelas) }}" class="hover:text-blue-600">{{ $kelas->nama_kelas }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-medium">Tugas</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Tugas Kelas</h1>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Cari tugas..." value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none">
            </div>
            {{-- Status Filter --}}
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Dikirim</option>
                <option value="dikirim" {{ request('status') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="terlambat" {{ request('status') === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="dinilai" {{ request('status') === 'dinilai' ? 'selected' : '' }}>Dinilai</option>
            </select>
            {{-- Mapel Filter --}}
            <select name="mapel" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white">
                <option value="">Semua Mapel</option>
                @foreach($mapelList as $mapel)
                    <option value="{{ $mapel->id }}" {{ request('mapel') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </button>
        </div>
    </div>

    {{-- Tugas List --}}
    <div class="space-y-3">
        @forelse($tugas as $t)
            @php
                $submission = $t->submissions->where('siswa_id', auth()->id())->first();
                $isLate = $submission && $submission->submitted_at->gt($t->deadline);
            @endphp
            <a href="{{ route('siswa.tugas.show', [$kelas, $t]) }}" class="block bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-blue-200 transition-all group">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 truncate">{{ $t->judul }}</h3>
                            @if($isLate)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 flex-shrink-0">Terlambat</span>
                            @elseif($submission && $submission->nilai !== null)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 flex-shrink-0">Dinilai</span>
                            @elseif($submission)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">Dikirim</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 flex-shrink-0">Belum Dikirim</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $t->mapel?->nama_mapel }}</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $t->deadline->translatedFormat('d M Y, H:i') }}
                            </span>
                            @php
                                $diff = now()->diffInHours($t->deadline, false);
                            @endphp
                            <span class="{{ $diff <= 0 ? 'text-red-600 font-semibold' : ($diff <= 48 ? 'text-yellow-600 font-medium' : 'text-gray-400') }}">
                                {{ $diff > 0 ? $diff . ' jam lagi' : 'Sudah lewat' }}
                            </span>
                        </div>
                        @if($submission && $submission->nilai !== null)
                            <div class="mt-2 flex items-center gap-1.5">
                                <span class="text-xs text-gray-500">Nilai:</span>
                                <span class="text-sm font-bold {{ $submission->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $submission->nilai }}</span>
                            </div>
                        @endif
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="mt-3 text-gray-500 text-sm">Belum ada tugas di kelas ini</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($tugas->hasPages())
        <div class="flex items-center justify-center gap-2">
            {{ $tugas->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
