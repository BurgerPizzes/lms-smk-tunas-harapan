@extends('layouts.guru')
@section('title', 'Tambah Soal - ' . ($quiz->judul ?? ''))
@section('page-content')

<div class="mb-6">
    <a href="{{ route('guru.quiz.show', $quiz->id) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-3 transition-colors">
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke {{ $quiz->judul }}
    </a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        @isset($soal) Edit Soal @else Tambah Soal Baru @endisset
    </h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        {{ $quiz->judul }} &middot; {{ $soalList->count() ?? 0 }} soal sudah ditambahkan
    </p>
</div>

<div class="max-w-3xl">
    <form method="POST" action="{{ isset($soal) ? route('guru.quiz.update-question', [$quiz->id, $soal->id]) : route('guru.quiz.store-question', $quiz->id) }}" class="space-y-6">
        @csrf
        @isset($soal) @method('PUT') @endisset

        <!-- Question -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Soal</h3>
            <div class="space-y-4">
                <div>
                    <label for="pertanyaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea id="pertanyaan" name="pertanyaan" rows="4" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis pertanyaan...">{{ old('pertanyaan', $soal->pertanyaan ?? '') }}</textarea>
                    @error('pertanyaan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipe Soal <span class="text-red-500">*</span></label>
                        <select id="tipe" name="tipe" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" onchange="switchQuestionType(this.value)">
                            <option value="pilihan_ganda_4" {{ old('tipe', $soal->tipe ?? 'pilihan_ganda_4') === 'pilihan_ganda_4' ? 'selected' : '' }}>Pilihan Ganda (4 opsi)</option>
                            <option value="pilihan_ganda_5" {{ old('tipe', $soal->tipe ?? '') === 'pilihan_ganda_5' ? 'selected' : '' }}>Pilihan Ganda (5 opsi)</option>
                            <option value="benar_salah" {{ old('tipe', $soal->tipe ?? '') === 'benar_salah' ? 'selected' : '' }}>Benar / Salah</option>
                            <option value="essay" {{ old('tipe', $soal->tipe ?? '') === 'essay' ? 'selected' : '' }}>Essay</option>
                        </select>
                    </div>

                    <div>
                        <label for="poin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Poin <span class="text-red-500">*</span></label>
                        <input type="number" id="poin" name="poin" value="{{ old('poin', $soal->poin ?? 1) }}" min="1" max="100" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Options (Pilihan Ganda) -->
        <div id="section-pg" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 {{ in_array(old('tipe', $soal->tipe ?? 'pilihan_ganda_4'), ['pilihan_ganda_4', 'pilihan_ganda_5']) ? '' : 'hidden' }}">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Pilihan Jawaban</h3>
            <div class="space-y-3">
                <!-- Option A -->
                <div>
                    <div class="flex items-center space-x-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="jawaban_benar" value="A" {{ old('jawaban_benar', $soal->jawaban_benar ?? '') === 'A' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                            <span class="ml-2 w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">A</span>
                        </label>
                        <input type="text" name="pilihan_a" value="{{ old('pilihan_a', $soal->pilihan_a ?? '') }}" class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Pilihan A" required>
                    </div>
                </div>
                <!-- Option B -->
                <div>
                    <div class="flex items-center space-x-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="jawaban_benar" value="B" {{ old('jawaban_benar', $soal->jawaban_benar ?? '') === 'B' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">B</span>
                        </label>
                        <input type="text" name="pilihan_b" value="{{ old('pilihan_b', $soal->pilihan_b ?? '') }}" class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Pilihan B" required>
                    </div>
                </div>
                <!-- Option C -->
                <div>
                    <div class="flex items-center space-x-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="jawaban_benar" value="C" {{ old('jawaban_benar', $soal->jawaban_benar ?? '') === 'C' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">C</span>
                        </label>
                        <input type="text" name="pilihan_c" value="{{ old('pilihan_c', $soal->pilihan_c ?? '') }}" class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Pilihan C" required>
                    </div>
                </div>
                <!-- Option D -->
                <div>
                    <div class="flex items-center space-x-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="jawaban_benar" value="D" {{ old('jawaban_benar', $soal->jawaban_benar ?? '') === 'D' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">D</span>
                        </label>
                        <input type="text" name="pilihan_d" value="{{ old('pilihan_d', $soal->pilihan_d ?? '') }}" class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Pilihan D" required>
                    </div>
                </div>
                <!-- Option E (only for 5 options) -->
                <div id="option-e" class="{{ in_array(old('tipe', $soal->tipe ?? ''), ['pilihan_ganda_5']) ? '' : 'hidden' }}">
                    <div class="flex items-center space-x-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="jawaban_benar" value="E" {{ old('jawaban_benar', $soal->jawaban_benar ?? '') === 'E' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">E</span>
                        </label>
                        <input type="text" name="pilihan_e" value="{{ old('pilihan_e', $soal->pilihan_e ?? '') }}" class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Pilihan E" {{ in_array(old('tipe', $soal->tipe ?? ''), ['pilihan_ganda_5']) ? 'required' : '' }}>
                    </div>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                <svg class="w-3.5 h-3.5 mr-1 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                Pilih radio button di samping jawaban yang benar
            </p>
        </div>

        <!-- Benar Salah -->
        <div id="section-bs" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 {{ old('tipe', $soal->tipe ?? '') === 'benar_salah' ? '' : 'hidden' }}">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Jawaban</h3>
            <div class="flex items-center space-x-4">
                <label class="inline-flex items-center px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-600 cursor-pointer has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20 transition-all">
                    <input type="radio" name="jawaban_benar" value="benar" {{ old('jawaban_benar', $soal->jawaban_benar ?? '') === 'benar' ? 'checked' : '' }} class="sr-only" required>
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Benar</span>
                </label>
                <label class="inline-flex items-center px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-600 cursor-pointer has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/20 transition-all">
                    <input type="radio" name="jawaban_benar" value="salah" {{ old('jawaban_benar', $soal->jawaban_benar ?? '') === 'salah' ? 'checked' : '' }} class="sr-only">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Salah</span>
                </label>
            </div>
        </div>

        <!-- Essay Answer -->
        <div id="section-essay" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 {{ old('tipe', $soal->tipe ?? '') === 'essay' ? '' : 'hidden' }}">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">Jawaban Essay</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Soal essay akan dinilai manual oleh guru. Tidak perlu mengisi jawaban benar di bawah.</p>
            <div>
                <label for="jawaban_essay" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kunci Jawaban (Opsional)</label>
                <textarea id="jawaban_essay" name="jawaban_benar" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis kunci jawaban sebagai panduan penilaian...">{{ old('jawaban_benar', $soal->jawaban_benar ?? '') }}</textarea>
            </div>
        </div>

        <!-- Pembahasan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Pembahasan</h3>
            <div>
                <label for="pembahasan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pembahasan Jawaban (Opsional)</label>
                <textarea id="pembahasan" name="pembahasan" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Jelaskan pembahasan soal ini...">{{ old('pembahasan', $soal->pembahasan ?? '') }}</textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('guru.quiz.show', $quiz->id) }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                Batal
            </a>
            <div class="flex items-center space-x-3">
                <button type="submit" name="action" value="save-add" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Simpan & Tambah Lagi
                </button>
                <button type="submit" name="action" value="save-done" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    Simpan Selesai
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function switchQuestionType(tipe) {
    document.getElementById('section-pg').classList.toggle('hidden', !['pilihan_ganda_4', 'pilihan_ganda_5'].includes(tipe));
    document.getElementById('section-bs').classList.toggle('hidden', tipe !== 'benar_salah');
    document.getElementById('section-essay').classList.toggle('hidden', tipe !== 'essay');
    document.getElementById('option-e').classList.toggle('hidden', tipe !== 'pilihan_ganda_5');

    // Toggle required on radio buttons
    const pgRadios = document.querySelectorAll('#section-pg input[name="jawaban_benar"]');
    const bsRadios = document.querySelectorAll('#section-bs input[name="jawaban_benar"]');
    const essayTextarea = document.querySelector('#section-essay textarea[name="jawaban_benar"]');

    pgRadios.forEach(r => r.required = ['pilihan_ganda_4', 'pilihan_ganda_5'].includes(tipe));
    bsRadios.forEach(r => r.required = tipe === 'benar_salah');
    if (essayTextarea) essayTextarea.required = false;
}

// Init on page load
switchQuestionType(document.getElementById('tipe')?.value || 'pilihan_ganda_4');
</script>

@endsection
