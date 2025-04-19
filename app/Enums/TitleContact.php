<?php

namespace App\Enums;

enum TitleContact: int {
    case Primary = 1;
    case Secondary = 2;

    public function displayName(): string {
        return match ($this) {
            self::Primary => 'Primary',
            self::Secondary => 'Secondary',
        };
    }
}
