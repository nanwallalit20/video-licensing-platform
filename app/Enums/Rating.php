<?php

namespace App\Enums;

enum Rating: string {
    case G = 'g';
    case PG = 'pg';
    case PG13 = 'pg_13';
    case R = 'r';
    case NC17 = 'nc_13';

    /**
     * Get the description for the rating.
     */
    public function description(): string {
        return match ($this) {
            self::G => 'G (General Audience)',
            self::PG => 'PG (Parental Guidance)',
            self::PG13 => 'PG-13 (Parent Strongly Cautioned)',
            self::R => 'R (Restricted)',
            self::NC17 => 'NC-17 (Adults Only)',
        };
    }
}
