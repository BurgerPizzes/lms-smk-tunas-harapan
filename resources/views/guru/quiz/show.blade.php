@extends('layouts.guru')
@section('title', $quiz->judul ?? 'Detail Quiz')
@section('page-content')

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('guru.kelas.show', $quiz->kelas_id ?? '') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Kembali ke Kelas
        </a>
        <div class="flex items-center space-x-3">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $quiz->judul }}</h1>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $quiz->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                {{ $quiz->is_published ? 'Aktif' : 'Draft' }}
            </span>
        </div>
        <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-gray-500 dark:text-gray-400">
            <span class="flex items-center space-x-1">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <span>{{ $quiz->durasi_menit }} menit</span>
            </span>
            <span>&middot;</span>
            <span>{{ $soalList->count() ?? 0 }} soal</span>
            <span>&middot;</span>
            <span>{{ $quiz->mapel->nama ?? '-' }}</span>
            @if($quiz->mulai_at)
                <span>&middot;</span>
                <span>{{ $quiz->mulai_at->format('d M Y, H:i') }} - {{ $quiz->selesai_at?->format('H:i') ?? '-' }}</span>
            @endif
        </div>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('guru.quiz.add-question', $quiz->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Soal
        </a>
    </div>
</div>

<!-- Tabs -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6">
        <nav class="flex space-x-6 -mb-px" id="quiz-tabs">
            <button onclick="switchQuizTab('soal')" id="qtab-soal" class="qtab-btn py-3 border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 font-medium text-sm">
                Daftar Soal ({{ $soalList->count() ?? 0 }})
            </button>
            <button onclick="switchQuizTab('hasil')" id="qtab-hasil" class="qtab-btn py-3 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm">
                Hasil ({{ $hasilList->count() ?? 0 }})
            </button>
        </nav>
    </div>

    <!-- Soal Tab -->
    <div id="qcontent-soal" class="qcontent-tab">
        @forelse($soalList ?? [] as $index => $soal)
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-full text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ $index + 1 }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 capitalize">
                            {{ str_replace('_', ' ', $soal->tipe ?? 'pilihan_ganda') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">{{ $soal->poin ?? 1 }} poin</span>
                    </div>
                    <p class="text-sm text-gray-900 dark:text-white mb-3">{{ $soal->pertanyaan }}</p>

                    @if(in_array($soal->tipe ?? '', ['pilihan_ganda_4', 'pilihan_ganda_5']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">
                        @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                            @if($soal->{'pilihan_'.strtoupper($opt)})
                            <div class="flex items-center space-x-2 px-3 py-2 rounded-lg {{ ($soal->jawaban_benar ?? '') === strtoupper($opt) ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-700/50' }}">
                                <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center text-xs font-bold flex-shrink-0 {{ ($soal->jawaban_benar ?? '') === strtoupper($opt) ? 'border-green-500 bg-green-500 text-white' : 'border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400' }}">
                                    {{ strtoupper($opt) }}
                                </span>
                                <span class="text-sm {{ ($soal->jawaban_benar ?? '') === strtoupper($opt) ? 'text-green-700 dark:text-green-400 font-medium' : 'text-gray-600 dark:text-gray-400' }}">{{ $soal->{'pilihan_'.strtoupper($opt)} }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @elseif(($soal->tipe ?? '') === 'benar_salah')
                    <div class="flex space-x-2 mb-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium {{ ($soal->jawaban_benar ?? '') === 'benar' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400' : 'bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400' }}">Benar</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium {{ ($soal->jawaban_benar ?? '') === 'salah' ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400' : 'bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400' }}">Salah</span>
                    </div>
                    @endif

                    @if($soal->pembahasan)
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <p class="text-xs font-semibold text-blue-800 dark:text-blue-300 mb-1">Pembahasan</p>
                            <p class="text-xs text-blue-700 dark:text-blue-400 whitespace-pre-line">{{ $soal->pembahasan }}</p>
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-1 ml-4 flex-shrink-0">
                    <a href="{{ route('guru.quiz.edit-question', [$quiz->id, $soal->id]) }}" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Edit Soal">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    </a>
                    <form method="POST" action="{{ route('guru.quiz.destroy-question', [$quiz->id, $soal->id]) }}" class="inline" onsubmit="return confirm('Hapus soal ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus Soal">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827m0 0v.75m0-2.25a1.125 1.125 0 0 1 0-2.25" /></svg>
            <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum ada soal</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Mulai tambahkan soal untuk quiz ini.</p>
            <a href="{{ route('guru.quiz.add-question', $quiz->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Soal
            </a>
        </div>
        @endforelse

        <!-- Add Question Button -->
        @if(($soalList->count() ?? 0) > 0)
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('guru.quiz.add-question', $quiz->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Soal Baru
            </a>
        </div>
        @endif
    </div>

    <!-- Hasil Tab -->
    <div id="qcontent-hasil" class="qcontent-tab hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Siswa</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Skor</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Benar / Salah</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Waktu Mulai</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">Waktu Selesai</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Durasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($hasilList ?? [] as $index => $hasil)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($hasil->siswa->name ?? 'S', 0, 1)) }}</div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $hasil->siswa->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-8 text-sm font-bold rounded-lg {{ ($hasil->skor ?? 0) >= ($quiz->kkm ?? 75) ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $hasil->skor ?? 0 }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center text-sm text-gray-600 dark:text-gray-400 hidden md:table-cell">
                            <span class="text-green-600 dark:text-green-400 font-medium">{{ $hasil->benar ?? 0 }}</span>
                            <span class="text-gray-400"> / </span>
                            <span class="text-red-600 dark:text-red-400 font-medium">{{ $hasil->salah ?? 0 }}</span>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $hasil->mulai_at?->format('d M Y, H:i') ?? '-' }}</td>
                        <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400 hidden lg:table-cell">{{ $hasil->selesai_at?->format('d M Y, H:i') ?? '-' }}</td>
                        <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $hasil->durasi ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada siswa yang mengerjakan quiz ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchQuizTab(tab) {
    document.querySelectorAll('.qcontent-tab').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.qtab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
        btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    });
    const content = document.getElementById('qcontent-' + tab);
    const tabEl = document.getElementById('qtab-' + tab);
    if (content) content.classList.remove('hidden');
    if (tabEl) {
        tabEl.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        tabEl.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
    }
}
</script>

@endsection
