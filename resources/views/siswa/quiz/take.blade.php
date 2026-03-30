@extends('layouts.siswa')

@section('title', $quiz->judul)

@section('page-content')
@csrf
{{-- Quiz Taking Interface --}}
<div class="space-y-4" id="quizApp">
    {{-- Top Bar --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-6 py-4 sticky top-0 z-30">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="text-lg font-bold text-gray-900 truncate max-w-md hidden sm:block">{{ $quiz->judul }}</h1>
                <h1 class="text-sm font-bold text-gray-900 sm:hidden">{{ Str::limit($quiz->judul, 30) }}</h1>
            </div>
            <div class="flex items-center gap-4">
                {{-- Fullscreen Toggle --}}
                <button onclick="toggleFullscreen()" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors" title="Layar Penuh">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                </button>
                {{-- Timer --}}
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg" id="timerContainer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span id="timerDisplay" class="text-xl font-bold font-mono text-gray-900">{{ $timeRemaining ?? $quiz->durasi * 60 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-6 py-3">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-500">Pertanyaan <span id="currentQ" class="font-bold text-gray-900">1</span> dari <span id="totalQ" class="font-bold text-gray-900">{{ $questions->count() }}</span></span>
            <span id="answeredCount" class="text-sm text-gray-500">0 dijawab</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div id="progressBar" class="h-2 rounded-full bg-blue-600 transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        {{-- Question Area --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sm:p-8">
                {{-- Question --}}
                <div id="questionArea">
                    @foreach($questions as $index => $question)
                        <div class="question-slide {{ $index === 0 ? '' : 'hidden' }}" data-question="{{ $index }}">
                            <div class="mb-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 mb-3">
                                    Soal {{ $index + 1 }}
                                    @if($question->tipe === 'essay')
                                        <span class="ml-1">• Essay</span>
                                    @elseif($question->tipe === 'pg')
                                        <span class="ml-1">• Pilihan Ganda</span>
                                    @elseif($question->tipe === 'pgk')
                                        <span class="ml-1">• PG Kompleks</span>
                                    @endif
                                </span>
                                <h2 class="text-lg font-semibold text-gray-900 leading-relaxed">{!! $question->pertanyaan !!}</h2>
                            </div>

                            {{-- Pilihan Ganda Options --}}
                            @if($question->tipe === 'pg')
                                <div class="space-y-3">
                                    @foreach(['A','B','C','D','E'] as $letter)
                                        @php
                                            $optionKey = 'opsi_' . strtolower($letter);
                                        @endphp
                                        @if($question->$optionKey)
                                            <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-blue-300 hover:bg-blue-50/50 transition-all option-label" data-letter="{{ $letter }}">
                                                <input type="radio" name="jawaban[{{ $question->id }}]" value="{{ $letter }}" class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500 flex-shrink-0 quiz-radio" data-qid="{{ $question->id }}">
                                                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 text-gray-600 text-sm font-bold flex-shrink-0">{{ $letter }}</span>
                                                <span class="text-sm text-gray-700 leading-relaxed pt-0.5">{!! $question->$optionKey !!}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            {{-- PG Kompleks --}}
                            @if($question->tipe === 'pgk')
                                <div class="space-y-3">
                                    <p class="text-xs text-gray-500 italic mb-2">Pilih semua jawaban yang benar (boleh lebih dari satu)</p>
                                    @foreach(['A','B','C','D','E'] as $letter)
                                        @php
                                            $optionKey = 'opsi_' . strtolower($letter);
                                        @endphp
                                        @if($question->$optionKey)
                                            <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-blue-300 hover:bg-blue-50/50 transition-all option-label" data-letter="{{ $letter }}">
                                                <input type="checkbox" name="jawaban[{{ $question->id }}][]" value="{{ $letter }}" class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500 rounded flex-shrink-0 quiz-checkbox" data-qid="{{ $question->id }}">
                                                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 text-gray-600 text-sm font-bold flex-shrink-0">{{ $letter }}</span>
                                                <span class="text-sm text-gray-700 leading-relaxed pt-0.5">{!! $question->$optionKey !!}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            {{-- Essay --}}
                            @if($question->tipe === 'essay')
                                <div>
                                    <textarea name="jawaban[{{ $question->id }}]" rows="6" placeholder="Tulis jawaban kamu di sini..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none resize-none quiz-essay" data-qid="{{ $question->id }}"></textarea>
                                    <p class="text-xs text-gray-400 mt-2">Jawaban essay akan dinilai manual oleh guru</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-100">
                    <button onclick="prevQuestion()" id="btnPrev" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Sebelumnya
                    </button>
                    <button onclick="confirmSubmit()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        {{ $questions->count() > 1 ? 'Selanjutnya' : 'Kumpulkan' }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Question Navigator --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sticky top-32">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Navigasi Soal</h3>
                <div class="grid grid-cols-5 gap-2" id="questionGrid">
                    @foreach($questions as $index => $question)
                        <button onclick="goToQuestion({{ $index }})" class="q-nav-btn w-full aspect-square rounded-lg text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors flex items-center justify-center" data-qindex="{{ $index }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 space-y-2 text-xs">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-blue-600"></div>
                        <span class="text-gray-600">Sedang dilihat</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-green-500"></div>
                        <span class="text-gray-600">Sudah dijawab</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-gray-200"></div>
                        <span class="text-gray-600">Belum dijawab</span>
                    </div>
                </div>
                {{-- Finish Button --}}
                <button onclick="confirmSubmit()" class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Selesai & Kumpulkan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Submit Modal --}}
<div id="submitModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeSubmitModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Kumpulkan Quiz?</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Pastikan semua jawaban sudah benar. Quiz yang sudah dikumpulkan tidak bisa diubah.
                </p>
                <div class="bg-gray-50 rounded-xl p-4 mt-4">
                    <p class="text-sm text-gray-700">
                        <span id="answeredInfo" class="font-bold text-gray-900">0</span> dari <span class="font-bold">{{ $questions->count() }}</span> soal sudah dijawab
                    </p>
                    @if($questions->count() > 0)
                        <p id="unansweredInfo" class="text-xs text-red-600 mt-1 hidden">Masih ada soal yang belum dijawab!</p>
                    @endif
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button onclick="closeSubmitModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium text-sm">
                    Kembali
                </button>
                <button onclick="submitQuiz()" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium text-sm">
                    Ya, Kumpulkan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Warning on Leave --}}
