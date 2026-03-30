@extends('layouts.siswa')

@section('title', 'Quiz')

@section('page-content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quiz</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar quiz yang tersedia untuk kamu</p>
    </div>

    {{-- Status Summary --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Total Quiz</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['tersedia'] ?? 0 }}</p>
            <p class="text-xs text-blue-600 font-medium mt-1">Tersedia</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['selesai'] ?? 0 }}</p>
            <p class="text-xs text-green-600 font-medium mt-1">Selesai</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-gray-400">{{ $stats['belum_dibuka'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Belum Dibuka</p>
        </div>
    </div>

    {{-- Quiz List --}}
    <div class="space-y-4">
        @forelse($quizzes as $quiz)
            @php
                $status = $quiz->status ?? 'belum_dibuka';
                $attempt = $quiz->attempts?->where('siswa_id', auth()->id())->first();
                $statusConfig = match($status) {
                    'belum_mulai' => ['icon' => 'clock', 'bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'badge' => 'bg-blue-100 text-blue-800', 'label' => 'Belum Mulai'],
                    'tersedia' => ['icon' => 'play', 'bg' => 'bg-green-50', 'text' => 'text-green-700', 'badge' => 'bg-green-100 text-green-800', 'label' => 'Tersedia'],
                    'selesai' => ['icon' => 'check', 'bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'badge' => 'bg-gray-100 text-gray-800', 'label' => 'Selesai'],
                    'belum_dibuka' => ['icon' => 'lock', 'bg' => 'bg-gray-50', 'text' => 'text-gray-400', 'badge' => 'bg-gray-100 text-gray-500', 'label' => 'Belum Dibuka'],
                    default => ['icon' => 'clock', 'bg' => 'bg-gray-50', 'text' => 'text-gray-500', 'badge' => 'bg-gray-100 text-gray-700', 'label' => 'Belum Mulai'],
                };
            @endphp
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex flex-col sm:flex-row">
                    {{-- Left Color Strip --}}
                    <div class="w-2 sm:w-1.5 {{ $status === 'tersedia' ? 'bg-green-500' : ($status === 'selesai' ? 'bg-gray-300' : 'bg-blue-300') }} flex-shrink-0"></div>
                    
                    {{-- Content --}}
                    <div class="flex-1 p-5">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-base font-semibold text-gray-900 truncate">{{ $quiz->judul }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['badge'] }} flex-shrink-0">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $quiz->mapel?->nama_mapel }}</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $quiz->durasi }} menit
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $quiz->jumlah_soal }} soal
                                    </span>
                                </div>
                                {{-- Schedule --}}
                                <div class="flex flex-wrap items-center gap-4 mt-2 text-xs text-gray-400">
                                    @if($quiz->mulai_at)
                                        <span>Mulai: {{ $quiz->mulai_at->translatedFormat('d M Y, H:i') }}</span>
                                    @endif
                                    @if($quiz->selesai_at)
                                        <span>Selesai: {{ $quiz->selesai_at->translatedFormat('d M Y, H:i') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="flex items-center gap-3 flex-shrink-0">
                                @if($status === 'tersedia' && (!$attempt || $attempt->can_retry ?? false))
                                    <a href="{{ route('siswa.quiz.start', $quiz) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Mulai
                                    </a>
                                @elseif($attempt && $attempt->skor !== null)
                                    <a href="{{ route('siswa.quiz.result', [$quiz, $attempt]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat Hasil
                                    </a>
                                @elseif($status === 'belum_dibuka')
                                    <div class="px-5 py-2.5 bg-gray-100 text-gray-400 rounded-lg text-sm font-medium inline-flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Terkunci
                                    </div>
                                @elseif($attempt)
                                    <div class="px-5 py-2.5 bg-yellow-50 text-yellow-700 rounded-lg text-sm font-medium inline-flex items-center gap-2">
                                        <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Sedang Dikerjakan
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="mt-3 text-gray-500 text-sm">Belum ada quiz tersedia</p>
            </div>
        @endforelse
    </div>

    @if(isset($quizzes) && $quizzes->hasPages())
        <div class="flex items-center justify-center">
            {{ $quizzes->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
