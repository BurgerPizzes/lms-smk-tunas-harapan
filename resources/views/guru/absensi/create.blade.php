@extends('layouts.guru')
@section('title', 'Input Absensi')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <a href="{{ isset($kelas_id) ? route('guru.kelas.show', $kelas_id) : route('guru.kelas.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-3 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Input Absensi</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Catat kehadiran siswa untuk sesi pembelajaran</p>
</div>

<div class="max-w-4xl">
    <form method="POST" action="{{ route('guru.kelas.absensi.store') }}" class="space-y-6">
        @csrf

        <!-- Sesi Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Informasi Sesi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <select id="kelas_id" name="kelas_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList ?? [] as $k)
                            <option value="{{ $k->id }}" {{ (old('kelas_id') == $k->id || isset($kelas_id) && $kelas_id == $k->id) ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                    @error('kelas_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select id="mapel_id" name="mapel_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Mapel</option>
                        @foreach($mapels ?? [] as $mapel)
                            <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                    @error('mapel_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('tanggal') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="pertemuan_ke" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pertemuan Ke <span class="text-red-500">*</span></label>
                    <input type="number" id="pertemuan_ke" name="pertemuan_ke" value="{{ old('pertemuan_ke') }}" min="1" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="1">
                    @error('pertemuan_ke') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Catatan</label>
                <textarea id="catatan" name="catatan" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
            </div>
        </div>

        <!-- Student List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Daftar Siswa</h3>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="setAllStatus('hadir')" class="px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">Semua Hadir</button>
                    <span class="text-xs text-gray-400">|</span>
                    <span id="status-summary" class="text-xs text-gray-500 dark:text-gray-400">H: 0 I: 0 S: 0 A: 0</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-10">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nama Siswa</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($siswaList ?? [] as $index => $siswa)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($siswa->name ?? 'S', 0, 1)) }}</div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $siswa->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center space-x-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="absensi[{{ $siswa->id }}]" value="hadir" {{ old("absensi.$siswa->id", 'hadir') === 'hadir' ? 'checked' : '' }} class="sr-only peer" onchange="updateSummary()">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 text-xs font-medium text-gray-500 dark:text-gray-400 peer-checked:text-green-600 dark:peer-checked:text-green-400 transition-all">H</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="absensi[{{ $siswa->id }}]" value="izin" class="sr-only peer" onchange="updateSummary()">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 text-xs font-medium text-gray-500 dark:text-gray-400 peer-checked:text-blue-600 dark:peer-checked:text-blue-400 transition-all">I</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="absensi[{{ $siswa->id }}]" value="sakit" class="sr-only peer" onchange="updateSummary()">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 text-xs font-medium text-gray-500 dark:text-gray-400 peer-checked:text-yellow-600 dark:peer-checked:text-yellow-400 transition-all">S</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="absensi[{{ $siswa->id }}]" value="alpha" class="sr-only peer" onchange="updateSummary()">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 text-xs font-medium text-gray-500 dark:text-gray-400 peer-checked:text-red-600 dark:peer-checked:text-red-400 transition-all">A</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <input type="text" name="keterangan[{{ $siswa->id }}]" value="{{ old("keterangan.$siswa->id") }}" class="w-full px-2 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Keterangan...">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Pilih kelas terlebih dahulu untuk melihat daftar siswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ isset($kelas_id) ? route('guru.kelas.show', $kelas_id) : route('guru.kelas.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Batal</a>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Simpan Absensi
            </button>
        </div>
    </form>
</div>

<script>
function setAllStatus(status) {
    document.querySelectorAll('input[type="radio"][value="' + status + '"]').forEach(r => r.checked = true);
    updateSummary();
}
function updateSummary() {
    let h = 0, i = 0, s = 0, a = 0;
    document.querySelectorAll('tbody tr').forEach(row => {
        const radios = row.querySelectorAll('input[type="radio"]');
        if (radios.length === 0) return;
        const checked = [...radios].find(r => r.checked);
        if (checked) {
            if (checked.value === 'hadir') h++;
            else if (checked.value === 'izin') i++;
            else if (checked.value === 'sakit') s++;
            else if (checked.value === 'alpha') a++;
        }
    });
    const el = document.getElementById('status-summary');
    if (el) el.textContent = `H: ${h} I: ${i} S: ${s} A: ${a}`;
}
updateSummary();
</script>

@endsection
