@extends('layouts.siswa')

@section('title', 'Detail Pengumpulan')

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.submissions.index') }}" class="hover:text-blue-600">Pengumpulan Saya</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">Detail</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            {{-- Tugas Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Informasi Tugas
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Judul</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $submission->tugas?->judul }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Kelas</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $submission->tugas?->kelas?->nama_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Mata Pelajaran</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $submission->tugas?->mapel?->nama_mapel }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Deadline</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $submission->tugas?->deadline?->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- My Submission --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Pengumpulan Saya
                    </h2>
                    @php
                        $isLate = $submission->submitted_at->gt($submission->tugas?->deadline);
                        $canEdit = $submission->nilai === null && now()->lt($submission->tugas?->deadline);
                    @endphp
                    @if($canEdit)
                        <a href="{{ route('siswa.tugas.show', [$submission->tugas?->kelas, $submission->tugas]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                    @endif
                </div>

                <div class="space-y-4">
                    {{-- Submitted Content --}}
                    @if($submission->content)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">Jawaban / Catatan</p>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $submission->content }}</div>
                        </div>
                    @endif

                    {{-- File --}}
                    @if($submission->file_path)
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-2">File Terlampir</p>
                            <a href="{{ route('siswa.submissions.download', $submission) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-sm text-blue-700 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Unduh File
                            </a>
                        </div>
                    @endif

                    {{-- Submitted At --}}
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Dikumpulkan: <strong>{{ $submission->submitted_at->translatedFormat('d M Y, H:i') }}</strong></span>
                        @if($isLate)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Terlambat</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tepat Waktu</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Grade --}}
            @if($submission->nilai !== null)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Penilaian
                    </h2>
                    <div class="flex items-center gap-6 mb-4">
                        <div class="w-24 h-24 rounded-2xl {{ $submission->nilai >= 75 ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center flex-shrink-0">
                            <span class="text-4xl font-bold {{ $submission->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $submission->nilai }}</span>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $submission->nilai >= 75 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $submission->nilai >= 75 ? '✓ Lulus' : '✗ Tidak Lulus' }}
                            </span>
                            <p class="text-xs text-gray-500 mt-2">Dinilai pada {{ $submission->updated_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    @if($submission->feedback)
                        <div class="bg-purple-50 border border-purple-100 rounded-xl p-4 mt-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <p class="text-sm font-semibold text-purple-800">Feedback dari Guru</p>
                            </div>
                            <p class="text-sm text-purple-700 whitespace-pre-wrap">{{ $submission->feedback }}</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center gap-3 text-gray-400">
                        <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-medium">Belum dinilai. Guru sedang memeriksa tugas kamu.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="w-full lg:w-72">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Ringkasan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Status</span>
                        @if($submission->nilai !== null)
                            <span class="font-medium {{ $submission->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $submission->nilai >= 75 ? 'Lulus' : 'Tidak Lulus' }}</span>
                        @else
                            <span class="font-medium text-blue-600">Menunggu Nilai</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Nilai</span>
                        <span class="font-medium text-gray-900">{{ $submission->nilai ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Keterlambatan</span>
                        <span class="font-medium {{ $isLate ? 'text-red-600' : 'text-green-600' }}">{{ $isLate ? 'Ya' : 'Tidak' }}</span>
                    </div>
                </div>
                <hr class="border-gray-100">
                <a href="{{ route('siswa.submissions.index') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
