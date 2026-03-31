<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruQuizController extends Controller
{
    /**
     * Display a listing of quizzes in a class.
     */
    public function index(Request $request, Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $query = Quiz::where('class_id', $kelas->id)->with('mapel', 'guru');

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_published', true)
                      ->where('mulai_at', '<=', now())
                      ->where('selesai_at', '>=', now());
            } elseif ($status === 'upcoming') {
                $query->where('is_published', true)->where('mulai_at', '>', now());
            } elseif ($status === 'ended') {
                $query->where('selesai_at', '<', now());
            }
        }

        $quizzes = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.quiz.index', compact('kelas', 'quizzes', 'mapels'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.quiz.create', compact('kelas', 'mapels'));
    }

    /**
     * Store a newly created quiz.
     */
    public function store(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($kelas);

        $validated = $request->validate([
            'judul'         => ['required', 'string', 'max:255'],
            'mapel_id'      => ['required', 'exists:mapels,id'],
            'deskripsi'     => ['nullable', 'string'],
            'mulai_at'      => ['required', 'date', 'after:now'],
            'selesai_at'    => ['required', 'date', 'after:mulai_at'],
            'durasi_menit'  => ['required', 'integer', 'min:1', 'max:300'],
            'show_result'   => ['sometimes', 'boolean'],
            'random_soal'   => ['sometimes', 'boolean'],
            'is_published'  => ['sometimes', 'boolean'],
        ]);

        $validated['class_id'] = $kelas->id;
        $validated['guru_id']  = Auth::id();
        $validated['show_result'] = $request->boolean('show_result', true);
        $validated['random_soal'] = $request->boolean('random_soal', false);
        $validated['is_published'] = $request->boolean('is_published', false);

        $quiz = Quiz::create($validated);

        return redirect()
            ->route('guru.quiz.show', $quiz)
            ->with('success', 'Quiz berhasil dibuat. Silakan tambahkan soal.');
    }

    /**
     * Display the specified quiz with its questions.
     */
    public function show(Quiz $quiz): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($quiz->kelas);

        $quiz->load(['kelas', 'mapel', 'guru', 'questions' => function ($query) {
            $query->orderBy('urutan');
        }, 'attempts' => function ($query) {
            $query->with('siswa');
        }]);

        $attemptStats = QuizAttempt::where('quiz_id', $quiz->id)
            ->selectRaw('
                COUNT(*) as total_attempts,
                COUNT(DISTINCT siswa_id) as unique_students,
                AVG(skor) as average_score,
                MAX(skor) as highest_score,
                MIN(skor) as lowest_score
            ')
            ->first();

        return view('guru.quiz.show', compact('quiz', 'attemptStats'));
    }

    /**
     * Add a question to a quiz.
     */
    public function addQuestion(Request $request, Quiz $quiz): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($quiz->kelas);

        $validated = $request->validate([
            'tipe'        => ['required', 'string', 'in:pilihan_ganda,essay,true_false'],
            'pertanyaan'  => ['required', 'string'],
            'pilihan_a'   => ['nullable', 'string'],
            'pilihan_b'   => ['nullable', 'string'],
            'pilihan_c'   => ['nullable', 'string'],
            'pilihan_d'   => ['nullable', 'string'],
            'pilihan_e'   => ['nullable', 'string'],
            'jawaban_benar' => ['required', 'string'],
            'pembahasan'  => ['nullable', 'string'],
            'poin'        => ['nullable', 'integer', 'min:1'],
        ]);

        $nextNumber = QuizQuestion::where('quiz_id', $quiz->id)->max('urutan') ?? 0;
        $validated['quiz_id'] = $quiz->id;
        $validated['urutan']  = $nextNumber + 1;
        $validated['poin']    = $validated['poin'] ?? 10;

        QuizQuestion::create($validated);

        return redirect()
            ->route('guru.quiz.show', $quiz)
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Update a question.
     */
    public function updateQuestion(Request $request, QuizQuestion $question): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($question->quiz->kelas);

        $validated = $request->validate([
            'tipe'        => ['required', 'string', 'in:pilihan_ganda,essay,true_false'],
            'pertanyaan'  => ['required', 'string'],
            'pilihan_a'   => ['nullable', 'string'],
            'pilihan_b'   => ['nullable', 'string'],
            'pilihan_c'   => ['nullable', 'string'],
            'pilihan_d'   => ['nullable', 'string'],
            'pilihan_e'   => ['nullable', 'string'],
            'jawaban_benar' => ['required', 'string'],
            'pembahasan'  => ['nullable', 'string'],
            'poin'        => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['poin'] = $validated['poin'] ?? 10;

        $question->update($validated);

        return redirect()
            ->route('guru.quiz.show', $question->quiz)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Delete a question.
     */
    public function deleteQuestion(QuizQuestion $question): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($question->quiz->kelas);

        $question->delete();

        return redirect()
            ->route('guru.quiz.show', $question->quiz)
            ->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * View all attempts and scores for a quiz.
     */
    public function results(Quiz $quiz): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($quiz->kelas);

        $quiz->load(['kelas', 'mapel']);

        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->with('siswa')
            ->orderBy('skor', 'desc')
            ->paginate(25);

        $statistics = [
            'total_attempts'   => QuizAttempt::where('quiz_id', $quiz->id)->count(),
            'unique_students'  => QuizAttempt::where('quiz_id', $quiz->id)->distinct('siswa_id')->count('siswa_id'),
            'average_score'    => QuizAttempt::where('quiz_id', $quiz->id)->whereNotNull('skor')->avg('skor'),
            'highest_score'    => QuizAttempt::where('quiz_id', $quiz->id)->max('skor'),
            'lowest_score'     => QuizAttempt::where('quiz_id', $quiz->id)->min('skor'),
            'passed_count'     => QuizAttempt::where('quiz_id', $quiz->id)->where('skor', '>=', 75)->count(),
            'failed_count'     => QuizAttempt::where('quiz_id', $quiz->id)->where('skor', '<', 75)->count(),
        ];

        return view('guru.quiz.results', compact('quiz', 'attempts', 'statistics'));
    }

    /**
     * Authorize that the authenticated guru has access to the given class.
     */
    private function authorizeGuruAccess(Kelas $kelas): void
    {
        $guru = auth()->user();
        $hasAccess = GuruMapel::where('guru_id', $guru->id)
            ->where('class_id', $kelas->id)
            ->exists();

        if (! $hasAccess && $kelas->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
    }
}
