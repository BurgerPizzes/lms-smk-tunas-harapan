@extends('layouts.guru')
@section('title', 'Penilaian - ' . ($tugas->judul ?? ''))
@section('page-content')

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <a href="{{ route('guru.tugas.show', $tugas->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Kembali ke Tugas
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Penilaian Tugas</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $tugas->judul }} &middot; {{ $tugas->mapel->nama ?? '-' }} &middot; {{ $tugas->kelas->nama ?? '-' }}</p>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('guru.penilaian.export', [$tugas->class_id ?? $tugas->id, $tugas->mapel_id ?? 0]) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            Export CSV
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['average'] ?? '-' }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Rata-rata</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['highest'] ?? '-' }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tertinggi</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['lowest'] ?? '-' }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Terendah</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['graded'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dinilai</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['pass_rate'] ?? '-' }}%</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">KKM Lulus</p>
    </div>
</div>

<!-- Filter -->
<div class="flex flex-col sm:flex-row gap-3 mb-4">
    <select id="filter-status" class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
        <option value="">Semua Status</option>
        <option value="submitted">Sudah Mengumpulkan</option>
        <option value="late">Terlambat</option>
        <option value="not_submitted">Belum Mengumpulkan</option>
        <option value="graded">Sudah Dinilai</option>
        <option value="ungraded">Belum Dinilai</option>
    </select>
    <input type="text" id="search-siswa" placeholder="Cari nama siswa..." class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 flex-1">
</div>

<!-- Grading Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <form method="POST" action="{{ route('guru.penilaian.grade-bulk', $tugas->id) }}" id="grading-form">
        @csrf

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-10">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden lg:table-cell">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">File</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-24">Nilai</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden xl:table-cell">Feedback</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($submissions ?? [] as $index => $sub)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors grading-row" data-status="{{ $sub->file_path ? ($sub->is_late ? 'late' : 'submitted') : 'not_submitted' }}" data-name="{{ strtolower($sub->siswa->name ?? '') }}" data-graded="{{ $sub->nilai !== null ? 'graded' : 'ungraded' }}">
                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($sub->siswa->name ?? 'S', 0, 1)) }}</div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $sub->siswa->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500 dark:text-gray-400">{{ $sub->submitted_at?->format('d M Y, H:i') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($sub->file)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($sub->is_late ?? false) ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                                    {{ ($sub->is_late ?? false) ? 'Terlambat' : 'Tepat Waktu' }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Belum</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center hidden md:table-cell">
                            @if($sub->file)
                                <a href="{{ Storage::url($sub->file) }}" download class="inline-flex items-center text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                    Download
                                </a>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="nilai[{{ $sub->id }}]" value="{{ $sub->nilai }}" min="0" max="{{ $tugas->nilai_maks ?? 100 }}" class="w-full px-2 py-1.5 text-center text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="-">
                        </td>
                        <td class="px-4 py-3 hidden xl:table-cell">
                            <textarea name="feedback[{{ $sub->id }}]" rows="1" class="w-full px-2 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none" placeholder="Feedback...">{{ $sub->feedback ?? '' }}</textarea>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button type="button" onclick="saveGrade({{ $sub->id }})" class="p-1.5 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Simpan Nilai">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada pengumpulan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Nilai maks: <strong>{{ $tugas->nilai_maks ?? 100 }}</strong> &middot; KKM: <strong>{{ $tugas->kkm ?? 75 }}</strong>
            </p>
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Simpan Semua Nilai
            </button>
        </div>
    </form>
</div>

<script>
function filterRows() {
    const status = document.getElementById('filter-status').value;
    const search = document.getElementById('search-siswa').value.toLowerCase();
    document.querySelectorAll('.grading-row').forEach(row => {
        let show = row.dataset.name.includes(search);
        if (status === 'graded') show = show && row.dataset.graded === 'graded';
        else if (status === 'ungraded') show = show && row.dataset.graded === 'ungraded';
        else if (status) show = show && row.dataset.status === status;
        row.style.display = show ? '' : 'none';
    });
}
document.getElementById('filter-status')?.addEventListener('change', filterRows);
document.getElementById('search-siswa')?.addEventListener('keyup', filterRows);

function saveGrade(subId) {
    const form = document.getElementById('grading-form');
    const nilai = form.querySelector(`input[name="nilai[${subId}]"]`)?.value;
    const feedback = form.querySelector(`textarea[name="feedback[${subId}]"]`)?.value;
    fetch('{{ route('guru.penilaian.grade', $tugas->id) }}', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
        body: JSON.stringify({ submission_id: subId, nilai: nilai, feedback: feedback })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const row = document.querySelector(`input[name="nilai[${subId}]"]`)?.closest('tr');
            if (row) row.dataset.graded = nilai ? 'graded' : 'ungraded';
        }
    });
}
</script>

@endsection
