<?php

namespace App\Enums;

enum SubjectCategoryEnum: string
{
    case NORMATIF  = 'normatif';
    case ADAPTIF   = 'adaptif';
    case PRODUKTIF = 'produktif';

    /**
     * Get the human-readable label for the category.
     */
    public function label(): string
    {
        return match ($this) {
            self::NORMATIF  => 'Muatan Normatif',
            self::ADAPTIF   => 'Muatan Adaptif',
            self::PRODUKTIF => 'Muatan Produktif',
        };
    }

    /**
     * Get the description for the category.
     */
    public function description(): string
    {
        return match ($this) {
            self::NORMATIF  => 'Mata pelajaran wajib nasional (Pendidikan Agama, PKn, Bahasa Indonesia)',
            self::ADAPTIF   => 'Mata pelajaran dasar umum (Matematika, Bahasa Inggris, IPA/IPS)',
            self::PRODUKTIF => 'Mata pelajaran sesuai kompetensi keahlian program studi',
        };
    }

    /**
     * Get the color for UI badges.
     */
    public function color(): string
    {
        return match ($this) {
            self::NORMATIF  => 'blue',
            self::ADAPTIF   => 'green',
            self::PRODUKTIF => 'purple',
        };
    }

    /**
     * Get all categories as an associative array [value => label].
     */
    public static function toArray(): array
    {
        return array_column(
            array_map(fn (self $cat) => [
                'value' => $cat->value,
                'label' => $cat->label(),
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
     * Check if the given value is a valid category.
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values());
    }
}
