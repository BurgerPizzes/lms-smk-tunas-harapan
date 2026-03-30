<?php

namespace App\Enums;

enum SubmissionStatusEnum: string
{
    case SUBMITTED     = 'submitted';
    case LATE          = 'late';
    case NOT_SUBMITTED = 'not_submitted';

    /**
     * Get the human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::SUBMITTED     => 'Sudah Dikumpulkan',
            self::LATE          => 'Terlambat',
            self::NOT_SUBMITTED => 'Belum Dikumpulkan',
        };
    }

    /**
     * Get the color for UI badges.
     */
    public function color(): string
    {
        return match ($this) {
            self::SUBMITTED     => 'green',
            self::LATE          => 'yellow',
            self::NOT_SUBMITTED => 'red',
        };
    }

    /**
     * Get the icon class for the status.
     */
    public function icon(): string
    {
        return match ($this) {
            self::SUBMITTED     => 'heroicon-check-circle',
            self::LATE          => 'heroicon-clock',
            self::NOT_SUBMITTED => 'heroicon-x-circle',
        };
    }

    /**
     * Check if the submission is considered submitted (even if late).
     */
    public function isSubmitted(): bool
    {
        return in_array($this, [self::SUBMITTED, self::LATE]);
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
