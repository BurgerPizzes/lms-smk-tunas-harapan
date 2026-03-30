@extends('layouts.siswa')

@section('title', $materi->judul)

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.kelas.index') }}" class="hover:text-blue-600">Kelas</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('siswa.kelas.show', $kelas) }}" class="hover:text-blue-600">{{ $kelas->nama_kelas }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('siswa.materi.index', $kelas) }}" class="hover:text-blue-600">Materi</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium truncate max-w-xs">{{ $materi->judul }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Main Content --}}
        <div class="flex-1 min-w-0 space-y-6">
            {{-- Materi Header --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $materi->tipe === 'video' ? 'bg-red-100 text-red-600' : ($materi->tipe === 'file' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }} flex items-center justify-center flex-shrink-0">
                        @if($materi->tipe === 'video')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($materi->tipe === 'file')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl font-bold text-gray-900">{{ $materi->judul }}</h1>
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $materi->mapel?->nama_mapel }}</span>
                            <span>Pertemuan {{ $materi->pertemuan_ke }}</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $materi->guru?->name }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $materi->published_at?->translatedFormat('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                {{-- Video Embed --}}
                @if($materi->tipe === 'video' && $materi->video_url)
                    <div class="mb-6 rounded-xl overflow-hidden bg-gray-900 aspect-video">
                        <iframe src="{{ $materi->video_url }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                    </div>
                @endif

                {{-- Rich Text Content --}}
                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                    {!! $materi->konten !!}
                </div>

                {{-- File Downloads --}}
                @if($materi->attachments && $materi->attachments->count() > 0)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Lampiran</h3>
                        <div class="space-y-2">
                            @foreach($materi->attachments as $file)
                                <a href="{{ route('files.download', $file->id) }}" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                                    <div class="w-10 h-10 bg-white rounded-lg border border-gray-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600 truncate">{{ $file->file_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $file->file_size }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Link --}}
                @if($materi->link)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Tautan Terkait</h3>
                        <a href="{{ $materi->link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Buka Tautan
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Comments Section --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Komentar ({{ $comments->count() }})
                </h2>

                {{-- Comment Form --}}
                <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <textarea name="content" rows="2" placeholder="Tulis komentar..." required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none resize-none"></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Comments List --}}
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($comments as $comment)
                        <div class="flex gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-50 rounded-xl px-4 py-3">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-semibold text-gray-900">{{ $comment->user->name ?? 'User' }}</span>
                                        @if($comment->user->hasRole('guru'))
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700">GURU</span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-6">Belum ada komentar</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="w-full lg:w-72 space-y-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Detail Materi</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Tipe</span>
                        <span class="font-medium text-gray-900 capitalize">{{ $materi->tipe }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Mapel</span>
                        <span class="font-medium text-gray-900">{{ $materi->mapel?->nama_mapel }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Pertemuan</span>
                        <span class="font-medium text-gray-900">Ke-{{ $materi->pertemuan_ke }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Dipublikasikan</span>
                        <span class="font-medium text-gray-900">{{ $materi->published_at?->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            @if(isset($prevMateri) || isset($nextMateri))
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Navigasi</h3>
                    @if(isset($prevMateri))
                        <a href="{{ route('siswa.materi.show', [$kelas, $prevMateri]) }}" class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            {{ Str::limit($prevMateri->judul, 30) }}
                        </a>
                    @endif
                    @if(isset($nextMateri))
                        <a href="{{ route('siswa.materi.show', [$kelas, $nextMateri]) }}" class="flex items-center justify-end gap-2 text-sm text-blue-600 hover:text-blue-700">
                            {{ Str::limit($nextMateri->judul, 30) }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
