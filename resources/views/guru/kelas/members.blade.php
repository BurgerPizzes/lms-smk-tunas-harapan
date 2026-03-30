@extends('layouts.guru')
@section('title', 'Anggota Kelas - ' . ($kelas->nama ?? ''))
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <a href="{{ route('guru.kelas.show', $kelas->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-3 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke {{ $kelas->nama }}
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Anggota Kelas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $kelas->nama }} &middot; {{ $kelas->kode_unik }}</p>
        </div>
    </div>
</div>

<!-- Member Tabs -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6">
        <nav class="flex space-x-6 -mb-px" id="member-tabs">
            <button onclick="switchMemberTab('siswa')" id="mtab-siswa" class="mtab-btn py-3 border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 font-medium text-sm">
                Siswa ({{ $siswaList->count() ?? 0 }})
            </button>
            <button onclick="switchMemberTab('guru')" id="mtab-guru" class="mtab-btn py-3 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm">
                Guru Pengampu ({{ $guruList->count() ?? 0 }})
            </button>
        </nav>
    </div>

    <!-- Siswa Tab -->
    <div id="mcontent-siswa" class="mcontent-tab">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    <input type="text" id="search-siswa" placeholder="Cari siswa..." class="pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-64" onkeyup="filterMembers()">
                </div>
            </div>
            <div class="space-y-2" id="siswa-list">
                @forelse($siswaList ?? [] as $siswa)
                <div class="member-item flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors" data-name="{{ strtolower($siswa->name ?? '') }}">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-sm font-bold text-indigo-600 dark:text-indigo-400">
                            {{ strtoupper(substr($siswa->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $siswa->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">NIS: {{ $siswa->nis ?? '-' }} &middot; Bergabung {{ $siswa->pivot->joined_at?->diffForHumans() ?? $siswa->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('guru.kelas.remove-member'), [$kelas->id, $siswa->id]) }}" onsubmit="return confirm('Yakin ingin menghapus {{ $siswa->name }} dari kelas ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus dari kelas">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>
                    </form>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada siswa di kelas ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Guru Tab -->
    <div id="mcontent-guru" class="mcontent-tab hidden">
        <div class="p-6">
            <div class="space-y-2">
                @forelse($guruList ?? [] as $guru)
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center text-sm font-bold text-blue-600 dark:text-blue-400">
                            {{ strtoupper(substr($guru->name ?? 'G', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $guru->name }}</p>
                                @if(isset($guru->pivot) && $guru->pivot->is_primary)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">Wali Kelas</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $guru->email ?? '' }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $guru->pivot->mapel ?? 'Guru Mapel' }}</span>
                </div>
                @empty
                <div class="text-center py-12">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada guru pengampu.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function switchMemberTab(tab) {
    document.querySelectorAll('.mcontent-tab').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.mtab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
        btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    });
    const content = document.getElementById('mcontent-' + tab);
    const tabEl = document.getElementById('mtab-' + tab);
    if (content) content.classList.remove('hidden');
    if (tabEl) {
        tabEl.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        tabEl.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
    }
}
function filterMembers() {
    const q = document.getElementById('search-siswa').value.toLowerCase();
    document.querySelectorAll('#siswa-list .member-item').forEach(item => {
        item.style.display = item.dataset.name.includes(q) ? '' : 'none';
    });
}
</script>

@endsection
