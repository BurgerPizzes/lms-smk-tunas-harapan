@extends('layouts.guru')
@section('title', 'Hasil Quiz - ' . ($quiz->judul ?? ''))
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <a href="{{ route('guru.quiz.show', $quiz->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Quiz
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hasil Quiz: {{ $quiz->judul }}</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $quiz->kelas->nama ?? '' }} &middot; {{ $quiz->mapel->nama ?? '' }}</p>
</div>

<!-- Statistics -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Percobaan</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $statistics['total_attempts'] ?? 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Siswa Unik</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $statistics['unique_students'] ?? 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata</p>
        <p class="text-2xl font-bold mt-1 @php $avg = $statistics['average_score'] ?? 0; @endphp {{ $avg >= 75 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ number_format($avg, 1) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Lulus / Gagal</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ ($statistics['passed_count'] ?? 0) }} / {{ ($statistics['failed_count'] ?? 0) }}</p>
    </div>
</div>

<!-- Results Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Siswa</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Skor</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Waktu Mulai</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Waktu Selesai</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($attempts ?? [] as $index => $attempt)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ($attempts->currentPage() - 1) * $attempts->perPage() + $index + 1 }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                {{ strtoupper(substr($attempt->siswa->name ?? 'S', 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $attempt->siswa->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-sm font-bold @php $score = $attempt->skor ?? 0; @endphp {{ $score >= 75 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $score ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-4 text-center hidden sm:table-cell">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $attempt->waktu_mulai ? $attempt->waktu_mulai->format('d M Y H:i') : '-' }}</span>
                    </td>
                    <td class="px-5 py-4 text-center hidden sm:table-cell">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $attempt->waktu_selesai ? $attempt->waktu_selesai->format('d M Y H:i') : '-' }}</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($attempt->skor !== null)
                            @if(($attempt->skor ?? 0) >= 75)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Lulus</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Tidak Lulus</span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Menjawab</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada hasil quiz.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($attempts) && $attempts->hasPages())
    <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">Menampilkan {{ $attempts->firstItem() }}-{{ $attempts->lastItem() }} dari {{ $attempts->total() }} data</p>
        {{ $attempts->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>

@endsection
