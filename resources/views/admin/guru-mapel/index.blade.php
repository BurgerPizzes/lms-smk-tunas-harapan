@extends('layouts.admin')
@section('title', 'Guru & Mata Pelajaran')
@section('page-content')

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Guru & Mata Pelajaran</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Alokasi guru ke mata pelajaran dan kelas</p>
    </div>
    <button type="button" onclick="document.getElementById('addModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Tambah Alokasi
    </button>
</div>

<!-- Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Guru</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Kelas</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Tahun Ajaran</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Primary</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($assignments ?? [] as $index => $assignment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ($assignments->currentPage() - 1) * $assignments->perPage() + $index + 1 }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
                                <span class="text-xs font-bold text-green-600 dark:text-green-400">{{ strtoupper(substr($assignment->guru->name ?? '', 0, 1)) }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $assignment->guru->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $assignment->mapel->nama ?? '-' }}</td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 hidden md:table-cell">{{ $assignment->kelas->nama ?? '-' }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">{{ $assignment->tahunAjaran->nama ?? '-' }}</td>
                    <td class="px-5 py-4 text-center">
                        @if($assignment->is_primary)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Primary</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">Secondary</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        <form method="POST" action="{{ route('admin.guru-mapel.destroy', $assignment->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus alokasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                            <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum ada alokasi</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Mulai dengan mengalokasikan guru ke mata pelajaran.</p>
                            <button type="button" onclick="document.getElementById('addModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Tambah Alokasi
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($assignments) && $assignments->hasPages())
    <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">Menampilkan {{ $assignments->firstItem() }}-{{ $assignments->lastItem() }} dari {{ $assignments->total() }} data</p>
        {{ $assignments->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
        <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 transition-opacity" onclick="document.getElementById('addModal').classList.add('hidden')"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg w-full mx-auto z-10">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Alokasi Guru</h3>
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.guru-mapel.store') }}">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label for="guru_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Guru <span class="text-red-500">*</span></label>
                        <select id="guru_id" name="guru_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                            <option value="">Pilih Guru</option>
                            @foreach($guruList ?? [] as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->name }} - {{ $guru->nip ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('guru_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="mapel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select id="mapel_id" name="mapel_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                            <option value="">Pilih Mapel</option>
                            @foreach($mapelList ?? [] as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->nama }} ({{ $mapel->kode }})</option>
                            @endforeach
                        </select>
                        @error('mapel_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                        <select id="kelas_id" name="kelas_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasList ?? [] as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama }} - {{ $kelas->jurusan->nama ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <select id="tahun_ajaran_id" name="tahun_ajaran_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach($tahunAjaranList ?? [] as $ta)
                                <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_primary" value="1" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Guru Primary</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
