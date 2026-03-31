<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ClassGuruMapel;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users with search and filters.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Filter by role (Spatie)
        if ($request->filled('role')) {
            $query->role($request->input('role'));
        }

        // Filter by jurusan
        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->input('jurusan_id'));
        }

        // Filter by kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->input('kelas_id'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $is_active = $request->input('status') === 'active';
            $query->where('is_active', $is_active);
        }

        $users = $query->with(['jurusan', 'kelas', 'roles'])
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $jurusans = Jurusan::where('aktif', true)->orderBy('nama')->get();
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();

        return view('admin.users.index', compact('users', 'jurusans', 'kelasList'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): \Illuminate\View\View
    {
        $jurusans = Jurusan::where('aktif', true)->orderBy('nama')->get();
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();

        return view('admin.users.create', compact('jurusans', 'kelasList'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role'      => ['required', 'string', 'in:admin,guru,siswa'],
            'nip'       => ['nullable', 'string', 'max:50', 'unique:users,nip'],
            'nis'       => ['nullable', 'string', 'max:50', 'unique:users,nis'],
            'nisn'      => ['nullable', 'string', 'max:50', 'unique:users,nisn'],
            'no_hp'     => ['nullable', 'string', 'max:20'],
            'jurusan_id' => ['nullable', 'exists:jurusans,id'],
            'kelas_id'  => ['nullable', 'exists:kelas,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $role = $validated['role'];
        unset($validated['role']);

        $user = User::create($validated);
        $user->assignRole($role);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified user with related data.
     */
    public function show(User $user): \Illuminate\View\View
    {
        $user->load(['jurusan', 'kelas', 'roles']);

        // Related data for siswa
        $enrolledClasses = null;
        $teachingSubjects = null;
        $activityLogs = null;

        if ($user->hasRole('siswa')) {
            $enrolledClasses = $user->enrolledClasses()->with(['jurusan', 'waliKelas'])->get();
        }

        if ($user->hasRole('guru')) {
            $teachingSubjects = ClassGuruMapel::where('guru_id', $user->id)
                ->with(['mapel', 'kelas', 'tahunAjaran'])
                ->get();
        }

        $activityLogs = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.users.show', compact('user', 'enrolledClasses', 'teachingSubjects', 'activityLogs'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): \Illuminate\View\View
    {
        $user->load('roles');
        $jurusans = Jurusan::where('aktif', true)->orderBy('nama')->get();
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();

        return view('admin.users.edit', compact('user', 'jurusans', 'kelasList'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'      => ['required', 'string', 'in:admin,guru,siswa'],
            'nip'       => ['nullable', 'string', 'max:50', 'unique:users,nip,' . $user->id],
            'nis'       => ['nullable', 'string', 'max:50', 'unique:users,nis,' . $user->id],
            'nisn'      => ['nullable', 'string', 'max:50', 'unique:users,nisn,' . $user->id],
            'no_hp'     => ['nullable', 'string', 'max:20'],
            'jurusan_id' => ['nullable', 'exists:jurusans,id'],
            'kelas_id'  => ['nullable', 'exists:kelas,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', $user->is_active);

        $role = $validated['role'];
        unset($validated['role']);

        $user->update($validated);
        $user->syncRoles([$role]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Soft delete the specified user.
     */
    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->withErrors('Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna berhasil {$status}.");
    }

    /**
     * Export users to CSV.
     */
    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $users = User::with(['jurusan', 'kelas', 'roles'])
            ->orderBy('name')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="users_' . date('Y-m-d_His') . '.csv"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['ID', 'Nama', 'Email', 'NIS/NIP', 'Role', 'Jurusan', 'Kelas', 'No HP', 'Status', 'Terdaftar']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->nip ?? $user->nis ?? '-',
                    ucfirst($user->getRoleNames()->first() ?? '-'),
                    $user->jurusan?->nama ?? '-',
                    $user->kelas?->nama ?? '-',
                    $user->no_hp ?? '-',
                    $user->is_active ? 'Aktif' : 'Nonaktif',
                    $user->created_at->format('d-m-Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
