<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case GURU  = 'guru';
    case SISWA = 'siswa';

    /**
     * Get the human-readable label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::GURU  => 'Guru',
            self::SISWA => 'Siswa',
        };
    }

    /**
     * Get the description for the role.
     */
    public function description(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator sistem LMS',
            self::GURU  => 'Guru / Pengajar',
            self::SISWA => 'Siswa / Peserta Didik',
        };
    }

    /**
     * Get all roles as an associative array [value => label].
     */
    public static function toArray(): array
    {
        return array_column(
            array_map(fn (self $role) => [
                'value' => $role->value,
                'label' => $role->label(),
            ], self::cases()),
            'label',
            'value'
        );
    }

    /**
     * Get all values as a plain array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if the given value is a valid role.
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values());
    }
}
