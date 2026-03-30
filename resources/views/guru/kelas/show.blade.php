@extends('layouts.guru')
@section('title', $kelas->nama ?? 'Detail Kelas')
@section('page-content')

<!-- Class Header Banner -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
    <div class="h-36 bg-gradient-to-br from-blue-500 to-indigo-600 relative">
        @if($kelas->cover_image)
            <img src="{{ Storage::url($kelas->cover_image) }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        @endif
        <div class="absolute bottom-4 left-6 right-6">
            <div class="flex items-end justify-between">
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $kelas->nama }}</h1>
                    <div class="flex items-center space-x-3 mt-1">
                        <span class="text-sm text-white/80">{{ $kelas->jurusan->nama ?? '-' }}</span>
                        <span class="text-white/40">|</span>
                        <span class="text-sm text-white/80">Kelas {{ $kelas->tingkat }}</span>
                        <span class="text-white/40">|</span>
                        <span class="text-sm text-white/80">{{ $kelas->siswa_count ?? $kelas->siswa->count() }} siswa</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="navigator.clipboard.writeText('{{ $kelas->kode_unik }}')" class="flex items-center space-x-1.5 px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg hover:bg-white/30 transition-colors text-white text-xs font-medium">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                        <span>{{ $kelas->kode_unik }}</span>
                    </button>
                    <a href="{{ route('guru.kelas.members', $kelas->id) }}" class="flex items-center space-x-1.5 px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg hover:bg-white/30 transition-colors text-white text-xs font-medium">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                        <span>Anggota</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 overflow-x-auto">
        <nav class="flex space-x-6 -mb-px" id="kelas-tabs">
            <button onclick="switchTab('feed')" id="tab-feed" class="tab-btn flex items-center space-x-2 py-3 border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 font-medium text-sm whitespace-nowrap">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                <span>Feed</span>
            </button>
            <button onclick="switchTab('materi')" id="tab-materi" class="tab-btn flex items-center space-x-2 py-3 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                <span>Materi</span>
            </button>
            <button onclick="switchTab('tugas')" id="tab-tugas" class="tab-btn flex items-center space-x-2 py-3 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" /></svg>
                <span>Tugas</span>
            </button>
            <button onclick="switchTab('siswa')" id="tab-siswa" class="tab-btn flex items-center space-x-2 py-3 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                <span>Siswa</span>
            </button>
            <button onclick="switchTab('absensi')" id="tab-absensi" class="tab-btn flex items-center space-x-2 py-3 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                <span>Absensi</span>
            </button>
            <button onclick="switchTab('quiz')" id="tab-quiz" class="tab-btn flex items-center space-x-2 py-3 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm whitespace-nowrap">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" /></svg>
                <span>Quiz</span>
            </button>
        </nav>
    </div>
</div>

