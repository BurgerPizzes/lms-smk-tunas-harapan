@extends('layouts.admin')
@section('title', 'Edit Kelas')
@section('page-content')

<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
        <a href="{{ route('admin.kelas.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Kelas</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <a href="{{ route('admin.kelas.show', $kelas->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $kelas->nama }}</a>
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        <span class="text-gray-900 dark:text-white font-medium">Edit</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Kelas: {{ $kelas->nama }}</h1>
</div>

<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('admin.kelas.update', $kelas->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Kelas <span class="text-red-500">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $kelas->nama) }}" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                        @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="jurusan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jurusan <span class="text-red-500">*</span></label>
                        <select id="jurusan_id" name="jurusan_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusans ?? [] as $jurusan)
                                <option value="{{ $jurusan->id }}" {{ old('jurusan_id', $kelas->jurusan_id) == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama }}</option>
                            @endforeach
                        </select>
                        @error('jurusan_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <select id="tahun_ajaran_id" name="tahun_ajaran_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach($tahunAjaran ?? [] as $ta)
                                <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $kelas->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>{{ $ta->nama }}</option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tingkat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tingkat <span class="text-red-500">*</span></label>
                        <select id="tingkat" name="tingkat" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                            <option value="10" {{ old('tingkat', $kelas->tingkat) === '10' ? 'selected' : '' }}>Kelas 10</option>
                            <option value="11" {{ old('tingkat', $kelas->tingkat) === '11' ? 'selected' : '' }}>Kelas 11</option>
                            <option value="12" {{ old('tingkat', $kelas->tingkat) === '12' ? 'selected' : '' }}>Kelas 12</option>
                        </select>
                        @error('tingkat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Wali Kelas</label>
                        <select id="wali_kelas_id" name="wali_kelas_id" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                            <option value="">Pilih Guru</option>
                            @foreach($guruList ?? [] as $guru)
                                <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="ruangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Ruangan</label>
                        <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan', $kelas->ruangan) }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    </div>
                    <div>
                        <label for="kapasitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kapasitas</label>
                        <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $kelas->kapasitas) }}" min="1" max="100" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kode Unik</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="kode_unik" name="kode_unik" value="{{ old('kode_unik', $kelas->kode_unik) }}" readonly class="flex-1 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-mono uppercase cursor-not-allowed">
                        <button type="button" onclick="generateCode()" class="px-4 py-2.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-colors">Regenerate</button>
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors resize-none">{{ old('deskripsi', $kelas->deskripsi) }}</textarea>
                </div>

                <div>
                    <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Cover Image</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 dark:hover:file:bg-indigo-900/50">
                    @if($kelas->cover_image)
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">File saat ini: {{ basename($kelas->cover_image) }}</p>
                    @endif
                </div>

                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $kelas->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-gray-500 peer-checked:bg-indigo-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">Kelas Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.kelas.show', $kelas->id) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function generateCode() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let code = '';
    for (let i = 0; i < 6; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
    document.getElementById('kode_unik').value = code;
}
</script>
@endpush

@endsection
