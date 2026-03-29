<?php

use App\Enums\RoleEnum;
use App\Enums\SubjectCategoryEnum;
use App\Enums\SubmissionStatusEnum;
use App\Enums\AttendanceStatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

if (! function_exists('format_rupiah')) {
    /**
     * Format number as Indonesian Rupiah.
     */
    function format_rupiah(int|float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (! function_exists('format_tanggal')) {
    /**
     * Format date to Indonesian format.
     */
    function format_tanggal(string|Carbon $date, string $format = 'd F Y'): string
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $carbon->translatedFormat($format);
    }
}

if (! function_exists('format_waktu')) {
    /**
     * Format datetime to Indonesian format with time.
     */
    function format_waktu(string|Carbon $datetime): string
    {
        return format_tanggal($datetime, 'd F Y, H:i');
    }
}

if (! function_exists('get_user_role')) {
    /**
     * Get the primary role label of the authenticated user.
     */
    function get_user_role(): ?string
    {
        $user = auth()->user();
        if (! $user) {
            return null;
        }

        $role = $user->roles->first();

        return $role?->name ?? null;
    }
}

if (! function_exists('is_admin')) {
    function is_admin(): bool
    {
        return auth()->check() && auth()->user()->hasRole(RoleEnum::ADMIN->value);
    }
}

if (! function_exists('is_guru')) {
    function is_guru(): bool
    {
        return auth()->check() && auth()->user()->hasRole(RoleEnum::GURU->value);
    }
}

if (! function_exists('is_siswa')) {
    function is_siswa(): bool
    {
        return auth()->check() && auth()->user()->hasRole(RoleEnum::SISWA->value);
    }
}

if (! function_exists('get_role_badge_color')) {
    /**
     * Get Tailwind CSS badge color class for a role.
     */
    function get_role_badge_color(string $role): string
    {
        return match ($role) {
            RoleEnum::ADMIN->value => 'bg-red-100 text-red-800',
            RoleEnum::GURU->value  => 'bg-blue-100 text-blue-800',
            RoleEnum::SISWA->value => 'bg-green-100 text-green-800',
            default                => 'bg-gray-100 text-gray-800',
        };
    }
}

if (! function_exists('get_status_badge_color')) {
    /**
     * Get Tailwind CSS badge color class for submission/attendance status.
     */
    function get_status_badge_color(string $status): string
    {
        return match ($status) {
            'submitted', 'hadir'     => 'bg-green-100 text-green-800',
            'late', 'sakit'          => 'bg-yellow-100 text-yellow-800',
            'not_submitted', 'alpha' => 'bg-red-100 text-red-800',
            'izin'                    => 'bg-blue-100 text-blue-800',
            default                   => 'bg-gray-100 text-gray-800',
        };
    }
}

if (! function_exists('get_subject_category_label')) {
    /**
     * Get human-readable label for a subject category.
     */
    function get_subject_category_label(string $category): string
    {
        return SubjectCategoryEnum::tryFrom($category)?->label() ?? ucfirst($category);
    }
}

if (! function_exists('generate_nis')) {
    /**
     * Generate a unique NIS (Nomor Induk Siswa).
     */
    function generate_nis(int $year, int $sequence): string
    {
        return sprintf('%d%04d', $year, $sequence);
    }
}

if (! function_exists('truncate_text')) {
    /**
     * Truncate text to a specified length with ellipsis.
     */
    function truncate_text(string $text, int $length = 100): string
    {
        return \Illuminate\Support\Str::limit($text, $length);
    }
}

if (! function_exists('format_file_size')) {
    /**
     * Format file size in human-readable format.
     */
    function format_file_size(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}

if (! function_exists('academic_year')) {
    /**
     * Get current academic year string (e.g., "2024/2025").
     */
    function academic_year(?int $month = null): string
    {
        $month = $month ?? now()->month;
        $year = now()->year;

        // Academic year in Indonesia starts in July
        if ($month >= 7) {
            return $year . '/' . ($year + 1);
        }

        return ($year - 1) . '/' . $year;
    }
}

if (! function_exists('current_semester')) {
    /**
     * Get the current semester based on the month.
     */
    function current_semester(?int $month = null): string
    {
        $month = $month ?? now()->month;

        // Semester Ganjil: July - December
        // Semester Genap: January - June
        return ($month >= 7) ? 'ganjil' : 'genap';
    }
}

if (! function_exists('get_attendance_summary')) {
    /**
     * Calculate attendance summary from a collection of attendance records.
     */
    function get_attendance_summary(Collection $attendances): array
    {
        return [
            'hadir'  => $attendances->where('status', AttendanceStatusEnum::HADIR->value)->count(),
            'izin'   => $attendances->where('status', AttendanceStatusEnum::IZIN->value)->count(),
            'sakit'  => $attendances->where('status', AttendanceStatusEnum::SAKIT->value)->count(),
            'alpha'  => $attendances->where('status', AttendanceStatusEnum::ALPHA->value)->count(),
            'total'  => $attendances->count(),
            'percentage' => $attendances->count() > 0
                ? round(($attendances->where('status', AttendanceStatusEnum::HADIR->value)->count() / $attendances->count()) * 100, 1)
                : 0,
        ];
    }
}
