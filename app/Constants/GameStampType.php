<?php

namespace App\Constants;

class GameStampType
{
    public const QUIZ = 'quiz';
    public const PHOTO = 'foto';

    /**
     * Ambil semua type dalam bentuk array
     */
    public static function all(): array
    {
        return [
            self::QUIZ,
            self::PHOTO
        ];
    }
}
