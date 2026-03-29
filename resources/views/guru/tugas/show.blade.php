@extends('layouts.guru')
@section('title', $tugas->judul ?? 'Detail Tugas')
@section('page-content')

<!-- Back & Actions -->
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('guru.kelas.show', $tugas->kelas_id ?? '') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Kelas
    </a>
    @if($tugas->guru_id == auth()->id() || auth()->user()->hasRole('admin'))
    <div class="flex items-center space-x-2">
        <a href="{{ route('guru.tugas.edit', $tugas->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
            Edit
        </a>
        <form method="POST" action="{{ route('guru.tugas.destroy', $tugas->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                Hapus
            </button>
        </form>
    </div>
    @endif
</div>

<!-- Tugas Detail -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">{{ $tugas->mapel->nama ?? '-' }}</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 capitalize">{{ $tugas->tipe ?? 'tugas' }}</span>
                @if($tugas->deadline)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $tugas->deadline->isPast() ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                        {{ $tugas->deadline->isPast() ? 'Lewat Deadline' : $tugas->deadline->diffForHumans() }}
                    </span>
                @endif
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $tugas->judul }}</h1>
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                <span class="flex items-center space-x-1">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    <span>Deadline: {{ $tugas->deadline?->format('d F Y, H:i') ?? 'Tanpa Deadline' }}</span>
                </span>
                <span class="flex items-center space-x-1">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" /></svg>
                    <span>Kelas: {{ $tugas->kelas->nama ?? '-' }}</span>
                </span>
            </div>
            @if($tugas->deskripsi)
                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $tugas->deskripsi }}</p>
                </div>
            @endif
            @if($tugas->instruksi)
                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                    <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-1">Instruksi</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-400 whitespace-pre-line">{{ $tugas->instruksi }}</p>
                </div>
            @endif
            @if($tugas->file)
                <div class="mt-4">
                    <a href="{{ Storage::url($tugas->file) }}" download class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Download Lampiran
                    </a>
                </div>
            @endif
        </div>

        <!-- Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Statistik Pengumpulan</h3>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['submitted'] ?? 0 }}</p>
                    <p class="text-xs text-green-700 dark:text-green-400 mt-1">Tepat Waktu</p>
                </div>
                <div class="text-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['late'] ?? 0 }}</p>
                    <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">Terlambat</p>
                </div>
                <div class="text-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['not_submitted'] ?? 0 }}</p>
                    <p class="text-xs text-red-700 dark:text-red-400 mt-1">Belum</p>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                <div class="bg-green-500 h-2.5 rounded-full transition-all" style="width: {{ $stats['submitted'] ?? 0 }}%"></div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $stats['submitted'] ?? 0 }} dari {{ $stats['total'] ?? 0 }} siswa telah mengumpulkan</p>
        </div>

        <!-- Filter -->
        <div class="flex items-center space-x-2">
            <select id="filter-status" onchange="filterSubmissions()" class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <option value="">Semua Status</option>
                <option value="submitted">Tepat Waktu</option>
                <option value="late">Terlambat</option>
                <option value="not_submitted">Belum Mengumpulkan</option>
                <option value="graded">Sudah Dinilai</option>
                <option value="ungraded">Belum Dinilai</option>
            </select>
            <input type="text" id="search-siswa" placeholder="Cari siswa..." class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 flex-1" onkeyup="filterSubmissions()">
        </div>

        <!-- Submissions Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="submissions-table">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Siswa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nilai</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($submissions ?? [] as $index => $sub)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors submission-row" data-status="{{ $sub->status ?? ($sub->file ? ($sub->is_late ? 'late' : 'submitted') : 'not_submitted') }}" data-name="{{ strtolower($sub->siswa->name ?? '') }}" data-graded="{{ $sub->nilai !== null ? 'graded' : 'ungraded' }}">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($sub->siswa->name ?? 'S', 0, 1)) }}</div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $sub->siswa->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-500 dark:text-gray-400">{{ $sub->submitted_at?->format('d M Y, H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($sub->file || $sub->submitted_at)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($sub->is_late ?? false) ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                                        {{ ($sub->is_late ?? false) ? 'Terlambat' : 'Tepat Waktu' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Belum</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($sub->nilai !== null)
                                    <span class="text-sm font-semibold {{ $sub->nilai >= ($tugas->kkm ?? 75) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $sub->nilai }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('guru.penilaian.index', $tugas->id) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Nilai">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada pengumpulan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Detail Tugas</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kelas</p>
                    <a href="{{ route('guru.kelas.show', $tugas->kelas_id) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">{{ $tugas->kelas->nama ?? '-' }}</a>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Mata Pelajaran</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $tugas->mapel->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Maks</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $tugas->nilai_maks ?? 100 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Dibuat</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $tugas->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('guru.penilaian.index', $tugas->id) }}" class="block w-full bg-indigo-600 text-white text-center rounded-xl py-3 text-sm font-medium hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
            Buka Halaman Penilaian
        </a>
    </div>
</div>

<script>
function filterSubmissions() {
    const status = document.getElementById('filter-status').value;
    const search = document.getElementById('search-siswa').value.toLowerCase();
    document.querySelectorAll('.submission-row').forEach(row => {
        const rowStatus = row.dataset.status;
        const rowGraded = row.dataset.graded;
        const rowName = row.dataset.name;
        let show = rowName.includes(search);
        if (status === 'graded') show = show && rowGraded === 'graded';
        else if (status === 'ungraded') show = show && rowGraded === 'ungraded';
        else if (status) show = show && rowStatus === status;
        row.style.display = show ? '' : 'none';
    });
}
</script>

@endsection
