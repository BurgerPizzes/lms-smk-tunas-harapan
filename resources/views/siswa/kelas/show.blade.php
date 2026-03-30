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
                    {{ $kelas->guru?->name ?? 'Guru Pengampu' }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Kode: {{ strtoupper($kelas->kode_unik) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Tabs + Content --}}
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            {{-- Tab Navigation --}}
            <div class="bg-white rounded-t-xl border border-b-0 border-gray-100 px-2">
                <nav class="flex gap-1 overflow-x-auto -mb-px">
                    <button onclick="switchTab('feed')" id="tab-feed" class="tab-btn flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        Feed
                    </button>
                    <button onclick="switchTab('materi')" id="tab-materi" class="tab-btn flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Materi
                    </button>
                    <button onclick="switchTab('tugas')" id="tab-tugas" class="tab-btn flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Tugas
                    </button>
                    <button onclick="switchTab('nilai')" id="tab-nilai" class="tab-btn flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Nilai
                    </button>
                    <button onclick="switchTab('absensi')" id="tab-absensi" class="tab-btn flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Absensi
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="bg-white rounded-b-xl border border-gray-100 shadow-sm">

                {{-- Feed Tab --}}
                <div id="content-feed" class="tab-content p-6 space-y-6">
                    @forelse($feedItems as $item)
                        <div class="border border-gray-100 rounded-xl p-5 hover:shadow-sm transition-shadow">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full {{ $item->type === 'announcement' ? 'bg-red-100 text-red-600' : ($item->type === 'materi' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600') }} flex items-center justify-center flex-shrink-0">
                                    @if($item->type === 'announcement')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    @elseif($item->type === 'materi')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-semibold uppercase tracking-wider {{ $item->type === 'announcement' ? 'text-red-600' : ($item->type === 'materi' ? 'text-blue-600' : 'text-green-600') }}">
                                            {{ $item->type === 'announcement' ? 'Pengumuman' : ($item->type === 'materi' ? 'Materi Baru' : 'Tugas Baru') }}
                                        </span>
                                        <span class="text-xs text-gray-400">• {{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $item->judul }}</h3>
                                    @if($item->type === 'tugas' && isset($item->deadline))
                                        <p class="text-xs text-gray-500 mt-1">Deadline: {{ $item->deadline->translatedFormat('d M Y, H:i') }}</p>
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

                {{-- Materi Tab --}}
                <div id="content-materi" class="tab-content p-6 hidden">
                    <div class="space-y-3">
                        @forelse($materiList as $materi)
                            <a href="{{ route('siswa.materi.show', [$kelas, $materi]) }}" class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl hover:shadow-sm hover:border-blue-200 transition-all group">
                                <div class="w-10 h-10 rounded-lg {{ $materi->tipe === 'video' ? 'bg-red-100 text-red-600' : ($materi->tipe === 'file' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }} flex items-center justify-center flex-shrink-0">
                                    @if($materi->tipe === 'video')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($materi->tipe === 'file')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 truncate">{{ $materi->judul }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Pertemuan {{ $materi->pertemuan_ke }} • {{ $materi->mapel?->nama_mapel }}</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @empty
                            <div class="py-16 text-center">
                                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <p class="mt-3 text-gray-500 text-sm">Belum ada materi di kelas ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Tugas Tab --}}
                <div id="content-tugas" class="tab-content p-6 hidden">
                    <div class="space-y-3">
                        @forelse($tugasList as $tugas)
                            <a href="{{ route('siswa.tugas.show', [$kelas, $tugas]) }}" class="block p-4 border border-gray-100 rounded-xl hover:shadow-sm hover:border-blue-200 transition-all group">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600">{{ $tugas->judul }}</p>
                                        <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs text-gray-500">
                                            <span>{{ $tugas->mapel?->nama_mapel }}</span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $tugas->deadline->translatedFormat('d M Y, H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                    @php
                                        $submission = $tugas->submissions->where('siswa_id', auth()->id())->first();
                                    @endphp
                                    @if($submission)
                                        @if($submission->nilai !== null)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 flex-shrink-0">Dinilai: {{ $submission->nilai }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">Dikirim</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 flex-shrink-0">Belum Dikirim</span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="py-16 text-center">
                                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="mt-3 text-gray-500 text-sm">Belum ada tugas di kelas ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Nilai Tab --}}
                <div id="content-nilai" class="tab-content p-6 hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tugas</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($nilaiList as $index => $nilai)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $nilai->tugas?->judul }}</p>
                                            <p class="text-xs text-gray-400">{{ $nilai->updated_at->translatedFormat('d M Y') }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-sm font-bold {{ $nilai->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $nilai->nilai }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($nilai->nilai >= 75)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-12 text-center text-sm text-gray-500">Belum ada nilai</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Absensi Tab --}}
                <div id="content-absensi" class="tab-content p-6 hidden">
                    @if(isset($absensiSummary))
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $absensiSummary['hadir'] ?? 0 }}</p>
                                <p class="text-xs text-green-700 font-medium mt-1">Hadir</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $absensiSummary['izin'] ?? 0 }}</p>
                                <p class="text-xs text-blue-700 font-medium mt-1">Izin</p>
                            </div>
                            <div class="bg-yellow-50 rounded-xl p-4 text-center">
                                <p class="text-2xl font-bold text-yellow-600">{{ $absensiSummary['sakit'] ?? 0 }}</p>
                                <p class="text-xs text-yellow-700 font-medium mt-1">Sakit</p>
                            </div>
                            <div class="bg-red-50 rounded-xl p-4 text-center">
                                <p class="text-2xl font-bold text-red-600">{{ $absensiSummary['alpha'] ?? 0 }}</p>
                                <p class="text-xs text-red-700 font-medium mt-1">Alpha</p>
                            </div>
                        </div>
                        <a href="{{ route('siswa.absensi.by-kelas', $kelas) }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Detail Absensi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <div class="py-16 text-center">
                            <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="mt-3 text-gray-500 text-sm">Belum ada data absensi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="w-full lg:w-80 space-y-5">
            {{-- Class Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Info Kelas</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Jurusan</span>
                        <span class="font-medium text-gray-900">{{ $kelas->jurusan?->nama ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Tahun Ajaran</span>
                        <span class="font-medium text-gray-900">{{ $kelas->tahun_ajaran ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Semester</span>
                        <span class="font-medium text-gray-900">{{ $kelas->semester ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Siswa</span>
                        <span class="font-medium text-gray-900">{{ $kelas->siswa_count ?? 0 }} siswa</span>
                    </div>
                </div>
            </div>

            {{-- Guru --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Guru</h3>
                <div class="space-y-3">
                    @if($kelas->guru)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                                {{ strtoupper(substr($kelas->guru->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $kelas->guru->name }}</p>
                                <p class="text-xs text-gray-500">Guru Pengampu</p>
                            </div>
                        </div>
                    @endif
                    @if(isset($guruList) && $guruList->count() > 1)
                        <hr class="border-gray-100">
                        @foreach($guruList as $g)
                            @if($g->id !== $kelas->guru?->id)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($g->name, 0, 1)) }}
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $g->name }}</p>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('border-blue-600', 'text-blue-600');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById('content-' + tabName).classList.remove('hidden');
    const btn = document.getElementById('tab-' + tabName);
    btn.classList.remove('border-transparent', 'text-gray-500');
    btn.classList.add('border-blue-600', 'text-blue-600');
}
</script>
@endpush
@endsection
