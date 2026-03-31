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
                    <span id="timerDisplay" class="text-xl font-bold font-mono text-gray-900">{{ $timeRemaining ?? ($quiz->durasi_menit * 60) }}</span>
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
                                    @elseif($question->tipe === 'pilgan')
                                        <span class="ml-1">• Pilihan Ganda</span>
                                    @elseif($question->tipe === 'benar_salah')
                                        <span class="ml-1">• Benar/Salah</span>
                                    @endif
                                </span>
                                <h2 class="text-lg font-semibold text-gray-900 leading-relaxed">{!! $question->pertanyaan !!}</h2>
                            </div>

                            {{-- Pilihan Ganda Options --}}
                            @if($question->tipe === 'pilgan')
                                <div class="space-y-3">
                                    @foreach(['A','B','C','D','E'] as $letter)
                                        @php
                                            $optionKey = 'pilihan_' . strtolower($letter);
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

                            {{-- Benar/Salah --}}
                            @if($question->tipe === 'benar_salah')
                                <div class="space-y-3">
                                    @foreach(['Benar','Salah'] as $optionLabel)
                                        @php
                                            $optionValue = strtolower($optionLabel);
                                        @endphp
                                        <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-blue-300 hover:bg-blue-50/50 transition-all option-label" data-letter="{{ $optionLabel }}">
                                            <input type="radio" name="jawaban[{{ $question->id }}]" value="{{ $optionValue }}" class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500 flex-shrink-0 quiz-radio" data-qid="{{ $question->id }}">
                                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 text-gray-600 text-sm font-bold flex-shrink-0">{{ substr($optionLabel, 0, 1) }}</span>
                                            <span class="text-sm text-gray-700 leading-relaxed pt-0.5">{{ $optionLabel }}</span>
                                        </label>
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
                    <button onclick="nextQuestion()" id="btnNext" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold shadow-sm">
                        Selanjutnya
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
                <form method="POST" action="{{ route('siswa.quiz.finish', $attempt) }}" id="finishForm">
                    @csrf
                    <button type="submit" onclick="return confirm('Yakin ingin mengumpulkan quiz?')" class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Selesai & Kumpulkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .option-label:has(input:checked) {
        border-color: #2563EB;
        background-color: #EFF6FF;
    }
</style>
@endpush

@push('scripts')
<script>
    const totalQuestions = {{ $questions->count() }};
    const durationSeconds = {{ $timeRemaining ?? ($quiz->durasi_menit * 60) }};
    let currentQuestion = 0;
    let timeLeft = durationSeconds;
    let timerInterval;
    let answered = new Set();

    document.addEventListener('DOMContentLoaded', () => {
        startTimer();
        updateNav();
    });

    function startTimer() {
        timerInterval = setInterval(() => {
            timeLeft--;
            updateTimerDisplay();
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                // Auto-submit
                document.getElementById('finishForm').submit();
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

    function goToQuestion(index) {
        if (index < 0 || index >= totalQuestions) return;
        
        // Save current answer before navigating
        saveCurrentAnswer();

        document.querySelectorAll('.question-slide').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.question-slide')[index].classList.remove('hidden');
        
        currentQuestion = index;
        updateNav();
    }

    function prevQuestion() {
        saveCurrentAnswer();
        if (currentQuestion > 0) goToQuestion(currentQuestion - 1);
    }

    function nextQuestion() {
        saveCurrentAnswer();
        if (currentQuestion < totalQuestions - 1) goToQuestion(currentQuestion + 1);
    }

    function saveCurrentAnswer() {
        const currentSlide = document.querySelectorAll('.question-slide')[currentQuestion];
        if (!currentSlide) return;

        const questionId = currentSlide.querySelector('.quiz-radio, .quiz-checkbox, .quiz-essay')?.dataset.qid;
        if (!questionId) return;

        const radio = currentSlide.querySelector('input[type="radio"]:checked');
        const essay = currentSlide.querySelector('textarea.quiz-essay');

        let jawaban = null;
        if (radio) {
            jawaban = radio.value;
        } else if (essay && essay.value.trim()) {
            jawaban = essay.value.trim();
        }

        if (jawaban !== null) {
            fetch('{{ route('siswa.quiz.submit-answer') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    attempt_id: {{ $attempt->id }},
                    question_id: questionId,
                    jawaban: jawaban,
                }),
            }).catch(() => {});
        }
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

    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('quiz-radio') || e.target.classList.contains('quiz-checkbox')) {
            const slide = e.target.closest('.question-slide');
            const qIndex = parseInt(slide.dataset.question);
            answered.add(qIndex);
            updateNav();
        }
    });

    document.addEventListener('input', (e) => {
        if (e.target.classList.contains('quiz-essay')) {
            const slide = e.target.closest('.question-slide');
            const qIndex = parseInt(slide.dataset.question);
            if (e.target.value.trim()) answered.add(qIndex);
            else answered.delete(qIndex);
            updateNav();
        }
    });

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(() => {});
        } else {
            document.exitFullscreen();
        }
    }

    window.addEventListener('beforeunload', (e) => {
        e.preventDefault();
        e.returnValue = '';
    });
</script>
@endpush
@endsection
