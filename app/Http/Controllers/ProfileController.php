<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit(): View
    {
        $user = Auth::user();
        $user->load(['jurusan', 'kelas']);

        return view('profile.edit', compact('user'));
    }

    /**
     * Update profile info.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_hp'  => ['nullable', 'string', 'max:20'],
        ]);

        // Siswa-specific fields
        if ($user->hasRole('siswa')) {
            $validated = array_merge($validated, $request->validate([
                'nis'   => ['nullable', 'string', 'max:50', 'unique:users,nis,' . $user->id],
                'nisn'  => ['nullable', 'string', 'max:50', 'unique:users,nisn,' . $user->id],
            ]));
        }

        // Guru-specific fields
        if ($user->hasRole('guru')) {
            $validated = array_merge($validated, $request->validate([
                'nip' => ['nullable', 'string', 'max:50', 'unique:users,nip,' . $user->id],
            ]));
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Change password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Optionally invalidate other sessions
        Auth::logoutOtherDevices($validated['password']);

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Update profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'photo' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);

        // Delete old photo
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $file = $validated['photo'];
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profiles', $filename, 'public');

        $user->update([
            'foto' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Remove profile photo.
     */
    public function removePhoto(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->update(['foto' => null]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
