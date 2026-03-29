@extends('layouts.admin')
@section('title', 'Detail User')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
        <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Users</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <span class="text-gray-900 dark:text-white font-medium">{{ $user->name }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail User</h1>
</div>

<!-- Profile Header -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
        <div class="w-20 h-20 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center flex-shrink-0">
            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
        </div>
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : ($user->role === 'guru' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400') }}">
                    {{ ucfirst($user->role) }}
                </span>
                @if($user->is_active)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>Aktif
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>Nonaktif
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Bergabung sejak {{ $user->created_at->translatedFormat('d F Y') }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                Edit
            </a>
            <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline" onsubmit="return confirm('Yakin ingin mengubah status user ini?')">
                @csrf @method('PATCH')
                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/40 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Info Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Personal Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            Informasi Pribadi
        </h3>
        <dl class="space-y-3">
            <div class="flex justify-between items-start py-2 border-b border-gray-100 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</dd>
            </div>
            <div class="flex justify-between items-start py-2 border-b border-gray-100 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Tempat Lahir</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->tempat_lahir ?? '-' }}</dd>
            </div>
            <div class="flex justify-between items-start py-2 border-b border-gray-100 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Tanggal Lahir</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->tanggal_lahir ? $user->tanggal_lahir->translatedFormat('d F Y') : '-' }}</dd>
            </div>
            <div class="flex justify-between items-start py-2 border-b border-gray-100 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400">No. HP</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->no_hp ?? '-' }}</dd>
            </div>
            <div class="flex justify-between items-start py-2">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Alamat</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white text-right max-w-[250px]">{{ $user->alamat ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    <!-- Academic Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
            </svg>
            Informasi Akademik
        </h3>
        <dl class="space-y-3">
            <div class="flex justify-between items-start py-2 border-b border-gray-100 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $user->role === 'guru' ? 'NIP' : 'NIS' }}</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->nip ?? ($user->nis ?? '-') }}</dd>
            </div>
            @if($user->role === 'siswa')
            <div class="flex justify-between items-start py-2 border-b border-gray-100 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400">NISN</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->nisn ?? '-' }}</dd>
            </div>
            @endif
            <div class="flex justify-between items-start py-2 border-b border-gray-100 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Jurusan</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->jurusan->nama ?? '-' }}</dd>
            </div>
            <div class="flex justify-between items-start py-2 border-b border-gray-100 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Kelas</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->kelas->nama ?? '-' }}</dd>
            </div>
            <div class="flex justify-between items-start py-2">
                <dt class="text-sm text-gray-500 dark:text-gray-400">Login Terakhir</dt>
                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->last_login_at ? $user->last_login_at->translatedFormat('d F Y, H:i') : '-' }}</dd>
            </div>
        </dl>
    </div>
</div>

<!-- Related Data -->
@if($user->role === 'siswa' && isset($enrolledClasses))
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Kelas yang Diikuti</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <th class="px-4 py-2.5">No</th>
                    <th class="px-4 py-2.5">Nama Kelas</th>
                    <th class="px-4 py-2.5">Jurusan</th>
                    <th class="px-4 py-2.5">Wali Kelas</th>
                    <th class="px-4 py-2.5">Bergabung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($enrolledClasses as $index => $class)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $class->nama }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $class->jurusan->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $class->waliKelas->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $class->pivot->joined_at ?? $class->created_at->translatedFormat('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Belum bergabung dengan kelas manapun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@if($user->role === 'guru' && isset($teachingSubjects))
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Mata Pelajaran yang Diampu</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <th class="px-4 py-2.5">No</th>
                    <th class="px-4 py-2.5">Mata Pelajaran</th>
                    <th class="px-4 py-2.5">Kelas</th>
                    <th class="px-4 py-2.5">Tahun Ajaran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($teachingSubjects as $index => $subject)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $subject->mapel->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $subject->kelas->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $subject->tahunAjaran->nama ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Belum mengampu mata pelajaran manapun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Activity Log -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between p-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Log Aktivitas User</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <th class="px-5 py-3">Waktu</th>
                    <th class="px-5 py-3">Aksi</th>
                    <th class="px-5 py-3">Deskripsi</th>
                    <th class="px-5 py-3 hidden sm:table-cell">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($activityLogs ?? [] as $log)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $log->created_at->diffForHumans() }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $log->action === 'created' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : ($log->action === 'updated' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : ($log->action === 'deleted' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300')) }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $log->description }}</td>
                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell font-mono">{{ $log->ip_address ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
