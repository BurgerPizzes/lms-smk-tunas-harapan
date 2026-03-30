@extends('layouts.siswa')

@section('title', 'Nilai - ' . ($kelas->nama_kelas ?? 'Kelas'))

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.nilai.index') }}" class="hover:text-blue-600">Nilai Saya</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">{{ $kelas->nama_kelas }}</span>
    </nav>

    {{-- Class Info Header --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $kelas->nama_kelas }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $kelas->jurusan?->nama }} • {{ $kelas->guru?->name }}</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-green-50 rounded-xl px-4 py-2 text-center">
                    <p class="text-xs text-green-700 font-medium">Rata-rata</p>
                    <p class="text-lg font-bold text-green-600">{{ number_format($rataRata ?? 0, 1) }}</p>
                </div>
                <div class="bg-blue-50 rounded-xl px-4 py-2 text-center">
                    <p class="text-xs text-blue-700 font-medium">Lulus</p>
                    <p class="text-lg font-bold text-blue-600">{{ $lulusCount ?? 0 }}/{{ $totalNilai ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Mapel Filter --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex flex-wrap gap-2" id="mapelFilter">
            <button onclick="filterByMapel('')" class="mapel-btn px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white" data-mapel="">
                Semua
            </button>
            @foreach($mapelList as $mapel)
                <button onclick="filterByMapel('{{ $mapel->id }}')" class="mapel-btn px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-mapel="{{ $mapel->id }}">
                    {{ $mapel->nama_mapel }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Grade Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="nilaiTable">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Mapel</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24 hidden sm:table-cell">Maks</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Feedback</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($nilaiList as $index => $nilai)
                        @php
                            $statusLulus = $nilai->nilai >= $nilai->tugas?->kkm ?? 75;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors nilai-row" data-mapel="{{ $nilai->tugas?->mapel_id }}">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $nilai->tugas?->judul }}</p>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $nilai->tugas?->mapel?->nama_mapel }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold {{ $statusLulus ? 'text-green-600' : 'text-red-600' }}">{{ $nilai->nilai }}</span>
                            </td>
                            <td class="px-6 py-4 text-center hidden sm:table-cell">
                                <span class="text-sm text-gray-500">{{ $nilai->tugas?->nilai_maks ?? 100 }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($statusLulus)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-sm text-gray-500 truncate max-w-[200px]">{{ $nilai->feedback ?? '-' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                <p class="mt-3 text-gray-500 text-sm">Belum ada nilai di kelas ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterByMapel(mapelId) {
    document.querySelectorAll('.mapel-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    const active = document.querySelector(`.mapel-btn[data-mapel="${mapelId}"]`);
    active.classList.remove('bg-gray-100', 'text-gray-700');
    active.classList.add('bg-blue-600', 'text-white');

    document.querySelectorAll('.nilai-row').forEach(row => {
        row.style.display = (!mapelId || row.dataset.mapel === mapelId) ? '' : 'none';
    });
}
</script>
@endpush
@endsection
