@extends('layouts.admin')
@section('title', 'Detail Jurusan')
@section('page-content')

<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
        <a href="{{ route('admin.jurusan.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Jurusan</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <span class="text-gray-900 dark:text-white font-medium">{{ $jurusan->nama }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Jurusan</h1>
</div>

<!-- Info Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Kode Jurusan</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white mt-1 font-mono">{{ $jurusan->kode }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Kelas</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $jurusan->kelas_count ?? $jurusan->kelas->count() }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
        <p class="text-xl font-bold mt-1 @if($jurusan->aktif) text-green-600 @else text-red-600 @endif">{{ $jurusan->aktif ? 'Aktif' : 'Nonaktif' }}</p>
    </div>
</div>

<!-- Detail Info -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Informasi Jurusan</h3>
    <div class="space-y-3">
        <div class="flex items-start space-x-3">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-32 flex-shrink-0">Nama</span>
            <span class="text-sm text-gray-900 dark:text-white">{{ $jurusan->nama }}</span>
        </div>
        <div class="flex items-start space-x-3">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-32 flex-shrink-0">Kode</span>
            <span class="text-sm text-gray-900 dark:text-white font-mono">{{ $jurusan->kode }}</span>
        </div>
        <div class="flex items-start space-x-3">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-32 flex-shrink-0">Deskripsi</span>
            <span class="text-sm text-gray-900 dark:text-white">{{ $jurusan->deskripsi ?? '-' }}</span>
        </div>
        <div class="flex items-start space-x-3">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-32 flex-shrink-0">Status</span>
            @if($jurusan->aktif)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Nonaktif</span>
            @endif
        </div>
    </div>
</div>

<!-- Kelas List -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Daftar Kelas</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Kelas</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tingkat</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jml Siswa</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($jurusan->kelas ?? [] as $index => $kelas)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-white">
                        <a href="{{ route('admin.kelas.show', $kelas->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $kelas->nama }}</a>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $kelas->tingkat }}</td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 text-center">{{ $kelas->siswas_count ?? $kelas->siswas->count() }}</td>
                    <td class="px-5 py-4 text-center">
                        @if($kelas->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada kelas di jurusan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
