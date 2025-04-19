<?php

namespace App\Enums;

enum ContentType: int {
    case FILMS = 1;
    case TV_SERIES = 2;
    case DOCUMENTARIES = 3;
    case SHORT_FILMS = 4;
    case OTHER = 5;

    /**
     * Get the description for the rating.
     */
    public function description(): string {
        return match ($this) {
            self::FILMS => 'Films',
            self::TV_SERIES => 'TV Series',
            self::DOCUMENTARIES => 'Documentaries',
            self::SHORT_FILMS => 'Short Films',
            self::OTHER => 'Other',
        };
    }
}
