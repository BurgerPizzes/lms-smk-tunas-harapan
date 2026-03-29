<?php

namespace App\Enums;

enum AttendanceStatusEnum: string
{
    case HADIR = 'hadir';
    case IZIN  = 'izin';
    case SAKIT = 'sakit';
    case ALPHA = 'alpha';

    /**
     * Get the human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::HADIR => 'Hadir',
            self::IZIN  => 'Izin',
            self::SAKIT => 'Sakit',
            self::ALPHA => 'Alpha',
        };
    }

    /**
     * Get the color for UI badges.
     */
    public function color(): string
    {
        return match ($this) {
            self::HADIR => 'green',
            self::IZIN  => 'blue',
            self::SAKIT => 'yellow',
            self::ALPHA => 'red',
        };
    }

    /**
     * Get the point value (for grading/penalty calculations).
     * Hadir = no penalty, Izin/Sakit = moderate, Alpha = full penalty.
     */
    public function pointValue(): int
    {
        return match ($this) {
            self::HADIR => 100,
            self::IZIN  => 75,
            self::SAKIT => 75,
            self::ALPHA => 0,
        };
    }

    /**
     * Check if the status counts as an absence.
     */
    public function isAbsent(): bool
    {
        return in_array($this, [self::IZIN, self::SAKIT, self::ALPHA]);
    }

    /**
     * Check if the status requires a note/excuse.
     */
    public function requiresNote(): bool
    {
        return in_array($this, [self::IZIN, self::SAKIT]);
    }

    /**
     * Get all statuses as an associative array [value => label].
     */
    public static function toArray(): array
    {
        return array_column(
            array_map(fn (self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
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
     * Check if the given value is a valid status.
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values());
    }
}
