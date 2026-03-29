@extends('layouts.siswa')

@section('title', $tugas->judul)

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.kelas.index') }}" class="hover:text-blue-600">Kelas</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('siswa.kelas.show', $kelas) }}" class="hover:text-blue-600">{{ $kelas->nama_kelas }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('siswa.tugas.index', $kelas) }}" class="hover:text-blue-600">Tugas</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium truncate max-w-xs">{{ $tugas->judul }}</span>
    </nav>

    {{-- Tugas Header --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-bold text-gray-900">{{ $tugas->judul }}</h1>
                <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-500">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $tugas->mapel?->nama_mapel }}</span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $tugas->guru?->name }}
                    </span>
                </div>
            </div>
            <div class="text-left sm:text-right flex-shrink-0">
                @php
                    $submission = $tugas->submissions->where('siswa_id', auth()->id())->first();
                    $isLate = $submission && $submission->submitted_at->gt($tugas->deadline);
                @endphp
                @if($isLate)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Terlambat</span>
                @elseif($submission && $submission->nilai !== null)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Dinilai</span>
                @elseif($submission)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Dikirim</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Belum Dikirim</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Description + Submission --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Deadline Card --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl {{ now()->gt($tugas->deadline) && !$submission ? 'bg-red-100' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 {{ now()->gt($tugas->deadline) && !$submission ? 'text-red-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</p>
                        <p class="text-lg font-bold {{ now()->gt($tugas->deadline) && !$submission ? 'text-red-600' : 'text-gray-900' }}">{{ $tugas->deadline->translatedFormat('l, d F Y, H:i') }}</p>
                        @php
                            $diff = now()->diffInHours($tugas->deadline, false);
                        @endphp
                        @if($diff > 0 && !$submission)
                            <p class="text-sm text-gray-500 mt-0.5"><span class="font-semibold">{{ $diff }} jam </span> lagi</p>
                        @elseif(!$submission)
                            <p class="text-sm text-red-600 font-semibold mt-0.5">Sudah lewat!</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Deskripsi & Instruksi</h2>
                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                    {!! $tugas->deskripsi !!}
                </div>

                {{-- File Attachment --}}
                @if($tugas->file_path)
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Lampiran Tugas</h3>
                        <a href="{{ route('siswa.tugas.download', [$kelas, $tugas]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors text-sm">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-gray-700 font-medium">Unduh Lampiran</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Submission Card --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Pengumpulan
                    </h2>
                </div>
                <div class="p-6">

                    {{-- Not Submitted --}}
                    @if(!$submission)
                        @if(now()->gt($tugas->deadline))
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                                <svg class="w-10 h-10 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-red-700 font-medium mt-2">Waktu pengumpulan telah berakhir</p>
                            </div>
                        @else
                            <form action="{{ route('siswa.tugas.submit', [$kelas, $tugas]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jawaban / Catatan</label>
                                    <textarea name="content" rows="5" placeholder="Tulis jawaban atau catatan kamu di sini..." required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none resize-none"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Lampiran File</label>
                                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-300 transition-colors">
                                        <svg class="w-10 h-10 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                        <p class="mt-2 text-sm text-gray-500">Seret file atau <label class="text-blue-600 font-medium cursor-pointer hover:text-blue-700">pilih file<input type="file" name="file" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar"></label></p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, DOC, PPT, XLS, JPG, PNG, ZIP (Maks. 10MB)</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-2">
                                    <p class="text-xs text-gray-400">
                                        <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Pengumpulan terlambat akan ditandai
                                    </p>
                                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Kumpulkan Tugas
                                    </button>
                                </div>
                            </form>
                        @endif

                    {{-- Submitted but not graded --}}
                    @elseif($submission && $submission->nilai === null)
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 bg-blue-50 rounded-xl p-4">
                                <svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-800">Tugas Sudah Dikirim</p>
                                    <p class="text-xs text-blue-600">Dikumpulkan pada {{ $submission->submitted_at->translatedFormat('d M Y, H:i') }}{{ $isLate ? ' (Terlambat)' : '' }}</p>
                                </div>
                            </div>
                            @if($submission->content)
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-xs font-medium text-gray-500 mb-1">Jawaban:</p>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $submission->content }}</p>
                                </div>
                            @endif
                            @if($submission->file_path)
                                <a href="{{ route('siswa.submissions.download', $submission) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 text-sm text-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Lihat File
                                </a>
                            @endif
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center gap-3">
                                <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-yellow-800">Menunggu guru untuk menilai tugas kamu</p>
                            </div>
                        </div>

                    {{-- Graded --}}
                    @else
                        <div class="space-y-4">
                            {{-- Grade Display --}}
                            <div class="flex items-center gap-4 bg-green-50 rounded-xl p-5">
                                <div class="w-16 h-16 rounded-2xl {{ $submission->nilai >= 75 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center flex-shrink-0">
                                    <span class="text-2xl font-bold">{{ $submission->nilai }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold {{ $submission->nilai >= 75 ? 'text-green-800' : 'text-red-800' }}">
                                        {{ $submission->nilai >= 75 ? 'Lulus ✓' : 'Tidak Lulus' }}
                                    </p>
                                    <p class="text-xs {{ $submission->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                        Dinilai pada {{ $submission->updated_at->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Submitted Info --}}
                            <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                <p class="text-xs font-medium text-gray-500">Dikumpulkan: {{ $submission->submitted_at->translatedFormat('d M Y, H:i') }}{{ $isLate ? ' (Terlambat)' : '' }}</p>
                                @if($submission->content)
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $submission->content }}</p>
                                @endif
                                @if($submission->file_path)
                                    <a href="{{ route('siswa.submissions.download', $submission) }}" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Unduh File
                                    </a>
                                @endif
                            </div>

                            {{-- Feedback --}}
                            @if($submission->feedback)
                                <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        <p class="text-sm font-semibold text-purple-800">Feedback dari Guru</p>
                                    </div>
                                    <p class="text-sm text-purple-700 whitespace-pre-wrap">{{ $submission->feedback }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Comments Section --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Komentar ({{ $comments->count() }})
                </h2>
                <form action="{{ route('siswa.tugas.comment.store', [$kelas, $tugas]) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <textarea name="content" rows="2" placeholder="Tulis pertanyaan atau komentar..." required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none resize-none"></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Kirim</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="space-y-4 max-h-80 overflow-y-auto">
                    @forelse($comments as $comment)
                        <div class="flex gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-50 rounded-xl px-4 py-3">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-semibold text-gray-900">{{ $comment->user->name ?? 'User' }}</span>
                                        <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada komentar</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="w-full lg:w-72 space-y-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Info Tugas</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Mapel</span>
                        <span class="font-medium text-gray-900">{{ $tugas->mapel?->nama_mapel }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Kelas</span>
                        <span class="font-medium text-gray-900">{{ $kelas->nama_kelas }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Dibuat</span>
                        <span class="font-medium text-gray-900">{{ $tugas->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Deadline</span>
                        <span class="font-medium text-gray-900">{{ $tugas->deadline->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
