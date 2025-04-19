<?php

namespace App\Enums;

enum TitleFestival: int {
    case Accepted = 1;
    case Won = 2;

    public function displayName(): string {
        return match ($this) {
            self::Accepted => 'Accepted',
            self::Won => 'Won',
        };
    }
}
