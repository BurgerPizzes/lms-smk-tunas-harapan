@extends('layouts.admin')
@section('title', 'Kelola Siswa - ' . ($kelas->nama ?? ''))
@section('page-content')

<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
        <a href="{{ route('admin.kelas.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Kelas</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <a href="{{ route('admin.kelas.show', $kelas->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $kelas->nama }}</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <span class="text-gray-900 dark:text-white font-medium">Kelola Siswa</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Siswa - {{ $kelas->nama }}</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $kelas->jurusan->nama ?? '-' }} &middot; Kode Unik: <span class="font-mono font-semibold">{{ $kelas->kode_unik }}</span></p>
</div>

<!-- Enrolled Siswa -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Siswa Terdaftar ({{ $enrolledSiswa->count() }})</h3>
        </div>
    </div>
    <div class="max-h-96 overflow-y-auto">
        <table class="w-full">
            <thead class="sticky top-0">
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIS</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($enrolledSiswa as $index => $siswa)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($siswa->name ?? 'S', 0, 1)) }}</div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $siswa->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400 font-mono">{{ $siswa->nis ?? '-' }}</td>
                    <td class="px-5 py-3 text-center">
                        <form method="POST" action="{{ route('admin.kelas.remove-member', ['kelas' => $kelas->id, 'userId' => $siswa->id]) }}" onsubmit="return confirm('Yakin ingin mengeluarkan siswa ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Keluarkan">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada siswa terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Siswa Form -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Tambah Siswa ke Kelas</h3>
    <form method="POST" action="{{ route('admin.kelas.enroll', $kelas->id) }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pilih Siswa</label>
            <select name="user_ids[]" multiple required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-h-[120px]">
                @foreach($availableSiswa as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->name }} ({{ $siswa->nis ?? $siswa->email }})</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tahan Ctrl/Cmd untuk memilih beberapa siswa</p>
            @error('user_ids') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        @if($availableSiswa->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Tidak ada siswa tersedia untuk ditambahkan.</p>
        @else
        <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Siswa
        </button>
        @endif
    </form>
</div>

@endsection
