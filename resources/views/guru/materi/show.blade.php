@extends('layouts.guru')
@section('title', $materi->judul ?? 'Detail Materi')
@section('page-content')

<!-- Back Link & Actions -->
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('guru.kelas.show', $materi->kelas_id ?? '') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Kelas
    </a>
    @if($materi->guru_id == auth()->id() || auth()->user()->hasRole('admin'))
    <div class="flex items-center space-x-2">
        <form method="POST" action="{{ route('guru.materi.toggle-publish', $materi->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $materi->is_published ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400' }}">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                {{ $materi->is_published ? 'Batalkan Terbit' : 'Terbitkan' }}
            </button>
        </form>
        <a href="{{ route('guru.materi.edit', $materi->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
            Edit
        </a>
        <form method="POST" action="{{ route('guru.materi.destroy', $materi->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                Hapus
            </button>
        </form>
    </div>
    @endif
</div>

<!-- Materi Detail -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                        {{ $materi->mapel->nama ?? '-' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $materi->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $materi->is_published ? 'Diterbitkan' : 'Draft' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                        Pertemuan {{ $materi->pertemuan_ke ?? '-' }}
                    </span>
                </div>
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $materi->judul }}</h1>
            <div class="flex items-center space-x-3 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center space-x-1.5">
                    <div class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                        {{ strtoupper(substr($materi->guru->name ?? 'G', 0, 1)) }}
                    </div>
                    <span>{{ $materi->guru->name ?? '-' }}</span>
                </div>
                <span>&middot;</span>
                <span>{{ $materi->created_at->translatedFormat('d F Y') }}</span>
            </div>
            @if($materi->deskripsi)
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $materi->deskripsi }}</p>
            @endif
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            @if($materi->tipe === 'file' && $materi->file)
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-indigo-100 dark:bg-indigo-900/40 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">{{ pathinfo($materi->file, PATHINFO_BASENAME) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ $materi->file_size ?? '' }}</p>
                    <a href="{{ Storage::url($materi->file) }}" download class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Download File
                    </a>
                </div>
            @elseif($materi->tipe === 'video' && $materi->video_url)
                <div class="aspect-video rounded-xl overflow-hidden">
                    @php
                        $ytId = preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^&\n?#]+)/', $materi->video_url, $matches) ? $matches[1] : null;
                    @endphp
                    @if($ytId)
                        <iframe src="https://www.youtube.com/embed/{{ $ytId }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                    @else
                        <a href="{{ $materi->video_url }}" target="_blank" class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                <p class="text-sm text-gray-500">Tonton Video</p>
                            </div>
                        </a>
                    @endif
                </div>
            @elseif($materi->tipe === 'text' && $materi->konten)
                <div class="prose dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    {!! $materi->konten !!}
                </div>
            @elseif($materi->tipe === 'link' && $materi->link_url)
                <a href="{{ $materi->link_url }}" target="_blank" rel="noopener" class="flex items-center space-x-4 p-4 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $materi->link_url }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Klik untuk membuka link</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                </a>
            @else
                <div class="text-center py-8 text-sm text-gray-500 dark:text-gray-400">Tidak ada konten untuk ditampilkan.</div>
            @endif
        </div>

        <!-- Comments Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                    Komentar ({{ $comments->count() ?? 0 }})
                </h3>
            </div>
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('guru.materi.comment.store', $materi->id) }}">
                    @csrf
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <textarea name="komentar" rows="2" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none" placeholder="Tulis komentar..."></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="px-4 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Kirim</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @forelse($comments ?? [] as $comment)
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-400 flex-shrink-0">
                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $comment->user->name ?? '-' }}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $comment->komentar ?? $comment->body ?? $comment->content }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada komentar.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Detail Materi</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tipe</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $materi->tipe }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kelas</p>
                    <a href="{{ route('guru.kelas.show', $materi->kelas_id) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">{{ $materi->kelas->nama ?? '-' }}</a>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Mata Pelajaran</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $materi->mapel->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pertemuan Ke</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $materi->pertemuan_ke ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Dibuat</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $materi->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Diperbarui</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $materi->updated_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
