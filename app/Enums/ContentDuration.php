<?php

namespace App\Enums;

enum ContentDuration: int {
    case SHORT = 1;
    case MEDIUM = 2;
    case FULL_LENGTH = 3;

    /**
     * Get the description for the rating.
     */
    public function description(): string {
        return match ($this) {
            self::SHORT => 'Short (Under 30 mins)',
            self::MEDIUM => 'Medium (30-60 mins)',
            self::FULL_LENGTH => 'Full-length Feature (Over 60 mins)',
        };
    }
}
