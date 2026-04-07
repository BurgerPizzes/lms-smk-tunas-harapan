@extends('layouts.guru')
@section('title', 'Quiz - ' . ($kelas->nama ?? ''))
@section('page-content')

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <a href="{{ route('guru.kelas.show', $kelas->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quiz - {{ $kelas->nama }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $quizzes->total() }} quiz</p>
    </div>
    <a href="{{ route('guru.kelas.quiz.create', $kelas->id) }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Buat Quiz
    </a>
</div>

<!-- Filter -->
<div class="flex items-center gap-3 mb-6">
    <select onchange="window.location.href='{{ route('guru.kelas.quiz.index', $kelas->id) }}?mapel='+this.value" class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
        <option value="">Semua Mapel</option>
        @foreach($mapels ?? [] as $mapel)
            <option value="{{ $mapel->id }}" {{ request('mapel') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
        @endforeach
    </select>
</div>

<!-- Quiz List -->
<div class="space-y-4">
    @forelse($quizzes ?? [] as $quiz)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div class="min-w-0 flex-1">
                <div class="flex items-center space-x-2 mb-1">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $quiz->judul }}</h3>
                    @if($quiz->is_published)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Terbit</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Draft</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $quiz->mapel->nama ?? '-' }} &middot; {{ $quiz->questions->count() }} soal &middot; {{ $quiz->durasi_menit }} menit</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $quiz->deskripsi ?? '-' }}</p>
            </div>
            <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
                <a href="{{ route('guru.quiz.show', $quiz->id) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Detail">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                </a>
                <a href="{{ route('guru.quiz.results', $quiz->id) }}" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors" title="Hasil">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 py-16 text-center">
        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827m0 0v.75m0-2.25a1.125 1.125 0 0 1 0-2.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5a1.5 1.5 0 0 1 1.5-1.5h12a1.5 1.5 0 0 1 1.5 1.5v8.25a1.5 1.5 0 0 1-1.5 1.5h-12a1.5 1.5 0 0 1-1.5-1.5v-8.25Z" /></svg>
        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum ada quiz</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Buat quiz baru untuk kelas ini.</p>
    </div>
    @endforelse
</div>

@if(isset($quizzes) && $quizzes->hasPages())
<div class="mt-6 flex items-center justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">Menampilkan {{ $quizzes->firstItem() }}-{{ $quizzes->lastItem() }} dari {{ $quizzes->total() }} data</p>
    {{ $quizzes->links('vendor.pagination.tailwind') }}
</div>
@endif

@endsection