<!-- Tab Content Area with Sidebar -->
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Content -->
    <div class="flex-1 min-w-0">

        <!-- FEED TAB -->
        <div id="content-feed" class="tab-content space-y-4">
            <!-- Post Announcement -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-sm font-bold text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <form method="POST" action="{{ route('guru.diskusi.store') }}">
                            @csrf
                            <textarea name="pesan" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none" placeholder="Bagikan pengumuman ke kelas..."></textarea>
                            <div class="flex items-center justify-end mt-2 space-x-2">
                                <button type="button" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" /></svg>
                                </button>
                                <button type="submit" class="px-4 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            @forelse($feedItems ?? [] as $item)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-sm transition-shadow">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 {{ $item->type === 'materi' ? 'bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400' : ($item->type === 'tugas' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400') }} rounded-full flex items-center justify-center flex-shrink-0">
                        @if($item->type === 'materi')
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                        @elseif($item->type === 'tugas')
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" /></svg>
                        @else
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 1 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" /></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ $item->guru->name ?? auth()->user()->name }} memposting {{ $item->type === 'materi' ? 'materi' : ($item->type === 'tugas' ? 'tugas' : 'pengumuman') }}
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">&middot;</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ $item->type === 'materi' ? route('guru.materi.show', $item->id) : route('guru.tugas.show', $item->id) }}" class="text-base font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $item->judul ?? $item->pesan ?? '-' }}</a>
                        @if($item->type === 'tugas')
                            <div class="flex items-center space-x-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center space-x-1">
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                    <span>Deadline: {{ $item->deadline?->format('d M Y, H:i') ?? '-' }}</span>
                                </span>
                            </div>
                        @endif
                        @if(isset($item->deskripsi) && $item->deskripsi)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ Str::limit($item->deskripsi, 150) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum ada aktivitas</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Mulai posting materi atau tugas untuk kelas ini.</p>
            </div>
            @endforelse
        </div>

        <!-- MATERI TAB -->
        <div id="content-materi" class="tab-content hidden">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <select onchange="window.location.href=this.value" class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Mapel</option>
                        @foreach($mapels ?? [] as $mapel)
                            <option value="?mapel={{ $mapel->id }}">{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('guru.kelas.materi.create', $kelas->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tambah Materi
                </a>
            </div>
            <div class="space-y-3">
                @forelse($materiList ?? [] as $materi)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3 flex-1 min-w-0">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/40 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('guru.materi.show', $materi->id) }}" class="text-sm font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $materi->judul }}</a>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $materi->mapel->nama ?? '-' }}</span>
                                    <span class="text-xs text-gray-300 dark:text-gray-600">&middot;</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Pertemuan {{ $materi->pertemuan_ke ?? '-' }}</span>
                                    <span class="text-xs text-gray-300 dark:text-gray-600">&middot;</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $materi->created_at->diffForHumans() }}</span>
                                </div>
                                @if($materi->deskripsi)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ Str::limit($materi->deskripsi, 100) }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $materi->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $materi->is_published ? 'Terbit' : 'Draft' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada materi di kelas ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- TUGAS TAB -->
        <div id="content-tugas" class="tab-content hidden">
            <div class="flex items-center justify-end mb-4">
                <a href="{{ route('guru.kelas.tugas.create', $kelas->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Buat Tugas
                </a>
            </div>
            <div class="space-y-3">
                @forelse($tugasList ?? [] as $tugas)
                <a href="{{ route('guru.tugas.show', $tugas->id) }}" class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tugas->judul }}</h4>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tugas->mapel->nama ?? '-' }}</span>
                                <span class="text-xs text-gray-300 dark:text-gray-600">&middot;</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-3 h-3 inline mr-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                    {{ $tugas->deadline?->format('d M Y, H:i') ?? 'Tanpa Deadline' }}
                                </span>
                                <span class="text-xs text-gray-300 dark:text-gray-600">&middot;</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tugas->submitted_count ?? 0 }}/{{ $kelas->siswa_count ?? $kelas->siswa->count() }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
                            @if($tugas->deadline && $tugas->deadline->isPast())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Lewat</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada tugas di kelas ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- SISWA TAB -->
        <div id="content-siswa" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">No</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nama Siswa</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">NIS</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($siswaList ?? [] as $index => $siswa)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ strtoupper(substr($siswa->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $siswa->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $siswa->nis ?? '-' }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="text-sm font-medium {{ ($siswa->attendance_percentage ?? 100) >= 80 ? 'text-green-600 dark:text-green-400' : (($siswa->attendance_percentage ?? 100) >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ $siswa->attendance_percentage ?? '-' }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada siswa di kelas ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ABSENSI TAB -->
        <div id="content-absensi" class="tab-content hidden">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('guru.kelas.absensi.create', $kelas->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Input Absensi
                </a>
                <a href="{{ route('guru.absensi.recap', $kelas->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75Z" /></svg>
                    Rekap Absensi
                </a>
            </div>
            <div class="space-y-3">
                @forelse($absensiList ?? [] as $absensi)
                <a href="{{ route('guru.absensi.show', $absensi->id) }}" class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-sm transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $absensi->mapel->nama ?? '-' }} - Pertemuan {{ $absensi->pertemuan_ke ?? '-' }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $absensi->tanggal->format('d F Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-3 text-xs">
                            <span class="text-green-600 dark:text-green-400 font-medium">{{ $absensi->hadir_count ?? 0 }} H</span>
                            <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ $absensi->izin_count ?? 0 }} I</span>
                            <span class="text-red-600 dark:text-red-400 font-medium">{{ $absensi->alpha_count ?? 0 }} A</span>
                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </div>
                    </div>
                </a>
                @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada sesi absensi.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- QUIZ TAB -->
        <div id="content-quiz" class="tab-content hidden">
            <div class="flex items-center justify-end mb-4">
                <a href="{{ route('guru.kelas.quiz.create', $kelas->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Buat Quiz
                </a>
            </div>
            <div class="space-y-3">
                @forelse($quizList ?? [] as $quiz)
                <a href="{{ route('guru.quiz.show', $quiz->id) }}" class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $quiz->judul }}</h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $quiz->mapel->nama ?? '-' }}</span>
                                <span class="text-xs text-gray-300 dark:text-gray-600">&middot;</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $quiz->soal_count ?? 0 }} soal</span>
                                <span class="text-xs text-gray-300 dark:text-gray-600">&middot;</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $quiz->durasi_menit }} menit</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $quiz->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $quiz->is_published ? 'Aktif' : 'Draft' }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada quiz di kelas ini.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Right Sidebar -->
    <div class="w-full lg:w-72 flex-shrink-0 space-y-4">
        <!-- Class Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Info Kelas</h3>
            <div class="space-y-3">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" /></svg>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Jurusan</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $kelas->jurusan->nama ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Jumlah Siswa</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $kelas->siswa_count ?? $kelas->siswa->count() }} siswa</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tahun Ajaran</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $kelas->tahun_ajaran ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kode Unik</p>
                        <div class="flex items-center space-x-1.5 mt-0.5">
                            <code class="text-sm font-mono bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded">{{ $kelas->kode_unik }}</code>
                            <button onclick="navigator.clipboard.writeText('{{ $kelas->kode_unik }}')" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guru Pengampu -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Guru Pengampu</h3>
            <div class="space-y-2.5">
                @forelse($guruList ?? [] as $guru)
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center text-xs font-bold text-blue-600 dark:text-blue-400">
                        {{ strtoupper(substr($guru->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $guru->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $guru->pivot->mapel ?? $guru->mapel ?? 'Guru' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-500 dark:text-gray-400">Tidak ada data guru.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Tab Switching Script -->
<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
        btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    });
    const content = document.getElementById('content-' + tabName);
    const tab = document.getElementById('tab-' + tabName);
    if (content) content.classList.remove('hidden');
    if (tab) {
        tab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        tab.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
    }
}
</script>

@endsection
