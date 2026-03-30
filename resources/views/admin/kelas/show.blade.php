@extends('layouts.admin')
@section('title', 'Detail Kelas')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
        <a href="{{ route('admin.kelas.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Kelas</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <span class="text-gray-900 dark:text-white font-medium">{{ $kelas->nama }}</span>
    </div>
</div>

<!-- Class Info Card -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex items-start space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $kelas->nama }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">Kelas {{ $kelas->tingkat }}</span>
                    @if($kelas->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>Aktif
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kelas->jurusan->nama ?? '-' }} &middot; {{ $kelas->tahunAjaran->nama ?? '-' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Wali Kelas: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $kelas->waliKelas->name ?? '-' }}</span></p>
                <div class="flex items-center space-x-2 mt-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Kode Kelas:</span>
                    <code class="text-xs font-mono bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded select-all">{{ $kelas->kode_unik }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $kelas->kode_unik }}'); this.textContent='Tersalin!'; setTimeout(() => this.textContent='Salin', 1500)" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Salin</button>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.kelas.members', $kelas->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
                Kelola Anggota
            </a>
            <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                Edit
            </a>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-6">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex -mb-px space-x-6" id="kelas-tabs">
            <button onclick="switchTab('siswa')" id="tab-siswa" class="tab-btn py-3 px-1 text-sm font-medium border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                Siswa ({{ $kelas->siswa->count() }})
            </button>
            <button onclick="switchTab('guru')" id="tab-guru" class="tab-btn py-3 px-1 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap">
                Guru Pengampu
            </button>
            <button onclick="switchTab('statistik')" id="tab-statistik" class="tab-btn py-3 px-1 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap">
                Statistik
            </button>
        </nav>
    </div>
</div>

<!-- Tab Content: Siswa -->
<div id="content-siswa" class="tab-content">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">NIS</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($kelas->siswa ?? [] as $index => $siswa)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                                    <span class="text-xs font-bold text-blue-600 dark:text-blue-400">{{ strtoupper(substr($siswa->name, 0, 1)) }}</span>
                                </div>
                                <a href="{{ route('admin.users.show', $siswa->id) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">{{ $siswa->name }}</a>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400 font-mono hidden md:table-cell">{{ $siswa->nis ?? '-' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400 hidden lg:table-cell">{{ $siswa->email }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $siswa->pivot->joined_at ?? $siswa->created_at->translatedFormat('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada siswa di kelas ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tab Content: Guru -->
<div id="content-guru" class="tab-content hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Guru</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">NIP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($guruPengampu ?? [] as $index => $gp)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
                                    <span class="text-xs font-bold text-green-600 dark:text-green-400">{{ strtoupper(substr($gp->guru->name ?? '', 0, 1)) }}</span>
                                </div>
                                <a href="{{ route('admin.users.show', $gp->guru_id) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">{{ $gp->guru->name ?? '-' }}</a>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $gp->mapel->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400 font-mono hidden md:table-cell">{{ $gp->guru->nip ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada guru pengampu di kelas ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tab Content: Statistik -->
<div id="content-statistik" class="tab-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 text-center">
            <div class="w-12 h-12 mx-auto bg-purple-100 dark:bg-purple-900/40 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalMateri'] ?? 0 }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Materi</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 text-center">
            <div class="w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/40 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalTugas'] ?? 0 }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Tugas</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 text-center">
            <div class="w-12 h-12 mx-auto bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['averageGrade'] ?? '-' }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rata-rata Nilai</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
        btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    });
    document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
    document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    document.getElementById('tab-' + tabName).classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
    document.getElementById('content-' + tabName).classList.remove('hidden');
}
</script>
@endpush

@endsection
