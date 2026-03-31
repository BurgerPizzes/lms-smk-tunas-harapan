@extends('layouts.siswa')

@section('title', 'Materi Kelas')

@section('page-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('siswa.kelas.index') }}" class="hover:text-blue-600">Kelas</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('siswa.kelas.show', $kelas) }}" class="hover:text-blue-600">{{ $kelas->nama_kelas }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-900 font-medium">Materi</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Materi Kelas</h1>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari materi..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none" id="searchMateri" onkeyup="filterMateri()">
            </div>
            {{-- Mapel Filter --}}
            <select id="filterMapel" onchange="filterMateri()" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($mapels as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Materi Grid View --}}
    <div id="view-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($materis as $m)
            <a href="{{ route('siswa.materi.show', $m) }}" class="materi-item bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:-translate-y-0.5 transition-all group" data-mapel="{{ $m->mapel_id }}" data-search="{{ strtolower($m->judul) }}">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $m->tipe === 'video' ? 'bg-red-100 text-red-600' : ($m->tipe === 'file' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }} flex items-center justify-center flex-shrink-0">
                        @if($m->tipe === 'video')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($m->tipe === 'file')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 line-clamp-2">{{ $m->judul }}</h3>
                        <div class="mt-2 space-y-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $m->mapel?->nama }}</span>
                            <p class="text-xs text-gray-500">Pertemuan {{ $m->pertemuan_ke }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $m->guru?->name }}
                    </span>
                    <span>{{ $m->created_at->translatedFormat('d M Y') }}</span>
                </div>
            </a>
        @empty
            <div class="col-span-full py-16 text-center">
                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <p class="mt-3 text-gray-500 text-sm">Belum ada materi</p>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function filterMateri() {
    const search = document.getElementById('searchMateri').value.toLowerCase();
    const mapel = document.getElementById('filterMapel').value;
    document.querySelectorAll('.materi-item').forEach(item => {
        const matchSearch = item.dataset.search.includes(search);
        const matchMapel = !mapel || item.dataset.mapel === mapel;
        item.style.display = matchSearch && matchMapel ? '' : 'none';
    });
}
</script>
@endpush
@endsection
