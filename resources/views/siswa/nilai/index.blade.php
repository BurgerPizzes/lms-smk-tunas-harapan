@extends('layouts.siswa')

@section('title', 'Nilai Saya')

@section('page-content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Nilai Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan nilai dari semua kelas</p>
    </div>

    {{-- Overall Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">Rata-rata Keseluruhan</p>
            <p class="text-3xl font-bold mt-1 {{ ($overallStats['rata_rata'] ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($overallStats['rata_rata'] ?? 0, 1) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">Tugas Dinilai</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $overallStats['total_dinilai'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">Tugas Lulus</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $overallStats['total_lulus'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Per Class Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($kelasNilai as $kn)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Card Header --}}
                <div class="p-5 border-b border-gray-100 cursor-pointer" onclick="toggleClassDetail('kelas-{{ $kn['kelas_id'] }}')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $kn['cover_color'] ?? '#4F46E5' }}">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $kn['nama_kelas'] }}</h3>
                                <p class="text-xs text-gray-500">{{ $kn['jumlah_nilai'] }} tugas dinilai</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-xl font-bold {{ ($kn['rata_rata'] ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($kn['rata_rata'] ?? 0, 1) }}</p>
                            </div>
                            <svg id="icon-kelas-{{ $kn['kelas_id'] }}" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Expandable Detail --}}
                <div id="kelas-{{ $kn['kelas_id'] }}" class="hidden">
                    <div class="p-5 space-y-3">
                        @foreach($kn['mapel'] ?? [] as $mapel)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ $mapel['nama_mapel'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $mapel['jumlah'] }} tugas</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ ($mapel['rata_rata'] ?? 0) >= 75 ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ min($mapel['rata_rata'] ?? 0, 100) }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold min-w-[3rem] text-right {{ ($mapel['rata_rata'] ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($mapel['rata_rata'] ?? 0, 1) }}</span>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('siswa.nilai.by-class', $kn['kelas_id']) }}" class="block pt-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-xl border border-gray-100 py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <p class="mt-3 text-gray-500 text-sm">Belum ada nilai</p>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function toggleClassDetail(id) {
    const el = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    el.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
@endpush
@endsection
