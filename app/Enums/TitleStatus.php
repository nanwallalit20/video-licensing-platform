<?php

namespace App\Enums;

enum TitleStatus: int {
    case Accepted = 1;
    case Rejected = 2;
    case Pending = 3;

    public function displayName(): string {
        return match ($this) {
            self::Accepted => 'Approved',
            self::Rejected => 'Declined',
            self::Pending => 'Pending',
        };
    }
}
