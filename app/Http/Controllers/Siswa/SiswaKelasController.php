<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaKelasController extends Controller
{
    /**
     * List enrolled classes.
     */
    public function index(): \Illuminate\View\View
    {
        $siswa = Auth::user();

        $kelasList = $siswa->kelas()
            ->with(['jurusan', 'waliKelas'])
            ->orderBy('nama')
            ->paginate(12);

        return view('siswa.kelas.index', compact('kelasList'));
    }

    /**
     * Show join class form (enter kode_unik).
     */
    public function joinClass(): \Illuminate\View\View
    {
        return view('siswa.kelas.join');
    }

    /**
     * Join a class via kode_unik.
     */
    public function join(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'kode_unik' => ['required', 'string', 'size:8'],
        ]);

        $siswa = Auth::user();

        $kelas = Kelas::where('kode_unik', strtoupper($validated['kode_unik']))
            ->where('is_active', true)
            ->first();

        if (! $kelas) {
            return back()
                ->withErrors(['kode_unik' => 'Kode kelas tidak valid atau kelas sudah dinonaktifkan.'])
                ->withInput();
        }

        // Check if already enrolled
        if ($siswa->kelas()->where('kelas.id', $kelas->id)->exists()) {
            return back()
                ->withErrors(['kode_unik' => 'Anda sudah terdaftar di kelas ini.'])
                ->withInput();
        }

        $siswa->kelas()->attach($kelas->id);

        return redirect()
            ->route('siswa.kelas.show', $kelas)
            ->with('success', 'Berhasil bergabung ke kelas ' . $kelas->nama . '.');
    }

    /**
     * Display the specified class with feed.
     */
    public function show(Kelas $kelas): \Illuminate\View\View
    {
        $siswa = Auth::user();

        // Verify enrollment
        if (! $siswa->kelas()->where('kelas.id', $kelas->id)->exists()) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        $kelas->load(['jurusan', 'waliKelas']);

        // Feed: Materi and Tugas timeline
        $materiFeed = $kelas->materi()
            ->where('is_published', true)
            ->with('mapel', 'user')
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($item) => [
                'type'       => 'materi',
                'id'         => $item->id,
                'title'      => $item->judul,
                'mapel'      => $item->mapel?->nama,
                'user_name'  => $item->user?->name,
                'created_at' => $item->created_at,
            ]);

        $tugasFeed = $kelas->tugas()
            ->where('is_published', true)
            ->with('mapel', 'user')
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($item) use ($siswa) {
                $submission = \App\Models\Submission::where('tugas_id', $item->id)
                    ->where('siswa_id', $siswa->id)
                    ->first();

                return [
                    'type'          => 'tugas',
                    'id'            => $item->id,
                    'title'         => $item->judul,
                    'mapel'         => $item->mapel?->nama,
                    'user_name'     => $item->user?->name,
                    'created_at'    => $item->created_at,
                    'deadline'      => $item->deadline,
                    'is_submitted'  => $submission !== null,
                    'grade'         => $submission?->nilai,
                    'is_expired'    => $item->deadline && $item->deadline < now(),
                ];
            });

        // Merge and sort
        $feed = $materiFeed->merge($tugasFeed)->sortByDesc('created_at')->values();

        return view('siswa.kelas.show', compact('kelas', 'feed'));
    }

    /**
     * Leave a class.
     */
    public function leave(Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $siswa = Auth::user();

        if (! $siswa->kelas()->where('kelas.id', $kelas->id)->exists()) {
            return back()->withErrors('Anda tidak terdaftar di kelas ini.');
        }

        $siswa->kelas()->detach($kelas->id);

        return redirect()
            ->route('siswa.kelas.index')
            ->with('success', 'Berhasil keluar dari kelas ' . $kelas->nama . '.');
    }
}