<div id="leaveModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
            <svg class="w-16 h-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <h3 class="text-xl font-bold text-gray-900 mt-4">Peringatan!</h3>
            <p class="text-sm text-gray-500 mt-2">Meninggalkan halaman ini akan menghentikan quiz dan mengirim jawaban secara otomatis.</p>
            <button onclick="document.getElementById('leaveModal').classList.add('hidden')" class="mt-6 px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
                Mengerti
            </button>
        </div>
    </div>
</div>
@endsection

@section('custom-styles')
<style>
    [x-cloak] { display: none !important; }
    .option-label:has(input:checked) {
        border-color: #2563EB;
        background-color: #EFF6FF;
    }
    .option-label:has(input:checked) span:first-of-type {
        background-color: #2563EB;
        color: white;
    }
</style>
@endsection

@push('scripts')
<script>
    const totalQuestions = {{ $questions->count() }};
    const durationSeconds = {{ $timeRemaining ?? ($quiz->durasi * 60) }};
    let currentQuestion = 0;
    let timeLeft = durationSeconds;
    let timerInterval;
    let answered = new Set();

    {{-- Initialize --}}
    document.addEventListener('DOMContentLoaded', () => {
        startTimer();
        updateNav();
    });

    {{-- Timer --}}
    function startTimer() {
        timerInterval = setInterval(() => {
            timeLeft--;
            updateTimerDisplay();
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                submitQuiz();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;
        const display = hours > 0
            ? `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`
            : `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
        
        const timerEl = document.getElementById('timerDisplay');
        timerEl.textContent = display;
        
        const container = document.getElementById('timerContainer');
        if (timeLeft <= 300) {
            timerEl.classList.remove('text-gray-900');
            timerEl.classList.add('text-red-600');
            container.classList.add('bg-red-50', 'rounded-lg');
        }
    }

    {{-- Question Navigation --}}
    function goToQuestion(index) {
        if (index < 0 || index >= totalQuestions) return;
        
        document.querySelectorAll('.question-slide').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.question-slide')[index].classList.remove('hidden');
        
        currentQuestion = index;
        updateNav();
    }

    function prevQuestion() {
        if (currentQuestion > 0) goToQuestion(currentQuestion - 1);
    }

    function nextQuestion() {
        if (currentQuestion < totalQuestions - 1) goToQuestion(currentQuestion + 1);
        else confirmSubmit();
    }

    function updateNav() {
        document.getElementById('currentQ').textContent = currentQuestion + 1;
        document.getElementById('btnPrev').disabled = currentQuestion === 0;
        
        const pct = ((currentQuestion + 1) / totalQuestions) * 100;
        document.getElementById('progressBar').style.width = pct + '%';
        
        document.querySelectorAll('.q-nav-btn').forEach((btn, i) => {
            btn.classList.remove('bg-blue-600', 'text-white', 'bg-green-500', 'text-white');
            if (i === currentQuestion) {
                btn.classList.add('bg-blue-600', 'text-white');
            } else if (answered.has(i)) {
                btn.classList.add('bg-green-500', 'text-white');
            } else {
                btn.classList.add('bg-gray-100', 'text-gray-600');
            }
        });

        document.getElementById('answeredCount').textContent = answered.size + ' dijawab';
    }

    {{-- Track Answered --}}
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('quiz-radio') || e.target.classList.contains('quiz-checkbox') || e.target.classList.contains('quiz-essay')) {
            const slide = e.target.closest('.question-slide');
            const qIndex = parseInt(slide.dataset.question);
            if (e.target.type === 'checkbox') {
                const checkboxes = slide.querySelectorAll('input[type="checkbox"]:checked');
                if (checkboxes.length > 0) answered.add(qIndex);
                else answered.delete(qIndex);
            } else if (e.target.type === 'text' || e.target.tagName === 'TEXTAREA') {
                if (e.target.value.trim()) answered.add(qIndex);
                else answered.delete(qIndex);
            } else {
                answered.add(qIndex);
            }
            updateNav();
        }
    });

    {{-- Also track essay input --}}
    document.addEventListener('input', (e) => {
        if (e.target.classList.contains('quiz-essay')) {
            const slide = e.target.closest('.question-slide');
            const qIndex = parseInt(slide.dataset.question);
            if (e.target.value.trim()) answered.add(qIndex);
            else answered.delete(qIndex);
            updateNav();
        }
    });

    {{-- Submit --}}
    function confirmSubmit() {
        document.getElementById('answeredInfo').textContent = answered.size;
        const unanswered = totalQuestions - answered.size;
        const unansweredInfo = document.getElementById('unansweredInfo');
        if (unanswered > 0) {
            unansweredInfo.textContent = `Masih ada ${unanswered} soal yang belum dijawab!`;
            unansweredInfo.classList.remove('hidden');
        } else {
            unansweredInfo.classList.add('hidden');
        }
        document.getElementById('submitModal').classList.remove('hidden');
    }

    function closeSubmitModal() {
        document.getElementById('submitModal').classList.add('hidden');
    }

    function submitQuiz() {
        clearInterval(timerInterval);
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('siswa.quiz.submit-answer', $quiz) }}';
        
        const csrf = document.querySelector('meta[name="csrf-token"]');
        if (csrf) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = csrf.content;
            form.appendChild(input);
        }

        const timeInput = document.createElement('input');
        timeInput.type = 'hidden';
        timeInput.name = 'time_spent';
        timeInput.value = durationSeconds - timeLeft;
        form.appendChild(timeInput);

        document.querySelectorAll('.question-slide').forEach(slide => {
            slide.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `jawaban[${input.name.match(/\[(\d+)\]/)[1]}]`;
                hidden.value = input.value;
                form.appendChild(hidden);
            });
            slide.querySelectorAll('input[type="checkbox"]:checked').forEach(input => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `jawaban[${input.name.match(/\[(\d+)\]/)[1]}][]`;
                hidden.value = input.value;
                form.appendChild(hidden);
            });
            slide.querySelectorAll('textarea.quiz-essay').forEach(textarea => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `jawaban[${textarea.name.match(/\[(\d+)\]/)[1]}]`;
                hidden.value = textarea.value;
                form.appendChild(hidden);
            });
        });

        document.body.appendChild(form);
        form.submit();
    }

    {{-- Fullscreen --}}
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(() => {});
        } else {
            document.exitFullscreen();
        }
    }

    {{-- Warn on leave --}}
    window.addEventListener('beforeunload', (e) => {
        e.preventDefault();
        e.returnValue = '';
    });
</script>
@endpush
