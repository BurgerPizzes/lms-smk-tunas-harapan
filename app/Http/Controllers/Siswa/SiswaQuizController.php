<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaQuizController extends Controller
{
    /**
     * List available quizzes for a class.
     */
    public function index(Request $request, Kelas $kelas): \Illuminate\View\View
    {
        $this->verifyEnrollment($kelas);

        $siswa = Auth::user();

        $query = Quiz::where('kelas_id', $kelas->id)
            ->where('is_published', true)
            ->with('mapel');

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        $quizzes = $query->orderBy('waktu_mulai', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Annotate with attempt status
        $quizzes->transform(function ($quiz) use ($siswa) {
            $attempt = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', $siswa->id)
                ->latest()
                ->first();

            $quiz->attempt = $attempt;
            $quiz->has_attempt = $attempt !== null;
            $quiz->is_active = $quiz->waktu_mulai <= now() && $quiz->waktu_selesai >= now();
            $quiz->is_upcoming = $quiz->waktu_mulai > now();
            $quiz->is_ended = $quiz->waktu_selesai < now();

            return $quiz;
        });

        $mapels = \App\Models\Mapel::whereHas('quiz', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id)->where('is_published', true);
        })->orderBy('nama')->get();

        return view('siswa.quiz.index', compact('kelas', 'quizzes', 'mapels'));
    }

    /**
     * Start a quiz attempt.
     */
    public function start(Quiz $quiz): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $this->verifyEnrollment($quiz->kelas);
        $siswa = Auth::user();

        // Check if quiz is active
        if ($quiz->waktu_mulai > now()) {
            return back()->withErrors('Quiz belum dimulai.');
        }

        if ($quiz->waktu_selesai < now()) {
            return back()->withErrors('Quiz telah berakhir.');
        }

        // Check if there's already a pending attempt
        $pendingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $siswa->id)
            ->where('status', 'in_progress')
            ->first();

        if ($pendingAttempt) {
            // Resume existing attempt
            return $this->showQuizPage($quiz, $pendingAttempt);
        }

        // Check max attempts
        $totalAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $siswa->id)
            ->count();

        // Create new attempt
        DB::beginTransaction();
        try {
            $attempt = QuizAttempt::create([
                'quiz_id'   => $quiz->id,
                'user_id'   => $siswa->id,
                'status'    => 'in_progress',
                'started_at' => now(),
                'waktu_selesai' => now()->addMinutes($quiz->durasi_menit),
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal memulai quiz: ' . $e->getMessage());
        }

        return $this->showQuizPage($quiz, $attempt);
    }

    /**
     * Save an answer (AJAX endpoint).
     */
    public function submitAnswer(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'attempt_id'  => ['required', 'exists:quiz_attempts,id'],
            'question_id' => ['required', 'exists:quiz_questions,id'],
            'jawaban'     => ['required', 'string'],
        ]);

        $siswa = Auth::user();
        $attempt = QuizAttempt::where('id', $validated['attempt_id'])
            ->where('user_id', $siswa->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        // Check time limit
        if ($attempt->waktu_selesai && $attempt->waktu_selesai < now()) {
            return response()->json(['error' => 'Waktu quiz telah berakhir.'], 403);
        }

        // Upsert answer
        QuizAnswer::updateOrCreate(
            [
                'attempt_id'  => $attempt->id,
                'question_id' => $validated['question_id'],
            ],
            [
                'jawaban' => $validated['jawaban'],
            ]
        );

        // Update time remaining
        $timeRemaining = $attempt->waktu_selesai
            ? max(0, now()->diffInSeconds($attempt->waktu_selesai))
            : $attempt->quiz->durasi_menit * 60;

        return response()->json([
            'message'        => 'Jawaban berhasil disimpan.',
            'time_remaining' => $timeRemaining,
        ]);
    }

    /**
     * Submit/finish a quiz attempt.
     */
    public function finish(Request $request, QuizAttempt $attempt): \Illuminate\Http\RedirectResponse
    {
        $siswa = Auth::user();

        if ($attempt->user_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke attempt ini.');
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('siswa.quiz.result', $attempt)
                ->with('info', 'Quiz sudah selesai.');
        }

        DB::beginTransaction();
        try {
            // Auto-submit if time is up
            $attempt->update([
                'status'     => 'completed',
                'finished_at' => now(),
            ]);

            // Calculate score (only for non-essay questions)
            $this->calculateScore($attempt);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menyelesaikan quiz: ' . $e->getMessage());
        }

        return redirect()->route('siswa.quiz.result', $attempt)
            ->with('success', 'Quiz berhasil diselesaikan.');
    }

    /**
     * View quiz result with pembahasan.
     */
    public function result(QuizAttempt $attempt): \Illuminate\View\View
    {
        $siswa = Auth::user();

        if ($attempt->user_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke hasil ini.');
        }

        $attempt->load([
            'quiz.kelas',
            'quiz.mapel',
            'answers.question',
        ]);

        // Only show pembahasan if enabled
        $showPembahasan = $attempt->quiz->tampilkan_nilai;

        return view('siswa.quiz.result', compact('attempt', 'showPembahasan'));
    }

    /**
     * Show the quiz page with questions.
     */
    private function showQuizPage(Quiz $quiz, QuizAttempt $attempt): \Illuminate\View\View
    {
        $questions = $quiz->questions()->orderBy('nomor')->get();

        // Shuffle questions if configured
        if ($quiz->acak_soal) {
            $questions = $questions->shuffle();
        }

        // Shuffle options if configured
        if ($quiz->acak_pilihan) {
            $questions = $questions->map(function ($q) {
                if (in_array($q->tipe, ['pg_4', 'pg_5'])) {
                    $options = collect([
                        ['key' => 'A', 'text' => $q->pilihan_a],
                        ['key' => 'B', 'text' => $q->pilihan_b],
                        ['key' => 'C', 'text' => $q->pilihan_c],
                        ['key' => 'D', 'text' => $q->pilihan_d],
                    ]);

                    if ($q->tipe === 'pg_5') {
                        $options->push(['key' => 'E', 'text' => $q->pilihan_e]);
                    }

                    $shuffled = $options->shuffle();
                    $q->shuffled_options = $shuffled;
                }
                return $q;
            });
        }

        // Load existing answers
        $existingAnswers = QuizAnswer::where('attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');

        $timeRemaining = $attempt->waktu_selesai
            ? max(0, now()->diffInSeconds($attempt->waktu_selesai))
            : $quiz->durasi_menit * 60;

        return view('siswa.quiz.take', compact(
            'quiz', 'attempt', 'questions', 'existingAnswers', 'timeRemaining'
        ));
    }

    /**
     * Calculate the score for a completed attempt.
     */
    private function calculateScore(QuizAttempt $attempt): void
    {
        $questions = $attempt->quiz->questions;
        $answers = $attempt->answers->keyBy('question_id');

        $totalBenar = 0;
        $totalBobot = 0;
        $totalBobotBenar = 0;

        foreach ($questions as $question) {
            $totalBobot += $question->bobot;
            $answer = $answers->get($question->id);

            if ($answer) {
                $isCorrect = false;

                switch ($question->tipe) {
                    case 'pg_4':
                    case 'pg_5':
                        $isCorrect = strtoupper(trim($answer->jawaban)) === strtoupper(trim($question->jawaban));
                        break;
                    case 'benar_salah':
                        $isCorrect = strtolower(trim($answer->jawaban)) === strtolower(trim($question->jawaban));
                        break;
                    case 'essay':
                        // Essay needs manual grading, skip
                        break;
                }

                if ($isCorrect) {
                    $totalBenar++;
                    $totalBobotBenar += $question->bobot;
                    $answer->update(['is_correct' => true]);
                } else {
                    $answer->update(['is_correct' => false]);
                }
            }
        }

        // Check if all questions are non-essay (auto-grade)
        $hasEssay = $questions->contains(fn ($q) => $q->tipe === 'essay');

        $nilai = $totalBobot > 0
            ? round(($totalBobotBenar / $totalBobot) * 100, 1)
            : 0;

        $attempt->update([
            'total_benar' => $totalBenar,
            'total_soal'  => $questions->count(),
            'nilai'       => $hasEssay ? null : $nilai,
        ]);
    }

    /**
     * Verify the authenticated siswa is enrolled in the given class.
     */
    private function verifyEnrollment(Kelas $kelas): void
    {
        $siswa = Auth::user();

        if (! $siswa->kelas()->where('kelas.id', $kelas->id)->exists()) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }
    }
}
