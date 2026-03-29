@extends('layouts.admin')
@section('title', 'System Logs')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Logs</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Riwayat aktivitas seluruh pengguna sistem</p>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Mulai</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Akhir</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipe Aksi</label>
            <select name="action" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <option value="">Semua Aksi</option>
                <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipe Model</label>
            <select name="model_type" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <option value="">Semua Model</option>
                <option value="User" {{ request('model_type') === 'User' ? 'selected' : '' }}>User</option>
                <option value="Kelas" {{ request('model_type') === 'Kelas' ? 'selected' : '' }}>Kelas</option>
                <option value="Materi" {{ request('model_type') === 'Materi' ? 'selected' : '' }}>Materi</option>
                <option value="Tugas" {{ request('model_type') === 'Tugas' ? 'selected' : '' }}>Tugas</option>
                <option value="Jurusan" {{ request('model_type') === 'Jurusan' ? 'selected' : '' }}>Jurusan</option>
                <option value="Mapel" {{ request('model_type') === 'Mapel' ? 'selected' : '' }}>Mapel</option>
            </select>
        </div>
        <div class="lg:col-span-4 flex items-end justify-end space-x-2">
            <a href="{{ route('admin.logs.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Reset</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Filter</button>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">IP Address</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden xl:table-cell">User Agent</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($logs ?? [] as $log)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-5 py-4">
                        <span class="text-sm text-gray-900 dark:text-white">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</span>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->user->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $actionColors = [
                                'created' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'updated' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'deleted' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                'login'   => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                'logout'  => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                            ];
                            $actionColor = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $actionColor }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $log->description }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono hidden md:table-cell">{{ $log->ip_address ?? '-' }}</td>
                    <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 max-w-[200px] truncate hidden xl:table-cell" title="{{ $log->user_agent ?? '' }}">{{ $log->user_agent ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Tidak ada log ditemukan</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Coba ubah filter untuk menampilkan log yang diinginkan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($logs) && $logs->hasPages())
    <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }} log</p>
        <div>{{ $logs->links('vendor.pagination.tailwind') }}</div>
    </div>
    @endif
</div>

@endsection
