<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Handle file upload and return the path.
     */
    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file'      => ['required', 'file', 'max:102400'],
            'directory' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $directory = $validated['directory'] ?? 'uploads';

        // Allowed MIME types
        $allowedMimes = [
            'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp',
            'mp4', 'avi', 'mov', 'mkv',
            'zip', 'rar', '7z',
            'txt', 'csv',
        ];

        $extension = $file->getClientOriginalExtension();
        if (! in_array(strtolower($extension), $allowedMimes)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe file tidak diizinkan.',
            ], 422);
        }

        $filename = time() . '_' . auth()->id() . '_' . \Illuminate\Support\Str::slug(
            pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
        ) . '.' . $extension;

        $path = $file->storeAs($directory, $filename, 'public');

        return response()->json([
            'success'   => true,
            'message'   => 'File berhasil diunggah.',
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_url'  => Storage::disk('public')->url($path),
        ], 201);
    }

    /**
     * Download a file from storage.
     */
    public function download(Request $request, string $path): \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse
    {
        // Decode the path
        $fullPath = storage_path('app/public/' . $path);

        if (! file_exists($fullPath)) {
            return back()->withErrors('File tidak ditemukan.');
        }

        $filename = basename($fullPath);

        return response()->download($fullPath, $filename);
    }

    /**
     * Delete a file from storage.
     */
    public function delete(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'file_path' => ['required', 'string'],
        ]);

        $fullPath = 'public/' . $validated['file_path'];

        if (Storage::exists($fullPath)) {
            Storage::delete($fullPath);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'File berhasil dihapus.',
                ]);
            }

            return back()->with('success', 'File berhasil dihapus.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.',
            ], 404);
        }

        return back()->withErrors('File tidak ditemukan.');
    }

    /**
     * View/display a file inline (e.g., images, PDFs).
     */
    public function show(string $path): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $fullPath = storage_path('app/public/' . $path);

        if (! file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Determine MIME type
        $mimeType = mime_content_type($fullPath);

        $filename = basename($fullPath);

        return response()->file($fullPath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
