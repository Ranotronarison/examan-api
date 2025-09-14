<?php

namespace App\Enum;

enum ExamStatus: string
{
    case CONFIRMED = 'CONFIRMED';
    case TO_ORGANIZE = 'TO_ORGANIZE';
    case CANCELLED = 'CANCELLED';
    case SEARCHING_PLACE = 'SEARCHING_PLACE';

    public function getLabel(): string
    {
        return match($this) {
            self::CONFIRMED => 'Confirmed',
            self::TO_ORGANIZE => 'To Organize',
            self::CANCELLED => 'Cancelled',
            self::SEARCHING_PLACE => 'Searching Place',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function getChoices(): array
    {
        return [
            'Confirmed' => self::CONFIRMED->value,
            'To Organize' => self::TO_ORGANIZE->value,
            'Cancelled' => self::CANCELLED->value,
            'Searching Place' => self::SEARCHING_PLACE->value,
        ];
    }
}
