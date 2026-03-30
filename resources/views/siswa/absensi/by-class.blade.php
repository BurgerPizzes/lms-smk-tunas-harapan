@extends('layouts.siswa')

@section('title', 'Absensi - ' . ($kelas->nama_kelas ?? 'Kelas'))

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.absensi.index') }}" class="hover:text-blue-600">Kehadiran</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">{{ $kelas->nama_kelas }}</span>
    </nav>

    {{-- Class Info --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $kelas->nama_kelas }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $kelas->jurusan?->nama }} • {{ $kelas->guru?->name }}</p>
            </div>
            <div class="flex gap-3">
                @php
                    $persen = $summary['persentase'] ?? 0;
                    $colorClass = $persen >= 80 ? 'green' : ($persen >= 60 ? 'yellow' : 'red');
                @endphp
                <div class="bg-{{ $colorClass }}-50 rounded-xl px-4 py-2 text-center min-w-[100px]">
                    <p class="text-xs text-{{ $colorClass }}-700 font-medium">Kehadiran</p>
                    <p class="text-lg font-bold text-{{ $colorClass }}-600">{{ number_format($persen, 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-2xl font-bold text-green-600">{{ $summary['hadir'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Hadir</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-2xl font-bold text-blue-600">{{ $summary['izin'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Izin</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <div class="w-10 h-10 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 11-12.728 0M12 2v9"/></svg>
            </div>
            <p class="text-2xl font-bold text-yellow-600">{{ $summary['sakit'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Sakit</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p class="text-2xl font-bold text-red-600">{{ $summary['alpha'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Alpha</p>
        </div>
    </div>

    {{-- Mapel Filter --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex flex-wrap gap-2">
            <button onclick="filterAbsensi('')" class="absensi-btn px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white" data-mapel="">Semua</button>
            @foreach($mapelList as $mapel)
                <button onclick="filterAbsensi('{{ $mapel->id }}')" class="absensi-btn px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-mapel="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</button>
            @endforeach
        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="absensiTable">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Mapel</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28 hidden sm:table-cell">Pertemuan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($absensiList as $index => $absen)
                        @php
                            $statusBadge = match($absen->status) {
                                'hadir' => ['bg-green-100 text-green-800', 'Hadir'],
                                'izin' => ['bg-blue-100 text-blue-800', 'Izin'],
                                'sakit' => ['bg-yellow-100 text-yellow-800', 'Sakit'],
                                'alpha' => ['bg-red-100 text-red-800', 'Alpha'],
                                default => ['bg-gray-100 text-gray-800', $absen->status ?? '-'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors absensi-row" data-mapel="{{ $absen->mapel_id }}">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $absen->tanggal?->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $absen->tanggal?->translatedFormat('l') }}</p>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $absen->mapel?->nama_mapel }}</span>
                            </td>
                            <td class="px-6 py-4 text-center hidden sm:table-cell">
                                <span class="text-sm text-gray-600">Ke-{{ $absen->pertemuan_ke ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge[0] }}">
                                    {{ $statusBadge[1] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-sm text-gray-500">{{ $absen->keterangan ?? '-' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                <p class="mt-3 text-gray-500 text-sm">Belum ada data absensi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($absensiList) && $absensiList->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-center">
                {{ $absensiList->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function filterAbsensi(mapelId) {
    document.querySelectorAll('.absensi-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    const active = document.querySelector(`.absensi-btn[data-mapel="${mapelId}"]`);
    active.classList.remove('bg-gray-100', 'text-gray-700');
    active.classList.add('bg-blue-600', 'text-white');

    document.querySelectorAll('.absensi-row').forEach(row => {
        row.style.display = (!mapelId || row.dataset.mapel === mapelId) ? '' : 'none';
    });
}
</script>
@endpush
@endsection
