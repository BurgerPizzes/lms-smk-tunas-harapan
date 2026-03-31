@extends('layouts.siswa')

@section('title', 'Hasil Quiz - ' . ($quiz->judul ?? 'Quiz'))

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.kelas.index') }}" class="hover:text-blue-600">Kelas</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('siswa.kelas.show', $quiz->kelas) }}" class="hover:text-blue-600">{{ $quiz->kelas?->nama_kelas ?? 'Kelas' }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('siswa.quiz.index', $quiz->kelas) }}" class="hover:text-blue-600">Quiz</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium truncate max-w-xs">{{ $quiz->judul }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">Hasil</span>
    </nav>

    {{-- Score Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                {{-- Score Circle --}}
                <div class="relative w-40 h-40 flex-shrink-0">
                    <svg class="w-40 h-40 transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#F3F4F6" stroke-width="10"/>
                        @php $skorValue = $attempt->skor ?? 0; $circleLength = 2 * 3.14159 * 52; @endphp
                        <circle cx="60" cy="60" r="52" fill="none" stroke="{{ $skorValue >= 75 ? '#22C55E' : '#EF4444' }}" stroke-width="10" stroke-linecap="round" stroke-dasharray="{{ $circleLength }}" stroke-dashoffset="{{ $circleLength * (1 - $skorValue / 100) }}"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-bold {{ $skorValue >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $skorValue }}</span>
                        <span class="text-xs text-gray-500">dari 100</span>
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $quiz->judul }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $quiz->mapel?->nama }}</p>
                    <div class="mt-4">
                        @if($skorValue >= 75)
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Lulus
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Tidak Lulus
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Score Breakdown --}}
        <div class="border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4">
            <div class="p-5 text-center border-r border-gray-100">
                <p class="text-2xl font-bold text-green-600">{{ $stats['benar'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Benar</p>
            </div>
            <div class="p-5 text-center border-r border-gray-100">
                <p class="text-2xl font-bold text-red-600">{{ $stats['salah'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Salah</p>
            </div>
            <div class="p-5 text-center border-r border-gray-100">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Total Soal</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ number_format(($stats['benar'] ?? 0) / max($stats['total'] ?? 1, 1) * 100, 0) }}%</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Akurasi</p>
            </div>
        </div>
    </div>

    {{-- Time Info --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex flex-wrap items-center justify-center gap-8 text-sm">
            @php
                $timeSpent = $attempt->durasi_detik ?? ($attempt->waktu_mulai && $attempt->waktu_selesai ? $attempt->waktu_mulai->diffInSeconds($attempt->waktu_selesai) : 0);
            @endphp
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Waktu: <strong>{{ gmdate('H:i:s', $timeSpent) }}</strong></span>
            </div>
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Tanggal: <strong>{{ $attempt->waktu_selesai?->translatedFormat('d M Y, H:i') }}</strong></span>
            </div>
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Durasi Quiz: <strong>{{ $quiz->durasi_menit }} menit</strong></span>
            </div>
        </div>
    </div>

    {{-- Question Review --}}
    @if($showPembahasan && count($reviewQuestions) > 0)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Pembahasan Soal
                </h2>
                <p class="text-sm text-gray-500 mt-1">Review jawaban kamu berdasarkan kunci jawaban yang benar</p>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($reviewQuestions as $index => $item)
                    @php
                        $isCorrect = $item['is_correct'] ?? false;
                    @endphp
                    <div class="p-6 {{ $isCorrect ? '' : 'bg-red-50/30' }}">
                        {{-- Question Header --}}
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full {{ $isCorrect ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                @if($isCorrect)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold text-gray-400 uppercase">Soal {{ $index + 1 }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $isCorrect ? 'Benar' : 'Salah' }}
                                    </span>
                                    @if($item['poin'] ?? null)
                                        <span class="text-xs text-gray-400">+{{ $item['poin'] }} poin</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-900 leading-relaxed">{!! $item['pertanyaan'] !!}</p>
                            </div>
                        </div>

                        {{-- Options Review (Pilihan Ganda) --}}
                        @if(($item['tipe'] ?? '') === 'pilgan' || ($item['tipe'] ?? '') === 'pilihan_ganda')
                            <div class="ml-11 space-y-2">
                                @foreach(['A','B','C','D','E'] as $letter)
                                    @php
                                        $optionKey = 'pilihan_' . strtolower($letter);
                                        $optionText = $item[$optionKey] ?? null;
                                        if(!$optionText) continue;
                                        $isUserAnswer = in_array(strtolower($letter), array_map('strtolower', (array)($item['user_answer'] ?? [])));
                                        $isCorrectAnswer = in_array(strtolower($letter), array_map('strtolower', (array)($item['correct_answer'] ?? [])));
                                    @endphp
                                    <div class="flex items-start gap-3 px-4 py-3 rounded-lg text-sm
                                        {{ $isCorrectAnswer ? 'bg-green-50 border border-green-200' : ($isUserAnswer && !$isCorrectAnswer ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-100') }}">
                                        <span class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold flex-shrink-0
                                            {{ $isCorrectAnswer ? 'bg-green-500 text-white' : ($isUserAnswer ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-500') }}">
                                            {{ $letter }}
                                        </span>
                                        <span class="flex-1 text-gray-700 leading-relaxed">{!! $optionText !!}</span>
                                        @if($isCorrectAnswer)
                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($isUserAnswer)
                                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @endif
                                    </div>
                                @endforeach
                                {{-- Legend --}}
                                <div class="flex items-center gap-4 text-xs text-gray-400 mt-2">
                                    @if(!$isCorrect)
                                        <span>Jawabanmu: <strong class="text-red-600">{{ $item['user_answer'] ?? '-' }}</strong></span>
                                    @endif
                                    <span>Kunci: <strong class="text-green-600">{{ $item['correct_answer'] ?? '-' }}</strong></span>
                                </div>
                            </div>
                        @endif

                        {{-- Benar/Salah Review --}}
                        @if(($item['tipe'] ?? '') === 'benar_salah')
                            <div class="ml-11 space-y-2">
                                @foreach(['Benar' => 'benar', 'Salah' => 'salah'] as $label => $value)
                                    @php
                                        $isUserAnswer = strtolower($item['user_answer'] ?? '') === $value;
                                        $isCorrectAnswer = strtolower($item['correct_answer'] ?? '') === $value;
                                    @endphp
                                    <div class="flex items-start gap-3 px-4 py-3 rounded-lg text-sm
                                        {{ $isCorrectAnswer ? 'bg-green-50 border border-green-200' : ($isUserAnswer && !$isCorrectAnswer ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-100') }}">
                                        <span class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold flex-shrink-0
                                            {{ $isCorrectAnswer ? 'bg-green-500 text-white' : ($isUserAnswer ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-500') }}">
                                            {{ substr($label, 0, 1) }}
                                        </span>
                                        <span class="flex-1 text-gray-700 leading-relaxed">{{ $label }}</span>
                                        @if($isCorrectAnswer)
                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($isUserAnswer)
                                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @endif
                                    </div>
                                @endforeach
                                <div class="flex items-center gap-4 text-xs text-gray-400 mt-2">
                                    @if(!$isCorrect)
                                        <span>Jawabanmu: <strong class="text-red-600">{{ $item['user_answer'] ?? '-' }}</strong></span>
                                    @endif
                                    <span>Kunci: <strong class="text-green-600">{{ $item['correct_answer'] ?? '-' }}</strong></span>
                                </div>
                            </div>
                        @endif

                        {{-- Essay Review --}}
                        @if(($item['tipe'] ?? '') === 'essay')
                            <div class="ml-11 space-y-3">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="text-xs font-medium text-gray-500 mb-1">Jawaban Kamu:</p>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $item['user_answer'] ?? '(Tidak dijawab)' }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Pembahasan --}}
                        @if(!empty($item['pembahasan']))
                            <div class="ml-11 mt-3 bg-purple-50 rounded-lg p-4 border border-purple-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-xs font-semibold text-purple-800">Pembahasan</p>
                                </div>
                                <p class="text-sm text-purple-700 leading-relaxed">{!! $item['pembahasan'] !!}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-gray-500">
                        Pembahasan tidak tersedia untuk quiz ini.
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-6 text-center">
            <svg class="w-10 h-10 text-yellow-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <p class="text-sm text-yellow-800 font-medium mt-2">Pembahasan tidak ditampilkan oleh guru</p>
            <p class="text-xs text-yellow-600 mt-1">Hubungi guru untuk mengetahui pembahasan soal</p>
        </div>
    @endif

    {{-- Back --}}
    <div class="flex justify-center">
        @if($quiz->kelas)
            <a href="{{ route('siswa.quiz.index', $quiz->kelas) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Quiz
            </a>
        @else
            <a href="{{ route('siswa.kelas.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Kelas Saya
            </a>
        @endif
    </div>
</div>
@endsection
