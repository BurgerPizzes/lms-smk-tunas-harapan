@extends('layouts.siswa')

@section('title', $kelas->nama_kelas)

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.kelas.index') }}" class="hover:text-blue-600 transition-colors">Kelas Saya</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">{{ $kelas->nama_kelas }}</span>
    </nav>

    {{-- Class Header --}}
    <div class="rounded-2xl overflow-hidden shadow-sm" style="background: linear-gradient(135deg, {{ $kelas->cover_color ?? '#4F46E5' }}, {{ $kelas->cover_color_secondary ?? '#7C3AED' }})">
        <div class="px-8 py-8 text-white">
            <h1 class="text-3xl font-bold">{{ $kelas->nama_kelas }}</h1>
            <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-white/80">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ $kelas->jurusan?->nama ?? '-' }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $kelas->waliKelas?->name ?? 'Guru Pengampu' }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Kode: {{ strtoupper($kelas->kode_unik) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Quick Action Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('siswa.materi.index', $kelas) }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md hover:border-blue-200 transition-all group text-center">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-blue-100">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-900">Materi</p>
            <p class="text-xs text-gray-500 mt-0.5">Lihat materi kelas</p>
        </a>
        <a href="{{ route('siswa.tugas.index', $kelas) }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md hover:border-green-200 transition-all group text-center">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-green-100">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-900">Tugas</p>
            <p class="text-xs text-gray-500 mt-0.5">Lihat tugas kelas</p>
        </a>
        <a href="{{ route('siswa.nilai.by-kelas', $kelas) }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md hover:border-yellow-200 transition-all group text-center">
            <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-yellow-100">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-900">Nilai</p>
            <p class="text-xs text-gray-500 mt-0.5">Lihat nilai kelas</p>
        </a>
        <a href="{{ route('siswa.absensi.by-kelas', $kelas) }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md hover:border-indigo-200 transition-all group text-center">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-indigo-100">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-900">Absensi</p>
            <p class="text-xs text-gray-500 mt-0.5">Lihat kehadiran</p>
        </a>
    </div>

    {{-- Feed --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($feed as $item)
                <div class="px-6 py-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full {{ $item['type'] === 'materi' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }} flex items-center justify-center flex-shrink-0">
                            @if($item['type'] === 'materi')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold uppercase tracking-wider {{ $item['type'] === 'materi' ? 'text-blue-600' : 'text-green-600' }}">
                                    {{ $item['type'] === 'materi' ? 'Materi Baru' : 'Tugas Baru' }}
                                </span>
                                <span class="text-xs text-gray-400">• {{ $item['created_at']->diffForHumans() }}</span>
                            </div>
                            @if($item['type'] === 'materi')
                                <a href="{{ route('siswa.materi.show', $item['id']) }}" class="text-sm font-semibold text-gray-900 hover:text-blue-600">{{ $item['title'] }}</a>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $item['mapel'] }} • {{ $item['user_name'] }}</p>
                            @else
                                <a href="{{ route('siswa.tugas.show', $item['id']) }}" class="text-sm font-semibold text-gray-900 hover:text-green-600">{{ $item['title'] }}</a>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $item['mapel'] }} • {{ $item['user_name'] }}</p>
                                @if(isset($item['deadline']))
                                    <p class="text-xs text-gray-500">Deadline: {{ $item['deadline']->translatedFormat('d M Y, H:i') }}
                                        @if(isset($item['is_expired']) && $item['is_expired'])
                                            <span class="text-red-600 font-medium"> (Lewat)</span>
                                        @elseif(isset($item['is_submitted']) && $item['is_submitted'])
                                            <span class="text-green-600 font-medium"> ✓ Dikirim</span>
                                        @endif
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center">
                    <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <p class="mt-3 text-gray-500 text-sm">Belum ada aktivitas di kelas ini</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Leave Class Form --}}
    <div class="text-right">
        <form action="{{ route('siswa.kelas.leave', $kelas) }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari kelas {{ $kelas->nama_kelas }}?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar Kelas
            </button>
        </form>
    </div>
</div>
@endsection
