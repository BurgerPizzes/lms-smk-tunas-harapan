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

    {{-- Tugas List --}}
    <div class="space-y-3">
        @forelse($tugasList as $t)
            <a href="{{ route('siswa.tugas.show', $t) }}" class="block bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-blue-200 transition-all group">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 truncate">{{ $t->judul }}</h3>
                            @if($t->is_expired)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 flex-shrink-0">Lewat</span>
                            @elseif($t->is_graded)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 flex-shrink-0">Dinilai</span>
                            @elseif($t->is_submitted)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">Dikirim</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 flex-shrink-0">Belum</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $t->mapel?->nama }}</span>
                            @if($t->deadline)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $t->deadline->translatedFormat('d M Y, H:i') }}
                                </span>
                            @endif
                        </div>
                        @if($t->is_submitted && $t->submission && $t->submission->nilai !== null)
                            <div class="mt-2 flex items-center gap-1.5">
                                <span class="text-xs text-gray-500">Nilai:</span>
                                <span class="text-sm font-bold {{ $t->submission->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $t->submission->nilai }}</span>
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
    @if($tugasList->hasPages())
        <div class="flex items-center justify-center gap-2">
            {{ $tugasList->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
