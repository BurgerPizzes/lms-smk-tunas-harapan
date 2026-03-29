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

        $query = Quiz::where('kelas_id', $kelas->id)->with('mapel', 'user');

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_published', true)
                      ->where('waktu_mulai', '<=', now())
                      ->where('waktu_selesai', '>=', now());
            } elseif ($status === 'upcoming') {
                $query->where('is_published', true)->where('waktu_mulai', '>', now());
            } elseif ($status === 'ended') {
                $query->where('waktu_selesai', '<', now());
            }
        }

        $quizzes = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id)
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
            $query->where('kelas_id', $kelas->id)
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
            'waktu_mulai'   => ['required', 'date', 'after:now'],
            'waktu_selesai' => ['required', 'date', 'after:waktu_mulai'],
            'durasi_menit'  => ['required', 'integer', 'min:1', 'max:300'],
            'bobot'         => ['nullable', 'integer', 'min:1'],
            'tampilkan_nilai' => ['sometimes', 'boolean'],
            'acak_soal'     => ['sometimes', 'boolean'],
            'acak_pilihan'  => ['sometimes', 'boolean'],
            'is_published'  => ['sometimes', 'boolean'],
        ]);

        $validated['kelas_id'] = $kelas->id;
        $validated['user_id']  = Auth::id();
        $validated['bobot']    = $validated['bobot'] ?? 100;
        $validated['tampilkan_nilai'] = $request->boolean('tampilkan_nilai', true);
        $validated['acak_soal'] = $request->boolean('acak_soal', false);
        $validated['acak_pilihan'] = $request->boolean('acak_pilihan', false);
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

        $quiz->load(['kelas', 'mapel', 'user', 'questions' => function ($query) {
            $query->orderBy('nomor');
        }]);

        $attemptStats = QuizAttempt::where('quiz_id', $quiz->id)
            ->selectRaw('
                COUNT(*) as total_attempts,
                COUNT(DISTINCT user_id) as unique_students,
                AVG(nilai) as average_score,
                MAX(nilai) as highest_score,
                MIN(nilai) as lowest_score
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
            'tipe'        => ['required', 'string', 'in:pg_4,pg_5,benar_salah,essay'],
            'pertanyaan'  => ['required', 'string'],
            'pilihan_a'   => ['nullable', 'string', 'required_if:tipe,pg_4,pg_5'],
            'pilihan_b'   => ['nullable', 'string', 'required_if:tipe,pg_4,pg_5'],
            'pilihan_c'   => ['nullable', 'string', 'required_if:tipe,pg_4,pg_5'],
            'pilihan_d'   => ['nullable', 'string', 'required_if:tipe,pg_4,pg_5'],
            'pilihan_e'   => ['nullable', 'string', 'required_if:tipe,pg_5'],
            'jawaban'     => ['required', 'string'],
            'pembahasan'  => ['nullable', 'string'],
            'bobot'       => ['nullable', 'integer', 'min:1'],
            'gambar'      => ['nullable', 'image', 'max:2048'],
        ]);

        $nextNumber = QuizQuestion::where('quiz_id', $quiz->id)->max('nomor') ?? 0;
        $validated['quiz_id'] = $quiz->id;
        $validated['nomor']   = $nextNumber + 1;
        $validated['bobot']   = $validated['bobot'] ?? 1;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('quiz/images', 'public');
            $validated['gambar'] = $path;
        }

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
            'tipe'        => ['required', 'string', 'in:pg_4,pg_5,benar_salah,essay'],
            'pertanyaan'  => ['required', 'string'],
            'pilihan_a'   => ['nullable', 'string', 'required_if:tipe,pg_4,pg_5'],
            'pilihan_b'   => ['nullable', 'string', 'required_if:tipe,pg_4,pg_5'],
            'pilihan_c'   => ['nullable', 'string', 'required_if:tipe,pg_4,pg_5'],
            'pilihan_d'   => ['nullable', 'string', 'required_if:tipe,pg_4,pg_5'],
            'pilihan_e'   => ['nullable', 'string', 'required_if:tipe,pg_5'],
            'jawaban'     => ['required', 'string'],
            'pembahasan'  => ['nullable', 'string'],
            'bobot'       => ['nullable', 'integer', 'min:1'],
            'gambar'      => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['bobot'] = $validated['bobot'] ?? 1;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            if ($question->gambar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($question->gambar);
            }
            $path = $request->file('gambar')->store('quiz/images', 'public');
            $validated['gambar'] = $path;
        }

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

        if ($question->gambar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($question->gambar);
        }

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
            ->with(['user', 'answers.question'])
            ->orderBy('nilai', 'desc')
            ->paginate(25);

        $statistics = [
            'total_attempts'   => QuizAttempt::where('quiz_id', $quiz->id)->count(),
            'unique_students'  => QuizAttempt::where('quiz_id', $quiz->id)->distinct('user_id')->count('user_id'),
            'average_score'    => QuizAttempt::where('quiz_id', $quiz->id)->whereNotNull('nilai')->avg('nilai'),
            'highest_score'    => QuizAttempt::where('quiz_id', $quiz->id)->max('nilai'),
            'lowest_score'     => QuizAttempt::where('quiz_id', $quiz->id)->min('nilai'),
            'passed_count'     => QuizAttempt::where('quiz_id', $quiz->id)->where('nilai', '>=', 75)->count(),
            'failed_count'     => QuizAttempt::where('quiz_id', $quiz->id)->where('nilai', '<', 75)->count(),
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
            ->where('kelas_id', $kelas->id)
            ->exists();

        if (! $hasAccess && $kelas->wali_kelas_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
    }
}
