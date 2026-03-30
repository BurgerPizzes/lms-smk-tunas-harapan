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

        $query = Quiz::where('class_id', $kelas->id)
            ->where('is_published', true)
            ->with('mapel');

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        $quizzes = $query->orderBy('mulai_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Annotate with attempt status
        $quizzes->transform(function ($quiz) use ($siswa) {
            $attempt = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('siswa_id', $siswa->id)
                ->latest()
                ->first();

            $quiz->attempt = $attempt;
            $quiz->has_attempt = $attempt !== null;
            $quiz->is_active = $quiz->mulai_at && $quiz->mulai_at <= now() && $quiz->selesai_at && $quiz->selesai_at >= now();
            $quiz->is_upcoming = $quiz->mulai_at && $quiz->mulai_at > now();
            $quiz->is_ended = $quiz->selesai_at && $quiz->selesai_at < now();

            return $quiz;
        });

        $mapels = \App\Models\Mapel::whereHas('quiz', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)->where('is_published', true);
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
        if ($quiz->mulai_at && $quiz->mulai_at > now()) {
            return back()->withErrors('Quiz belum dimulai.');
        }

        if ($quiz->selesai_at && $quiz->selesai_at < now()) {
            return back()->withErrors('Quiz telah berakhir.');
        }

        // Check if there's already a pending attempt
        $pendingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'dikerjakan')
            ->first();

        if ($pendingAttempt) {
            // Resume existing attempt
            return $this->showQuizPage($quiz, $pendingAttempt);
        }

        // Check max attempts
        $totalAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('siswa_id', $siswa->id)
            ->count();

        // Create new attempt
        DB::beginTransaction();
        try {
            $attempt = QuizAttempt::create([
                'quiz_id'     => $quiz->id,
                'siswa_id'   => $siswa->id,
                'status'      => 'dikerjakan',
                'waktu_mulai' => now(),
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
            ->where('siswa_id', $siswa->id)
            ->where('status', 'dikerjakan')
            ->firstOrFail();

        // Check time limit
        $timeRemaining = $this->getTimeRemaining($attempt, $attempt->quiz);
        if ($timeRemaining !== null && $timeRemaining <= 0) {
            return response()->json(['error' => 'Waktu quiz telah berakhir.'], 403);
        }

        // Upsert answer via QuizAnswer
        QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id'  => $attempt->id,
                'quiz_question_id' => $validated['question_id'],
            ],
            [
                'jawaban' => $validated['jawaban'],
            ]
        );

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

        if ($attempt->siswa_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke attempt ini.');
        }

        if ($attempt->status !== 'dikerjakan') {
            return redirect()->route('siswa.quiz.result', $attempt)
                ->with('info', 'Quiz sudah selesai.');
        }

        DB::beginTransaction();
        try {
            // Auto-submit if time is up
            $attempt->update([
                'status'        => 'selesai',
                'waktu_selesai' => now(),
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

        if ($attempt->siswa_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke hasil ini.');
        }

        $attempt->load([
            'quiz.kelas',
            'quiz.mapel',
        ]);

        // Load quiz answers
        $attempt->load('answers.question');

        // Only show pembahasan if enabled
        $showPembahasan = $attempt->quiz->show_result;

        return view('siswa.quiz.result', compact('attempt', 'showPembahasan'));
    }

    /**
     * Show the quiz page with questions.
     */
    private function showQuizPage(Quiz $quiz, QuizAttempt $attempt): \Illuminate\View\View
    {
        $questions = $quiz->questions()->orderBy('urutan')->get();

        // Shuffle questions if configured
        if ($quiz->random_soal) {
            $questions = $questions->shuffle();
        }

        // Load existing answers
        $existingAnswers = QuizAnswer::where('quiz_attempt_id', $attempt->id)
            ->get()
            ->keyBy('quiz_question_id');

        $timeRemaining = $this->getTimeRemaining($attempt, $quiz);

        return view('siswa.quiz.take', compact(
            'quiz', 'attempt', 'questions', 'existingAnswers', 'timeRemaining'
        ));
    }

    /**
     * Calculate time remaining for a quiz attempt.
     */
    private function getTimeRemaining(QuizAttempt $attempt, Quiz $quiz): ?int
    {
        if ($attempt->waktu_mulai) {
            $quizDuration = $quiz->durasi_menit * 60;
            $elapsed = now()->diffInSeconds($attempt->waktu_mulai);
            return max(0, $quizDuration - $elapsed);
        }
        return $quiz->durasi_menit * 60;
    }

    /**
     * Calculate the score for a completed attempt.
     */
    private function calculateScore(QuizAttempt $attempt): void
    {
        $questions = $attempt->quiz->questions;
        $answers = QuizAnswer::where('quiz_attempt_id', $attempt->id)->get()->keyBy('quiz_question_id');

        $totalBenar = 0;
        $totalPoin = 0;
        $totalPoinBenar = 0;

        foreach ($questions as $question) {
            $totalPoin += $question->poin;
            $answer = $answers->get($question->id);

            if ($answer) {
                $isCorrect = false;

                switch ($question->tipe) {
                    case 'pilihan_ganda':
                        $isCorrect = strtoupper(trim($answer->jawaban)) === strtoupper(trim($question->jawaban_benar));
                        break;
                    case 'true_false':
                        $isCorrect = strtolower(trim($answer->jawaban)) === strtolower(trim($question->jawaban_benar));
                        break;
                    case 'essay':
                        // Essay needs manual grading, skip
                        break;
                }

                if ($isCorrect) {
                    $totalBenar++;
                    $totalPoinBenar += $question->poin;
                    $answer->update(['benar' => true]);
                } else {
                    $answer->update(['benar' => false]);
                }
            }
        }

        // Check if all questions are non-essay (auto-grade)
        $hasEssay = $questions->contains(fn ($q) => $q->tipe === 'essay');

        $skor = $totalPoin > 0
            ? round(($totalPoinBenar / $totalPoin) * 100, 1)
            : 0;

        $attempt->update([
            'total_benar' => $totalBenar,
            'total_salah' => $questions->count() - $totalBenar,
            'total_soal'  => $questions->count(),
            'skor'        => $hasEssay ? null : $skor,
        ]);
    }

    /**
     * Verify the authenticated siswa is enrolled in the given class.
     */
    private function verifyEnrollment(Kelas $kelas): void
    {
        $siswa = Auth::user();

        if (! $siswa->enrolledClasses()->where('kelas.id', $kelas->id)->exists()) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }
    }
}
