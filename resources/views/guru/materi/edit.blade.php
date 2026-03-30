@extends('layouts.guru')
@section('title', 'Edit Materi - ' . ($materi->judul ?? ''))
@section('page-content')

<div class="mb-6">
    <a href="{{ route('guru.materi.show', $materi->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-3 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Detail Materi
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Materi</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui informasi dan konten materi</p>
</div>

<div class="max-w-3xl">
    <form method="POST" action="{{ route('guru.materi.update', $materi->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <!-- Basic Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Informasi Materi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Judul Materi <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $materi->judul) }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('judul') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select id="mapel_id" name="mapel_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Mapel</option>
                        @foreach($mapels ?? [] as $mapel)
                            <option value="{{ $mapel->id }}" {{ (old('mapel_id', $materi->mapel_id) == $mapel->id) ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <select id="kelas_id" name="kelas_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList ?? [] as $k)
                            <option value="{{ $k->id }}" {{ (old('kelas_id', $materi->kelas_id) == $k->id) ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pertemuan_ke" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pertemuan Ke</label>
                    <input type="number" id="pertemuan_ke" name="pertemuan_ke" value="{{ old('pertemuan_ke', $materi->pertemuan_ke) }}" min="1" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Tipe Konten -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Tipe Konten</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                <label class="cursor-pointer">
                    <input type="radio" name="tipe" value="file" class="peer sr-only" {{ old('tipe', $materi->tipe) === 'file' ? 'checked' : '' }} onchange="switchTipe(this.value)">
                    <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 text-center transition-all">
                        <svg class="w-6 h-6 mx-auto text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-2">File</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="tipe" value="video" class="peer sr-only" {{ old('tipe', $materi->tipe) === 'video' ? 'checked' : '' }} onchange="switchTipe(this.value)">
                    <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 text-center transition-all">
                        <svg class="w-6 h-6 mx-auto text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-2">Video</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="tipe" value="text" class="peer sr-only" {{ old('tipe', $materi->tipe) === 'text' ? 'checked' : '' }} onchange="switchTipe(this.value)">
                    <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 text-center transition-all">
                        <svg class="w-6 h-6 mx-auto text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-2">Teks</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="tipe" value="link" class="peer sr-only" {{ old('tipe', $materi->tipe) === 'link' ? 'checked' : '' }} onchange="switchTipe(this.value)">
                    <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 text-center transition-all">
                        <svg class="w-6 h-6 mx-auto text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-2">Link</p>
                    </div>
                </label>
            </div>

            <div id="tipe-file" class="tipe-content {{ old('tipe', $materi->tipe) !== 'file' ? 'hidden' : '' }}">
                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Upload File</label>
                @if($materi->file)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">File saat ini: {{ pathinfo($materi->file, PATHINFO_BASENAME) }}</p>
                @endif
                <input type="file" name="file" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 file:cursor-pointer">
            </div>

            <div id="tipe-video" class="tipe-content {{ old('tipe', $materi->tipe) !== 'video' ? 'hidden' : '' }}">
                <label for="video_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">URL Video (YouTube)</label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $materi->video_url) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://www.youtube.com/watch?v=...">
                @php $ytId = preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^&\n?#]+)/', $materi->video_url ?? '', $m) ? $m[1] : null; @endphp
                @if($ytId)
                <div class="mt-3">
                    <iframe src="https://www.youtube.com/embed/{{ $ytId }}" class="w-full rounded-lg" height="200" frameborder="0" allowfullscreen></iframe>
                </div>
                @endif
            </div>

            <div id="tipe-text" class="tipe-content {{ old('tipe', $materi->tipe) !== 'text' ? 'hidden' : '' }}">
                <label for="konten" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Konten Materi</label>
                <textarea id="konten" name="konten" rows="12" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('konten', $materi->konten) }}</textarea>
            </div>

            <div id="tipe-link" class="tipe-content {{ old('tipe', $materi->tipe) !== 'link' ? 'hidden' : '' }}">
                <label for="link_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">URL Link</label>
                <input type="url" id="link_url" name="link_url" value="{{ old('link_url', $materi->link_url) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <!-- Publish Options -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Terbitkan</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Matikan untuk menyimpan sebagai draft</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $materi->is_published) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('guru.materi.show', $materi->id) }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Batal</a>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
function switchTipe(tipe) {
    document.querySelectorAll('.tipe-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('tipe-' + tipe)?.classList.remove('hidden');
}
</script>

@endsection
